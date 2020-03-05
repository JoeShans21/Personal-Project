<?php @include 'header.php';?>



<?php
$content = "";
$content_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["content"])) {
        if(empty(trim($_POST["content"]))){
            $content_err = "No content";
        } else {
            $fp = fopen('posts.json', 'r');//opens file in append mode  
            $arr = json_decode(fread($fp, filesize("posts.json")), TRUE);
            array_push($arr, [ 'id' => @count($arr) , 'content' => trim( $_POST[ "content" ] ) , 'poster' => $_SESSION['user'] , 'time' => array(date('Y') , date('m') , date('d') , date('H') , date('i')) , 'likes' => [] , 'comments' => [] ]);
            $json = json_encode($arr);
            fclose($fp);

            $newfp = fopen('posts.json', 'w');
            fwrite($newfp, $json);
            fclose($newfp);
            header("Location: http://localhost/social-media");
        }
    }
    if (isset($_POST['like'])) {
        $fp = fopen('posts.json', 'r');//opens file in append mode  
        $arr = json_decode(fread($fp, filesize("posts.json")), TRUE);
        $likes = $arr[$_POST['id']]['likes'];
        if (!in_array($_SESSION['id'], $likes)) {
            $arr[$_POST['id']]['likes'] = array_merge($likes, [$_SESSION['id']]);
            $json = json_encode($arr);
            fclose($fp);
            $newfp = fopen('posts.json', 'w');
            fwrite($newfp, $json);
            fclose($newfp);
            header("Location: http://localhost/social-media");
        }
        else {
            fclose($fp);
        }
    }
    if (isset($_POST['comment'])) {
        $fp = fopen('posts.json', 'r');//opens file in append mode  
        $arr = json_decode(fread($fp, filesize("posts.json")), TRUE);
        $comments = $arr[$_POST['id']]['comments'];
        $comment_content = $_POST['comment_content'];
        $comment_poster = $_SESSION['user'];
        $arr[$_POST['id']]['comments'] = array_merge($comments, [[$comment_content, $comment_poster]]);
        $json = json_encode($arr);
        fclose($fp);
        $newfp = fopen('posts.json', 'w');
        fwrite($newfp, $json);
        fclose($newfp);
        header("Location: http://localhost/social-media");
    }
}
?>



<body class="w3-theme-l5">

