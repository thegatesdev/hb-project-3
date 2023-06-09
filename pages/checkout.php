<?php

if (isset($_POST['prod_add'])) {
    if (isset($_SESSION['adding_product'])) { // Second cycle, input amount
        // We are trying to add a product to the list:
        $prod_input = $_SESSION['adding_product'];
        $amount = is_numeric($_POST['calc_input']) ? $_POST['calc_input'] : 1;

        $products = &$_SESSION['prod_list'];
        if (!isset($products)) $products = [];

        if (is_numeric($prod_input)) $prod_num = (int) $prod_input;
        else { // Product by description
            $prod_input = mysqli_escape_string($conn, $prod_input);
            $find_id = "SELECT product_num FROM product WHERE LOWER(description) LIKE LOWER('$prod_input');";
            $result = mysqli_query($conn, $find_id);
            if (mysqli_num_rows($result) > 0) $prod_num = mysqli_fetch_array($result)[0];
        }
        if (isset($prod_num)) {
            if (isset($products[$prod_num])) {
                // Product is already in the list:
                // Increase the amount
                $entry = &$products[$prod_num];
                $product = $entry['product'];
                if (($entry['amount'] += $amount) > $product['storage']) {
                    $entry['amount'] = $product['storage'];
                }
                $products[$prod_num] = $entry;
            } else {
                // Product is not yet in the list:
                // Get and add the inputted product data
                $query = "SELECT description, unit, price, storage FROM product WHERE product_num=$prod_num";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
                    if ($data['storage'] < $amount) $amount = $data['storage'];
                    $products[$prod_num] = array('amount' => $amount, 'product' => $data);
                }
            }
        }
        unset($_SESSION['adding_product']);
    } else { // First cycle, input product number
        $input = $_POST['calc_input'];
        if (!empty($input)) $_SESSION['adding_product'] = $input;
    }
} else if (isset($_POST['prod_remove'])) {
    // We are trying to remove a product from the list
    $prod_index = $_POST['prod_selected']; // Index in table
    if (is_numeric($prod_index)) {
        $prod_index--; // Account for table header being element 0
        $products = &$_SESSION['prod_list'];
        if (isset($products) && $prod_index < sizeof($products)) { // We have session products
            $prod_num = array_keys($products)[$prod_index]; // Index in table to product number ( key )
            if (isset($prod_num)) unset($products[$prod_num]);
        }
    }
    unset($_SESSION['adding_product']);
} else if (isset($_POST['prod_done'])) {
    include("./pages/receipt.php");
    exit;
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
        <input type="hidden" id="selected_prod_input" name="prod_selected" />
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
                <div id="prodlist_container">
                    <table id="prodlist">
                        <tr>
                            <th>Naam</th>
                            <th>Eenheid</th>
                            <th>Prijs</th>
                            <th>Aantal</th>
                        </tr>
                        <?php
                        if (isset($_SESSION['prod_list'])) {
                            $total = 0.0;
                            foreach ($_SESSION['prod_list'] as $entry) {
                                $product = $entry['product'];
                                $amount = $entry['amount'];
                                $total += $amount * ($price = $product['price']);
                                echo "<tr";
                                if ($amount === $product['storage']) echo " style='color:var(--color-err);'";
                                echo "><td>{$product['description']}</td>
                                <td>{$product['unit']}</td>
                                <td>&euro;$price</td>
                                <td>$amount</td>
                                </tr>";
                            }
                        }
                        ?>
                    </table>
                </div>
                <h3 id="total_price">Totaal: &euro;<?php echo isset($total) ? $total : 0.0 ?></h3>
            </div>
            <div id="buttons">
                <div>
                    <input type="submit" name="prod_add" id="addbutton" class="button-action" value="+"></input>
                    <input type="submit" name="prod_remove" id="removebutton" class="button-action" value="-"></input>
                </div>
                <div>
                    <input type="submit" name="prod_done" id="nextbutton" class="button-action" value="Afrekenen"></input>
                </div>
            </div>
        </div>
    </form>
</body>

</html>