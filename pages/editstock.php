<?php

if (!isset($selected_item)) {
    header("Location:/");
    exit;
}

$query = "SELECT p.product_num, p.description, p.storage, s.id AS sid, g.id AS gid FROM product AS p 
INNER JOIN supplier AS s ON p.supplier = s.id 
INNER JOIN product_group AS g ON p.product_group=g.id 
WHERE p.id=$selected_item";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) $prod = mysqli_fetch_assoc($result)[0];

$g_query = "SELECT g.id, g.name FROM product_group AS g";
$s_query = "SELECT s.id, s.name FROM supplier AS s";

$result = mysqli_query($conn, $g_query);
$groups = mysqli_fetch_all($result, MYSQLI_ASSOC);
$result = mysqli_query($conn, $s_query);
$suppliers = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" type="text/css" href="./style/editstock.css">
    <title>Item <?php echo $selected_item ?> bewerken</title>
</head>

<body>
    <div class="flex_center">
        <form method="post" id="container">
            <?php
            if (isset($prod)) {
                echo "
                    <h2>Omschrijving</h2>
                    <input type='text' value='{$prod['description']}'>
                    <h2>Leverancier</h2>"; {
                    echo "<select name's_select'>";
                    foreach ($suppliers as $s) {
                        echo "<option value='{$s['name']}'>{$s['id']}</option>";
                    }
                    echo "</select>";
                }
                echo "<h2>Artikelgroep</h2>";
                echo "<h2>eenheid</h2>";
                echo "<h2>prijs</h2>";
                echo "<h2>aantal</h2>";
            }
            ?>
        </form>
    </div>
</body>

</html>