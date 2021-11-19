<?php
require_once 'common.php';

if (isset($_GET['id']))
{
    $_SESSION['ids'][] = $_GET['id'];
    $_SESSION['ids'] = array_unique($_SESSION['ids']);
    echo var_dump($_SESSION);
    Header('Location: '.$_SERVER['PHP_SELF']);
}

$stmt = $conn->prepare('SELECT * FROM products;');
$stmt->execute();
$products = $stmt->fetchALL(PDO::FETCH_CLASS);
$checked = [];

foreach($products as $product) 
{
    $checked[$product->id] = false;
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
    <h1><?= translate('List of products')?></h1>
    <table class="center">
        <tr>
            <th></th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Add to cart</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <?php if (! in_array($product->id, $_SESSION['ids'])) : ?> 
                <tr> 
                    <td>
                        <img src='./book.jpg'/> 
                    </td>
                    <td>
                        <?= $product->title;?>
                    </td>
                    <td>
                        <?= $product->description;?>
                    </td>
                    <td>
                        <?= $product->price;?>
                    </td>
                    <td>
                        <a href=<?= "index.php?id=" . $product->id;?> >Add</a>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a  href="cart.php">Go to cart</a>
    </div>
    <br>
</body>
</html>
