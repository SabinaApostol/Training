<?php

require_once 'common.php';

$stmt = $conn->prepare('SELECT * FROM products');
$stmt->execute();
$products = $stmt->fetchALL(PDO::FETCH_CLASS);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($_POST['idDelete'])) {
        $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
        $stmt->bindValue(1, $_POST['idDelete'], PDO::PARAM_INT);
        $stmt->execute();
        header('Location: products.php');
        exit;
    }
    if (! empty($_POST['idEdit'])) {
        header('Location: product.php');
        exit;
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
            text-align: center;
        }
        .center{
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
<h1><?= translate('List of products') ?></h1>
    <table class="center">
        <tr>
            <th></th>
            <th><?= translate('Title') ?></th>
            <th><?= translate('Description') ?></th>
            <th><?= translate('Price') ?></th>
            <th><?= translate('Edit product') ?></th>
            <th><?= translate('Delete product') ?></th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr> 
                <td>
                    <img src="<?= $product->img ?>">
                </td>
                <td>
                    <?= $product->title ?>
                </td>
                <td>
                    <?= $product->description ?>
                </td>
                <td>
                    <?= $product->price ?>
                </td>
                <td>
                    <form action="product.php" method="post">
                        <input name="idEdit" value="<?= $product->id ?>" type="hidden">
                        <input type="submit" value="Edit">
                    </form> 
                </td>
                <td>
                    <form action="products.php" method="post">
                        <input name="idDelete" value="<?= $product->id ?>" type="hidden">
                        <input type="submit" value="Delete">
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a  href="product.php"><?= translate('Add') ?></a>
        <a  href="login.php"><?= translate('Logout') ?></a>
    </div>
</body>
</html>