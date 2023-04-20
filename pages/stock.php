<?php

$selected_item = isset($_POST['prod_selected']) && is_numeric($_POST['prod_selected']) ? (int)$_POST['prod_selected'] : null;

if (isset($_POST['search_submit'])) {
    if (!empty($_POST['search_input'])) {
        if (!isset($_SESSION['searches'])) $_SESSION['searches'] = [];
        $_SESSION['searches'][] = mysqli_escape_string($conn, $_POST['search_input']);
    }
} else if (isset($_POST['search_clear'])) unset($_SESSION['searches']);
else if (isset($_POST['prod_change'])) {
    include("./pages/editstock.php");
    exit;
}else if (isset($_POST['prod_edit']) && isset($selected_item)){
    $desc = mysqli_escape_string($conn, $_POST['edit_description']);
    $supp = mysqli_escape_string($conn, $_POST['edit_supplier']);
    $gr = mysqli_escape_string($conn, $_POST['edit_group']);
    $unit = mysqli_escape_string($conn, $_POST['edit_unit']);
    $price = mysqli_escape_string($conn, $_POST['edit_price']);
    $stor = mysqli_escape_string($conn, $_POST['edit_storage']);
    $query = "UPDATE product SET 
    description = '$desc',
    supplier = '$supp',
    product_group = '$gr',
    unit = '$unit',
    price = '$price',
    storage = '$stor' 
    WHERE product_num = $selected_item";
    mysqli_query($conn, $query);
}else if (isset($_FILES['import_product'])){
    $filename = $_FILES["import_product"]["tmp_name"];

    if (!empty($filename) && $handle = fopen($filename, "r")) {
        $import_data = array();
        while (($data = fgetcsv($handle, 1000)) !== false) {
            $import_data[] = $data; // Append to end of array
        }
        fclose($handle);
    }

    if (isset($import_data)){
        foreach ($import_data as $row) {
            for ($i = 0; $i > count($row); $i++) $row[$i] = mysqli_escape_string($conn, $row[$i]);
            
            $num = (int)filter_var($row[0], FILTER_SANITIZE_NUMBER_INT);
            // Intval?? No sir!! xD
            $query = "INSERT INTO product (product_num, description, supplier, product_group, unit, price, storage) 
            VALUES ($num, '{$row[1]}', '{$row[2]}', '{$row[3]}', '{$row[4]}', '{$row[5]}', '{$row[6]}') 
            ON DUPLICATE KEY UPDATE 
            description = VALUES(description),
            supplier = VALUES(supplier),
            product_group = VALUES(product_group),
            unit = VALUES(unit),
            price = VALUES(price),
            storage = storage + VALUES(storage)";
            mysqli_query($conn, $query);
        }
    }
}

// Look up products list
$query = "SELECT p.product_num, p.description, p.storage, s.name AS sname, g.name AS gname FROM product AS p 
INNER JOIN supplier AS s ON p.supplier = s.id
INNER JOIN product_group AS g ON p.product_group=g.id";

if (!empty($_SESSION['searches'])) {
    $query .= " WHERE ";
    foreach ($_SESSION['searches'] as $searchterm) {
        $query .= "(
        LOWER(p.description) LIKE LOWER('%$searchterm%') OR 
        LOWER(p.product_num) LIKE LOWER('%$searchterm%') OR 
        LOWER(g.name) LIKE LOWER('%$searchterm%') OR 
        LOWER(s.name) LIKE LOWER('%$searchterm%')
        ) AND ";
    }
    $query = substr($query, 0, -5) . ";";
}
$products = mysqli_fetch_all(mysqli_query($conn, $query), MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" type="text/css" href="./style/stock.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
    <script src="./js/stock.js" defer></script>
    <title>Magazijn</title>
</head>

<body>
    <form id="container" method="post" enctype="multipart/form-data">
        <input type="hidden" value='<?php echo $selected_item ?>' id="selected_prod_input" name="prod_selected">
        <div id="left">
            <div id="search">
                <input type="text" name="search_input" id="search_input" placeholder="Filteren...">
                <input type="submit" class="search_submit" name="search_submit" value="Zoek">
                <?php
                if (isset($_SESSION['searches'])) {
                    $filters = sizeof($_SESSION['searches']);
                    if ($filters === 1) $message = "1 filter weghalen";
                    else $message = "$filters filters weghalen";
                    echo "<input type='submit' class='search_submit' name='search_clear' value='$message'>";
                }
                ?>
            </div>
            <div id="table_container">
                <table id="product_table">
                    <tr>
                        <th>Product ID</th>
                        <th>Product</th>
                        <th>Groep</th>
                        <th>Leverancier</th>
                    </tr>
                    <?php
                    if (!empty($products)) {
                        foreach ($products as $prod) {
                            echo "<tr>
                            <td>{$prod['product_num']}</td>
                            <td>{$prod['description']}</td>
                            <td>{$prod['gname']}</td>
                            <td>{$prod['sname']}</td>
                            </tr>";
                        }
                    } else echo "<tr><td>Geen resultaten</td></tr>";
                    ?>
                </table>
            </div>
        </div>
        <div id="right">
            <ul id="product_details">
                <?php
                if (isset($selected_item) && is_numeric($selected_item)) {
                    $query = "SELECT p.product_num, p.description, p.storage, s.name AS sname, g.name AS gname FROM product AS p 
                    INNER JOIN supplier AS s ON p.supplier = s.id
                    INNER JOIN product_group AS g ON p.product_group=g.id
                    WHERE p.product_num=$selected_item";    
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0){
                        $prod = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
                        echo "<li><h2>Product</h2></li>";
                        echo "<li>{$prod['description']}</li>";
                        echo "<li><h2>Product ID</h2></li>";
                        echo "<li>{$prod['product_num']}</li>";
                        echo "<li><h2>Product Groep</h2></li>";
                        echo "<li>{$prod['gname']}</li>";
                        echo "<li><h2>Leverancier</h2></li>";
                        echo "<li>{$prod['sname']}</li>";
                        echo "<li><h2>Op voorraad</h2></li>";
                        echo "<li>{$prod['storage']}</li>";
                    }
                }
                ?>
            </ul>
            <div id="product_actions">
                <input type="submit" name="prod_change" id="prod_change" class="button-action" value="Bewerken">
                <input style="display: none;" id="import_select" type="file" name="import_product" onchange="form.submit()">
                <input type="button" id="prod_import" class="button-action" value="Importeren.." onclick="document.getElementById('import_select').click();">
            </div>
        </div>
    </form>
</body>

</html>