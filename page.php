<?php
const PAGES = "pages/";

session_start();
if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['user'])){
    // Not logged in:
    // Back to login page
    header("Location:login.php");
    exit;
}

include_once("./database.php");
$type = user_type($_SESSION['user']);
$page = "nopage.php";
if ($type){
    switch ($type[0]){
        case 2:
            $page = "checkout.php";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" href="./login.css">
</head>
<body>
    <?php include(PAGES . $page) ?>
</body>
</html>