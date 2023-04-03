<?php
session_start();

if (isset($_SESSION['user'])){
    // A session is already active:
    // Redirect to the proper page immideately

    header("Location:page.php");
    exit;
}

include("./database.php");
if (isset($_POST['login'])){
    // The user submitted the login form:
    // Try to log in the user

    $input_username = $_POST['input_username'];
    $input_pwd = $_POST['input_pwd'];
    $err = gdb_validate($logindb, $input_username, $input_pwd);

    if ($err) $message = $err . "!"; // An ERROR! D:
    else { // No ERROR! :D
        // Start new session for this user
        $_SESSION['user'] = $input_username;
        header("Location:page.php");
        exit;
    }
}else if (isset($_POST['create_account'])){
    // The user submitted the create account form:
    // Try to create a new account
    
    $input_username = $_POST['input_username'];
    $input_pwd = $_POST['input_pwd'];
    $input_pwd_check = $_POST['input_pwd_check'];

    if ($input_pwd === $input_pwd_check){
        $err = gdb_create($logindb, $input_username, $input_pwd);

        if ($err) $message = $err . "!"; // An ERROR! D:
        else { // No ERROR :D
            // Account created:
            // Back to login
            header("Refresh:0");
            exit; // Don't parse further, we are redirecting
        }

    }else $message = "De wachtwoorden zijn niet hetzelfde!";
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" href="./login.css">
    <title>Login</title>
</head>
<body>
    <div class="flex_center">
        <div id="form">
            <?php
                if (isset($_POST['new_account']) || isset($_POST['create_account'])) include("./parts/createaccform.php");
                else include("./parts/loginform.php");
            ?>
        </div>
        <?php if (isset($message))echo "<div class='messagebox error'><p>$message</p></div>"; ?>
    </div>
</body>
</html>