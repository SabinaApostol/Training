<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

$stmt = $conn->prepare('SELECT o.id, o.date, o.name, o.email, ROUND(SUM(p.price), 2) as sum
                        FROM orders o
                            JOIN order_details od ON o.id=od.order_id
                            JOIN old_products p ON od.product_id=p.id
                            GROUP BY o.date');
$stmt->execute();
$orders = $stmt->fetchALL(PDO::FETCH_CLASS);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Document')?></title>
    <style>
        table, th, td {
            border: 1px solid #000000;
            text-align: center;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        h1 {
            text-align: center;
            font-size: 50pt;
        }
    </style>
</head>
<body>
    <h1><?= translate('Orders') ?></h1>
    <table class="center">
        <tr>
            <th><?= translate('Date') ?></th>
            <th><?= translate('Customer name') ?></th>
            <th><?= translate('Customer email') ?></th>
            <th><?= translate('Price') ?></th>
            <th><?= translate('Details') ?></th>
        </tr>
        <?php foreach ($orders as $order) : ?>
            <tr>
                <td>
                    <?= $order->date ?>
                </td>
                <td>
                    <?= $order->name ?>
                </td>
                <td>
                    <?= $order->email ?>
                </td>
                <td>
                    <?= $order->sum ?>
                </td>
                <td>
                    <form action="order.php" method="post">
                        <input name="id" value="<?= $order->id ?>" type="hidden">
                        <button name='details', value='details'><?= translate('See details') ?></button>
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>