<?php

if (isset($_POST['search_submit'])){
    if (!empty($_POST['search_input'])){
        if (!isset($_SESSION['searches'])) $_SESSION['searches'] = [];
        $_SESSION['searches'][] = $_POST['search_input'];
    }
}else if (isset($_POST['search_clear'])) unset($_SESSION['searches']);

$query = "SELECT p.product_num, p.description, p.storage, s.name AS sname, g.name AS gname FROM product AS p 
INNER JOIN supplier AS s ON p.supplier = s.id
INNER JOIN product_group AS g ON p.product_group=g.id";

if (!empty($_SESSION['searches'])){
    $query .= " WHERE ";
    foreach ($_SESSION['searches'] as $searchterm){
        $query .= "(
        LOWER(p.description) LIKE LOWER('%$searchterm%') OR 
        LOWER(p.product_num) LIKE LOWER('%$searchterm%') OR 
        LOWER(g.name) LIKE LOWER('%$searchterm%') OR 
        LOWER(s.name) LIKE LOWER('%$searchterm%')
        ) AND ";
    }
    $query = substr($query, 0, -5) . ";";
}
$products = mysqli_fetch_all(mysqli_query(gdb_get($db), $query), MYSQLI_ASSOC);

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
    <form id="container" method="post">
        <input type="hidden" id="selected_prod_input" name="prod_selected">
        <div id="left">
            <div id="search">
                <input type="text" name="search_input" id="search_input" placeholder="Filteren...">
                <input type="submit" class="search_submit" name="search_submit" value="Zoek">
                <?php
                    if (isset($_SESSION['searches'])){
                        $filters = sizeof($_SESSION['searches']);
                        if ($filters === 1) $message = "1 filter weghalen";
                        else $message = "$filters filters weghalen";
                        echo "<input type='submit' class='search_submit' name='search_clear' value='$message'>";
                    }
                ?>
            </div>
            <div id="table_container">
                <table id="product_table">
                    <tr><th>Product ID</th><th>Product</th><th>Groep</th><th>Leverancier</th></tr>
                    <?php
                    if (!empty($products)){
                        foreach ($products as $prod){
                            echo "<tr>
                            <td>{$prod['product_num']}</td>
                            <td>{$prod['description']}</td>
                            <td>{$prod['gname']}</td>
                            <td>{$prod['sname']}</td>
                            </tr>";
                        }
                    }else echo "<tr><td>Geen resultaten</td></tr>";
                    ?>
                </table>
            </div>
        </div>
        <div id="right">
            <ul id="product_details">
                <li><h2>Data</h2></li>
                <li>Data</li>
                <li><h2>Data</li>
                <li>Data</li>
            </ul>
            <div id="product_actions">
                <input type="submit" id="prod_add" class="button-action" value="Bijboeken">
                <input type="submit" id="prod_remove" class="button-action" value="Afboeken">
                <input type="submit" id="prod_new" class="button-action" value="Nieuw product">
            </div>
        </div>
    </form>
</body>
</html>