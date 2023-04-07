<?php
const PAGES = "pages/";

session_start();
if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['user'])) {
    // Not logged in:
    // Back to login page
    header("Location:login.php");
    exit;
}

include_once("./database.php");

if ($conn) {
    $type = user_type($conn, $_SESSION['user']);
    if ($type) {
        switch ($type[0]) {
            case 1:
                $page = "management.php";
                break;
            case 2:
                $page = "checkout.php";
                break;
            case 3:
                $page = "stock.php";
                break;
        }
        if (isset($page)) {
            include(PAGES . $page);
            exit;
        }
    }
} else $message = "Kon niet verbinden met de server!";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" href="./login.css">
</head>

<body>
    <?php if (isset($message)) echo "<h1>$message</h1>" ?>
    <div class="flex_center">
        <div class="messagebox">
            <h2>Deze gebruiker heeft geen landingspagina</h2>
            <button id="logout_button" class="button-big">Uitloggen</button>
            <script type="text/javascript">
                document.getElementById("logout_button").onclick = function() {
                    location.href = "logout.php";
                }
            </script>
        </div>
    </div>
</body>

</html>