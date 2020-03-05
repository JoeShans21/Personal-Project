<?php
session_start();
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$first = $last = $first_err = $last_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $first = trim($_POST["first"]);
        $last = trim($_POST["last"]);
        $fp = fopen('users.json', 'r');//opens file in append mode  
        $arr = json_decode(fread($fp, filesize("users.json")), TRUE);
        array_push($arr, ['first' => $first, 'last' => $last, 'id' => count($arr), 'username' => $username, 'password' => password_hash($password, PASSWORD_DEFAULT), 'time' => array(date('Y'), date('m'), date('d'), date('H'), date('i'))]);
        $json = json_encode($arr);
        fclose($fp);

        $newfp = fopen('users.json', 'w');
        fwrite($newfp, $json);
        fclose($newfp);
        $_SESSION['user']=$username;
        $_SESSION['first']=$arr[$a]['first'];
        $_SESSION['last']=$arr[$a]['last'];
        $_SESSION['id']=$arr[$a]['id'];
        $_SESSION['date']=$arr[$a]['date'];
        header("Location: http://localhost/social-media");
    }
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($first_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="first" class="form-control" value="<?php echo $first; ?>">
                <span class="help-block"><?php echo $first_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($last_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last" class="form-control" value="<?php echo $last; ?>">
                <span class="help-block"><?php echo $last_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>