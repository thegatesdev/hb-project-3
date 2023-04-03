<?php

if (isset($_POST['prod_add'])){
    $input = $_POST['calc_input'];
    if (is_numeric($input)){
        $query = "SELECT id description unit price storage FROM product WHERE product_num=$input";
        $result = mysqli_query(gdb_get($db), $query);
        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" type="text/css" href="./style/checkout.css">
    <title>Kassa</title>
    <script src="./js/checkout.js" defer></script>
</head>
<body>
    <form id="container">
        <div id="calculator">
            <input type="text" name="calc_input" id="calc_display" class="bordered_field"></input>
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
                <h2 id="display_title">Producten</h2>
                <ul id="prodlist">
                    <li>Product 1</li>
                    <li>Product 2</li>
                    <li>Product 3</li>
                </ul>
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