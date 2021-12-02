<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

$products = prepareAndFetchAll($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (! empty($_POST['delete']) && $_POST['delete'] === 'delete' && ! empty($_POST['id']) ) {
        $stmt = $conn->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$_POST['id']]);
        $stmt = $conn->prepare('SELECT * FROM order_details WHERE product_id = ?');
        $stmt->execute([$_POST['id']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (! $order) {
            $stmt = $conn->prepare('DELETE FROM old_products WHERE id = ?');
            $stmt->execute([$_POST['id']]);
        }
        header('Location: products.php');
        exit;
    }
    if (! empty($_POST['edit']) && $_POST['edit'] === 'edit' && ! empty($_POST['idEdit'])) {
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
                    <a href="product.php?id=<?= $product->id ?>"><?= translate('Edit') ?></a>
                </td>
                <td>
                    <form action="products.php" method="post">
                        <input name="id" value="<?= $product->id ?>" type="hidden">
                        <button name="delete" value="delete"><?= translate('Delete') ?></button>
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