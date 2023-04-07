<?php

if (!isset($selected_item)) {
    header("Refresh:0");
    exit;
}

$query = "SELECT p.product_num,p.price, p.unit, p.description, p.storage, s.id AS sid, g.id AS gid FROM product AS p 
INNER JOIN supplier AS s ON p.supplier = s.id 
INNER JOIN product_group AS g ON p.product_group=g.id 
WHERE p.id=$selected_item";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) $prod = mysqli_fetch_assoc($result);
if (!isset($prod)){
    header("Refresh:0");
    exit;
}

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
    <title>Item <?php echo $prod['product_num'] ?> bewerken</title>
</head>

<body>
    <div class="flex_center">
        <form method="post" id="container">
            <h1>Product <?php echo $prod['product_num'] ?> bewerken</h1>
            <input type="hidden" name="prod_selected" value=<?php echo "'$selected_item'" ?>>
            <?php
                echo "<h2>Omschrijving</h2>";
                echo "<input name='edit_description' type='text' value='{$prod['description']}'>";
                echo "<h2>Leverancier</h2>";

                echo "<select name='edit_supplier'>";
                foreach ($suppliers as $s) {
                    echo "<option value='{$s['id']}'>{$s['name']}</option>";
                }
                echo "</select>";

                echo "<h2>Artikelgroep</h2>";

                echo "<select name='edit_group'>";
                foreach ($groups as $g) {
                    echo "<option value='{$g['id']}'>{$g['name']}</option>";
                }
                echo "</select>";

                echo "<h2>Eenheid</h2>";
                echo "<input name='edit_unit' type='text' value='{$prod['unit']}'></input>";
                echo "<h2>Prijs</h2>";
                echo "<input name='edit_price' type='number' value='{$prod['price']}'></input>";
                echo "<h2>Aantal</h2>";
                echo "<input name='edit_storage' type='number' value='{$prod['storage']}'></input>";
                echo "<input type='submit' name='prod_edit'>"
            ?>
        </form>
    </div>
</body>

</html>