<br>
<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">    
  <!-- The Grid -->
  <div class="w3-row">
    <!-- Left Column -->
    <div class="w3-col m3">
      <!-- Profile -->
        <div class="w3-card w3-round w3-white">
            <div class="w3-container">
                <h4 class="w3-center"><?php echo $_SESSION['user'];?></h4>
                <p class="w3-center"><img src="<?php echo findImg($_SESSION['user']); ?>" class="w3-circle" style="height:106px;width:106px" alt="No Profile Picture"></p>
                <hr>
                <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i> <?php echo $_SESSION['last'];?>, <?php echo $_SESSION['first'];?></p>
            </div>
        </div><br>
      
      <!-- Accordion -->
      <div class="w3-card w3-round">
        <div class="w3-white">
            <button onclick="myFunction('Demo1')" class="w3-button w3-block w3-theme-l1 w3-left-align"><i class="fa fa-circle-o-notch fa-fw w3-margin-right"></i> All Users</button>
            <div id="Demo1" class="w3-hide w3-container">
                <?php
                    $fp = fopen('users.json', 'r');  
                    $arr = json_decode(fread($fp, filesize("users.json")), TRUE);
                    fclose($fp);
                    for ($j = 0; $j < count($arr); $j++) {
                        echo '<p><a href="' . 'http://localhost/social-media/profile.php/?profile=' . $arr[$j]['username'] . '">' . $arr[$j]['username'] . '</a></p>';
                    }
                ?>
            </div>
        </div>      
      </div>
      <br>
      
      <!-- Interests  
      <div class="w3-card w3-round w3-white w3-hide-small">
        <div class="w3-container">
          <p>Interests</p>
          <p>
            <span class="w3-tag w3-small w3-theme-d5">News</span>
            <span class="w3-tag w3-small w3-theme-d4">W3Schools</span>
            <span class="w3-tag w3-small w3-theme-d3">Labels</span>
            <span class="w3-tag w3-small w3-theme-d2">Games</span>
            <span class="w3-tag w3-small w3-theme-d1">Friends</span>
            <span class="w3-tag w3-small w3-theme">Games</span>
            <span class="w3-tag w3-small w3-theme-l1">Friends</span>
            <span class="w3-tag w3-small w3-theme-l2">Food</span>
            <span class="w3-tag w3-small w3-theme-l3">Design</span>
            <span class="w3-tag w3-small w3-theme-l4">Art</span>
            <span class="w3-tag w3-small w3-theme-l5">Photos</span>
          </p>
        </div>
      </div>
      <br>
      -->
      
      <!-- Alert Box 
      <div class="w3-container w3-display-container w3-round w3-theme-l4 w3-border w3-theme-border w3-margin-bottom w3-hide-small">
        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-theme-l3 w3-display-topright">
          <i class="fa fa-remove"></i>
        </span>
        <p><strong>Hey!</strong></p>
        <p>People are looking at your profile. Find out who.</p>
      </div>
      -->
    <!-- End Left Column -->
    </div>
    
    <!-- Middle Column -->
    <div class="w3-col m7">
    
    <div class="w3-row-padding">
        <div class="w3-col m12">
          <div class="w3-card w3-round w3-white">
            <div class="w3-container w3-padding">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h6 class="w3-opacity">Speak your mind:</h6>
                    <input class="w3-border w3-padding" value="Status: Feeling Good" name="content"/>
                    <span class="help-block"><?php echo $content_err; ?></span>
                    <input type="submit" class="w3-button w3-theme" value="Post"/>
                </form>
            </div>
          </div>
        </div>
    </div>
    <?php
    $file = fopen('posts.json', 'r');//opens file in append mode  
    $posts = json_decode(fread($file, filesize("posts.json")), TRUE);
    fclose($file);
    if (count($posts) == 0){
        echo "<h4 style='margin-left:20px;'>No posts yet</h4>";
    } else {
        for ($i = count($posts)-1; $i >= 0; $i--) {
            $years = date("Y") - $posts[$i]["time"][0];
            $months = date("m") - $posts[$i]["time"][1];
            $days = date("d") - $posts[$i]["time"][2];
            $hours = date("H") - $posts[$i]["time"][3];
            $minutes = date("i") - $posts[$i]["time"][4];
            if ($years != 0) {
                $time = strval($years) . " years ago";
            } else if ($months != 0) {
                $time = strval($months) . " months ago";
            } else if ($days != 0) {
                $time = strval($days) . " days ago";
            } else if ($hours != 0) {
                $time = strval($hours) . " hours ago";
            } else if ($minutes != 0) {
                $time = strval($minutes) . " minutes ago";
            } else {
                $time = "Just Now";
            }
            $html = '
            <div class="w3-container w3-card w3-white w3-round w3-margin"><br>
                <img src="' . findImg($posts[$i]["poster"]) . '" alt="Avatar" class="w3-left w3-circle w3-margin-right" style="width:60px">
                <span class="w3-right w3-opacity">' . $time . '</span>
                <h4>' . $posts[$i]["poster"] . '</h4><br>
                <hr class="w3-clear">
                <p>' . $posts[$i]["content"] . '</p>
                <form method="post">
                    <input type="submit" name="like" class="w3-button w3-theme-d1 w3-margin-bottom" value="' . strval(@count($posts[$i]["likes"])) . '  Likes"/>
                    <input type="text" name="id" value="' . $posts[$i]["id"] . '" style="display: none"/>
                </form>
                <form method="post">
                    <input class="w3-border w3-padding" value="Status: Feeling Good" name="comment_content"/>
                    <input type="submit" name="comment" class="w3-button w3-theme-d2 w3-margin-bottom" value="Comment"/>
                    <input type="text" name="id" value="' . $posts[$i]["id"] . '" style="display: none"/>
                </form>
            </div>
            ';
            echo $html;

            for ($a = count($posts[$i]['comments'])-1; $a >= 0; $a--) {
                $comments = '
                <div class="w3-container w3-white w3-round w3-margin">
                    <h4>' . $posts[$i]['comments'][$a][1] . '</h4>
                    <p>' . $posts[$i]['comments'][$a][0] . '</p>
                </div>
                ';
                echo $comments;
            }
        }
    }
    ?>
    <!-- End Middle Column -->
    </div>
    
    <!-- Right Column -->
    <div class="w3-col m2">
      <div class="w3-card w3-round w3-white w3-center">
        <div class="w3-container">
          <p>Upcoming Events:</p>
          <p><strong>Personal Project Due</strong></p>
          <p>Friday 15:00</p>
          <p><button class="w3-button w3-block w3-theme-l4">Info</button></p>
        </div>
      </div>
      <br>
      <!--
      <div class="w3-card w3-round w3-white w3-center">
        <div class="w3-container">
          <p>Friend Request</p>
          <img src="/w3images/avatar6.png" alt="Avatar" style="width:50%"><br>
          <span>Jane Doe</span>
          <div class="w3-row w3-opacity">
            <div class="w3-half">
              <button class="w3-button w3-block w3-green w3-section" title="Accept"><i class="fa fa-check"></i></button>
            </div>
            <div class="w3-half">
              <button class="w3-button w3-block w3-red w3-section" title="Decline"><i class="fa fa-remove"></i></button>
            </div>
          </div>
        </div>
      </div>
      -->
      
      <div class="w3-card w3-round w3-white w3-padding-16 w3-center">
        <p>ADS</p>
      </div>
      <br>
      
      <div class="w3-card w3-round w3-white w3-padding-32 w3-center">
        <p><i class="fa fa-bug w3-xxlarge"></i></p>
      </div>
      
    <!-- End Right Column -->
    </div>
    
  <!-- End Grid -->
  </div>
  
<!-- End Page Container -->
</div>
<br>

<!-- Footer 
<footer class="w3-container w3-theme-d3 w3-padding-16">
  <h5>Footer</h5>
</footer>
 -->
<script>
// Accordion
function myFunction(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-theme-d1";
  } else { 
    x.className = x.className.replace("w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-theme-d1", "");
  }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>

</body>