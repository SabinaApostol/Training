<?php

require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['ids'][] = $_POST['id'];
    $_SESSION['ids'] = array_unique($_SESSION['ids']);
    header('Location: index.php');
    exit;
}

if (empty($_SESSION['ids'])) {
    $products = prepareAndFetchAll($conn);
} else {
    $placeHolders = createArrayToBind($_SESSION['ids']);
    $stmt = $conn->prepare('SELECT * FROM products WHERE id NOT IN( ' . $placeHolders .' )');
    $products = execAndFetch($stmt, array_values($_SESSION['ids']));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Document') ?></title>
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
            <th><?= translate('Add to cart') ?></th>
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
                    <form action="index.php" method="post">
                        <input name="id" value="<?= $product->id ?>" type="hidden">
                        <button><?= translate('Add') ?></button>
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a href="cart.php"><?= translate('Go to cart') ?></a>
    </div>
    <br>
</body>
</html>
