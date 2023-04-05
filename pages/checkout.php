<?php

if (isset($_POST['prod_add'])){
    if (isset($_SESSION['adding_product'])){ // Second cycle, input amount
        // We are trying to add a product to the list:
        $prod_input = $_SESSION['adding_product'];
        $amount = is_numeric($_POST['calc_input']) ? $_POST['calc_input'] : 1;

        $products =& $_SESSION['prod_list'];
        if (!isset($products)) $products = [];

        if (is_numeric($prod_input)) $prod_num = $prod_input;
        else { // Product by description
            $find_id = "SELECT product_num FROM product WHERE LOWER(description) LIKE LOWER('$prod_input');";
            $result = mysqli_query(gdb_get($db), $find_id);
            if (mysqli_num_rows($result) > 0) $prod_num = mysqli_fetch_array($result)[0];
        }
        if (isset($prod_num)){
            if (isset($products[$prod_num])){
                // Product is already in the list:
                // Increase the amount
                $products[$prod_num]['amount'] += $amount;
            }else{
                // Product is not yet in the list:
                // Get and add the inputted product data
                $query = "SELECT description, unit, price, storage FROM product WHERE product_num=$prod_num";
                $result = mysqli_query(gdb_get($db), $query);
                if (mysqli_num_rows($result) > 0){
                    $products[$prod_num] = array('amount' => $amount, 'product' => mysqli_fetch_assoc($result));
                }
            }
        }
        unset($_SESSION['adding_product']);
    }else{// First cycle, input product number
        $input = $_POST['calc_input'];
        if (!empty($input)) $_SESSION['adding_product'] = $input;
    }
} else if (isset($_POST['prod_remove'])){
    // We are trying to remove a product from the list
    $prod_index = $_POST['prod_selected']; // Index in table
    if (is_numeric($prod_index)){
        $prod_index--; // Account for table header being element 0
        $products =& $_SESSION['prod_list'];
        if (isset($products) && $prod_index < sizeof($products)){ // We have session products
            $prod_num = array_keys($products)[$prod_index]; // Index in table to product number ( key )
            if (isset($prod_num)) unset($products[$prod_num]);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" type="text/css" href="./style/checkout.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
    <script src="./js/checkout.js" defer></script>
    <title>Kassa</title>
</head>
<body>
    <form id="container" method="POST" onsubmit="beforeSubmit();">
        <input type="hidden" id="selected_prod_input" name="prod_selected"/>
        <div id="calculator">
            <input type="text" name="calc_input" id="calc_display" class="bordered_field" autofocus></input>
            <table id="keypad">
                <tr>
                    <td class="keybutton">1</td>
                    <td class="keybutton">2</td>
                    <td class="keybutton">3</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="keybutton">4</td>
                    <td class="keybutton">5</td>
                    <td class="keybutton">6</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="keybutton">7</td>
                    <td class="keybutton">8</td>
                    <td class="keybutton">9</td>
                    <td class="keybutton">0</td>
                </tr>
            </table>
        </div>
        <div id="products">
            <div id="display" class="bordered_field">
                <?php 
                    if (isset($_SESSION['adding_product'])) echo "<h2 id='display_title' class='flash_opacity'>Voer aantal in</h2>"; 
                    else echo "<h2 id='display_title'>Producten</h2>";
                ?>
                <table id="prodlist">
                    <tr><th>Naam</th><th>Eenheid</th><th>Prijs</th><th>Aantal</th></tr>
                    <?php
                        if (isset($_SESSION['prod_list'])){
                            foreach ($_SESSION['prod_list'] as $entry){
                                $product = $entry['product'];
                                echo "<tr>
                                <td>{$product['description']}</td>
                                <td>{$product['unit']}</td>
                                <td>&euro;{$product['price']}</td>
                                <td>{$entry['amount']}</td>
                                </tr>";
                            }
                        }
                    ?>
                </table>
            </div>
            <div id="buttons">
                <div>
                    <input type="submit" name="prod_add" id="addbutton" class="button-action" value="+"></input>
                    <input type="submit" name="prod_remove" id="removebutton" class="button-action" value="-"></input>
                </div>
                <div>
                    <input type="submit" id="nextbutton" class="button-action" value="Afrekenen"></input>
                </div>
            </div>
        </div>
    </form>
</body>
</html>