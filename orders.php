<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

$stmt = $conn->prepare("SELECT * FROM orders");
$stmt->execute();
$orders = $stmt->fetchALL(PDO::FETCH_CLASS);
$allOrders = [];
foreach ($orders as $order) {
    $products = unserialize($order->purchasedProducts);
    $sum = 0;
    $customerDetails = unserialize($order->customerDetails);
    $orderDetails['customerDetails'] = $customerDetails;
    $orderDetails['date'] = $order->creationDate;
    foreach ($products as $product) {
        $sum += $product->price;
    }
    $orderDetails['price'] = $sum;
    
    $orderDetails['id'] = $order->id;
    $allOrders[] = $orderDetails; 
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
        table, th, td {
            border: 1px solid #000000;
            text-align: center;
        }
        .center{
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
        <?php foreach ($allOrders as $order) : ?>
            <tr>
                <td>
                    <?= $order['date'] ?>
                </td>
                <td>
                    <?= $order['customerDetails']['name'] ?>
                </td>
                <td>
                    <?= $order['customerDetails']['email'] ?>
                </td>
                <td>
                    <?= $order['price'] ?>
                </td>
                <td>
                    <form action="order.php" method="post">
                        <input name="idOrder" value="<?= $order['id'] ?>" type="hidden">
                        <button><?= translate('See details') ?></button>
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>