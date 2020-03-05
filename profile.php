<?php
	include 'header.php';
	$profile = htmlspecialchars($_GET["profile"]);


	
	if (isset($_POST['submitpfp'])) {
		$target_dir = "pfps/";
		$file_name = $profile . "." . explode(".", basename($_FILES["fileToUpload"]["name"]))[1];
		$target_file = $target_dir . $filename;
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    	if($check !== false) {
        	$uploadOk = 1;
    	} else {
        	echo "File is not an image.";
        	$uploadOk = 0;
    	}
    	// Check if file already exists
		if (file_exists($target_file)) {
    		echo "Sorry, file already exists.";
    		$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
    		echo "Sorry, your file is too large.";
    		$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    		$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
    		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
    		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        		header("Location: http://107.15.138.161/profile.php/?profile=" . $profile);
    		} else {
        		echo "Sorry, there was an error uploading your file.";
    		}
		}
	}

	
?>
<br><br><br>
<h4 class="w3-center"><?php echo $profile;?></h4>
<p class="w3-center"><img src="<?php echo findImg($profile); ?>" class="w3-circle" style="height:106px;width:106px" alt="No Profile Picture"></p>
<hr>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?profile=" . $profile; ?>" method="post" enctype="multipart/form-data" style="display:none;" id="pfpform">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submitpfp">
</form>
<?php 
	if ($profile == $_SESSION['user']) {
		$jpgExists = file_exists('pfps/' . $profile . '.jpg') or file_exists('pfps/' . $profile . '.JPG');
		$jpegExists = file_exists('pfps/' . $profile . '.jpeg') or file_exists('pfps/' . $profile . '.JPEG');
		$pngExists = file_exists('pfps/' . $profile . '.png') or file_exists('pfps/' . $profile . '.png');
		$gifExists = file_exists('pfps/' . $profile . '.gif') or file_exists('pfps/' . $profile . '.GIF');
		if (!$jpgExists && !$jpegExists && !$pngExists && !$gifExists) {
			echo '<script>document.getElementById("pfpform").style.display = "block";</script>';
		}
	}
	$fp = fopen('posts.json', 'r');  
    $posts = json_decode(fread($fp, filesize("posts.json")), TRUE);
    fclose($fp);
    for ($i = count($posts)-1; $i >= 0; $i--) {
    	if ($posts[$i]['poster'] == $profile) {
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
