<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location:login.php");
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" href=".style/login.css">
    <title>Uitloggen</title>
</head>
<body>
    <div class="flex_center">
        <div class="messagebox">
            <h2>Uit aan het loggen...</h2>
            <p><?php echo $_SESSION['user'] ?></p>
        </div>
    </div>
</body>
</html>