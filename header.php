<?php 
    session_start();
    if (!isset($_SESSION['user']))
        header("Location: http://joeshanahan.com/login.php");
    $search_err = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['search'])) {
            $fp = fopen('users.json', 'r');  
            $arr = json_decode(fread($fp, filesize("users.json")), TRUE);
            fclose($fp);
            $userExists = FALSE;
            for ($a = 0; $a < count($arr); $a++) {
                if ($arr[$a]['username'] == trim($_POST["search"])) {
                    $userExists = TRUE;
                }
            }
            if ($userExists){
                header("Location: http://joeshanahan.com/profile.php/?profile=" . trim($_POST["search"]));
            } else {
                $search_err = "User does not exist.";
            }
        }
    }
    function findImg($user) {
        $imgTypes = ['.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG', '.gif', '.GIF'];
        for ($a = 0; $a < count($imgTypes); $a++) {
            if (file_exists('pfps/' . $user . $imgTypes[$a]))
                return 'http://joeshanahan.com/pfps/' . $user . $imgTypes[$a];
        }
    }
?>
<title>Personal Project</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html, body, h1, h2, h3, h4, h5 {font-family: "Open Sans", sans-serif}
</style>
<body class="w3-theme-l5">
<!-- Navbar -->
<div class="w3-top">
    <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
        <a href="http://joeshanahan.com/" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i></a>
        <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="News"><i class="fa fa-globe"></i></a>
        <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Account Settings"><i class="fa fa-user"></i></a>
        <div class="w3-dropdown-hover w3-hide-small">
            <button class="w3-button w3-padding-large" title="Notifications"><i class="fa fa-bell"></i><span class="w3-badge w3-right w3-small w3-green">3</span></button>     
            <div class="w3-dropdown-content w3-card-4 w3-bar-block" style="width:300px">
                <a href="#" class="w3-bar-item w3-button">One new friend request</a>
                <a href="#" class="w3-bar-item w3-button">John Doe posted on your wall</a>
                <a href="#" class="w3-bar-item w3-button">Jane likes your post</a>
            </div>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input name="search" class="w3-bar-item w3-hide-small w3-padding-large" type="text" placeholder="Search..">
            <button href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Search"><i class="fa fa-search"></i></button>
            <span class="help-block"><?php echo $search_err; ?></span>
        </form>
        <a href="<?php echo 'http://joeshanahan.com/profile.php/?profile=' . $_SESSION['user'];?>" class="w3-round sw3-bar-item w3-button w3-hide-small w3-right w3-padding-large w3-hover-white" title="My Account">
            <img src="<?php echo findImg($_SESSION['user']); ?>" class="w3-circle" style="width:35px" alt="Avatar">
        </a>
    </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 1</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 2</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 3</a>
    <a href="#" class="w3-bar-item w3-button w3-padding-large">My Profile</a>
</div>
<body/>