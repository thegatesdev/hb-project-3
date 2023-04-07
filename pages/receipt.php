<?php
if (!isset($_SESSION['prod_list'])) {
    header("Location:page.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("./parts/head.php") ?>
    <link rel="stylesheet" href="style/receipt.css">
</head>

<body>
    <div class="flex_center">
        <div id="container">
            <h1>Kassabon</h1>
            <table id="receipt_table">
                <tr class="header_row">
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Eenheid</th>
                    <th>Prijs</th>
                </tr>
                <?php
                $total = 0.0;
                foreach ($_SESSION['prod_list'] as $entry) {
                    $product = $entry['product'];
                    $amount = $entry['amount'];
                    $total += $amount * ($price = $product['price']);
                    echo "<tr><td>{$product['description']}</td>
                        <td>$amount</td>
                        <td>{$product['unit']}</td>
                        <td>&euro;$price</td>
                        </tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>

</html>