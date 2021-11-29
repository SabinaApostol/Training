<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

if (! empty($_POST['idOrder'])) {

    $stmt = $conn->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$_POST['idOrder']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $products = unserialize($order['purchasedProducts']);
    $customerDetails = unserialize($order['customerDetails']);
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
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        img {
            height: 30px;
            width: 30px;
        }
        p, ul {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1><?= translate('Order') ?></h1>
    <p class="center"><?= translate('Checkout information: ') ?></p>
    <table class="center">
        <tr>
            <th><?= translate('Date') ?></th>
            <th><?= translate('Name') ?></th>
            <th><?= translate('Email') ?></th>
        </tr>
        <tr>
            <td>
                <?= $order['creationDate'] ?>
            </td>
            <td>
                <?= $customerDetails['name'] ?>
            </td>
            <td>
                <?= $customerDetails['email'] ?>
            </td>
        </tr>
    </table>
    <br>
    <p class="center"><?= translate('Products: ') ?></p>
    <table class="center">
        <tr>
            <th><?= translate('Image') ?></th>
            <th><?= translate('Title') ?></th>
            <th><?= translate('Description') ?></th>
            <th><?= translate('Price') ?></th>
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
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>