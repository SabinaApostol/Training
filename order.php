<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

if (! empty($_POST['details']) && $_POST['details'] === 'details' && ! empty($_POST['id'])) {
    $stmt = $conn->prepare('SELECT o.id, o.date, o.name, o.email, p.id as product_id, p.title as title, p.description as description, p.price as price, p.img as image 
                            FROM orders o
                            JOIN order_details od ON o.id=od.order_id
                            JOIN old_products p ON od.product_id=p.id AND o.id = ?');
    $stmt->execute([$_POST['id']]);
    $orderDetails = $stmt->fetchALL();
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
    <table class="center">
        <tr>
            <th><?= translate('Date') ?></th>
            <th><?= translate('Name') ?></th>
            <th><?= translate('Email') ?></th>
            <th><?= translate('Title') ?></th>
            <th><?= translate('Description') ?></th>
            <th><?= translate('Price') ?></th>
            <th><?= translate('Image') ?></th>
        </tr>
        <?php foreach ($orderDetails as $order) : ?>
            <tr>
                <td>
                    <?= $order['date'] ?>
                </td>
                <td>
                    <?= $order['name'] ?>
                </td>
                <td>
                    <?= $order['email'] ?>
                </td>
                <td>
                    <?= $order['title'] ?>
                </td>
                <td>
                    <?= $order['description'] ?>
                </td>
                <td>
                    <?= $order['price'] ?>
                </td>
                <td>
                    <img src="<?= $order['image'] ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
   
</body>
</html>