<?php
require_once 'common.php';
if (isset($_GET['id'])) {
    if (($key = array_search($_GET['id'], $_SESSION['ids'])) !== false) {
        unset($_SESSION['ids'][$key]);
    }
    echo var_dump($_SESSION);
    Header('Location: '.$_SERVER['PHP_SELF']);
}
$stmt = $conn->prepare('SELECT * FROM products;');
$stmt->execute();

$allProducts = $stmt->fetchALL(PDO::FETCH_CLASS);
$products = array();
foreach($allProducts as $product) {
    if (in_array($product->id, $_SESSION['ids'])) {
        $products[] = $product;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Document')?></title>
    <style>
        h1 {
            text-align: center;
            font-size: 50pt;
        }
        table, th, td {
            border: 1px solid #000000;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        img {
            height: 30px;
            width: 30px;
        }
    </style>
</head>
<body>
    <h1><?= translate('Your cart')?></h1>
    <table class="center">
        <tr>
            <th></th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Remove from cart</th>
        </tr>
        <?php foreach($products as $product): ?>
            <tr> 
                <td>
                    <img src='./book.jpg'/> 
                </td>
                <td>
                    <?php echo $product->title;?>
                </td>
                <td>
                    <?php echo $product->description;?>
                </td>
                <td>
                    <?php echo $product->price;?>
                </td>
                <td>
                    <a href=<?= "cart.php?id=" . $product->id;?>>Remove</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a  href="index.php">Go to index</a>
    </div>
</body>
</html>
