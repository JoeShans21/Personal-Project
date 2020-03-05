<?php
session_start();
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $username = trim($_POST["username"]);
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have at least 6 characters.";
        } else{
            $password = trim($_POST["password"]);
            $fp = fopen('users.json', 'r');//opens file in append mode  
            $arr = json_decode(fread($fp, filesize("users.json")), TRUE);
            fclose($fp);
            $isUser = FALSE;
            for ($a = 0; $a < count($arr); $a++) {
                if ($arr[$a]['username'] == $username) {
                    $isUser = TRUE;
                    $correctPassword = password_verify($password, $arr[$a]['password']);
                    if (!$correctPassword) {
                        $password_err = "Password is incorrect.";
                    }
                    else {
                        $_SESSION['user']=$username;
                        $_SESSION['first']=$arr[$a]['first'];
                        $_SESSION['last']=$arr[$a]['last'];
                        $_SESSION['id']=$arr[$a]['id'];
                        $_SESSION['date']=$arr[$a]['date'];
                        header("Location: http://107.15.138.161:8021/");
                    }
                }
            }
            if (!$isUser) {
                $username_err = "Username does not exist.";
            }
        }
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
        <h2>Login</h2>
        <p>Please fill this form to log in.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Don't have an account? <a href="signup.php">Signup</a>.</p>
        </form>
    </div>    
</body>
</html>