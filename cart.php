<?php

require_once 'common.php';

$err = [];

if (! empty($_SESSION['ids'])) {
    $idValues = createArrayToBind($_SESSION['ids']);
    $stmt = $conn->prepare('SELECT * FROM products WHERE id IN (' . $idValues . ')');
    $products = execAndFetch($stmt, array_values($_SESSION['ids']));
} else {
    $products = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['remove'] ?? NULL === 'remove') {
        if (! empty($_POST['id']) && ($key = array_search($_POST['id'], $_SESSION['ids'])) !== false) {
            unset($_SESSION['ids'][$key]);
        }
        header('Location: cart.php');
        exit;
    }

    if (empty($_POST['email'])) {
        $err['email_empty'] = translate('Email field is empty');
    }

    if (empty($_POST['name'])) {
        $err['name_empty'] = translate('Name field is empty');
    } 
    
    if (empty($err)) {
        if (! empty($_SESSION['ids'])) {
            $date = date('Y-m-d H:i:s');
            $values = [
                'date' => $date,
                'name' => strip_tags($_POST['name']),
                'email' => strip_tags($_POST['email'])
            ];
            $stmt = $conn->prepare('INSERT INTO orders (date, name, email) VALUES (? , ?, ?)');
            $stmt->execute(array_values($values));

            $stmt = $conn->prepare('SELECT id FROM orders WHERE date = ?');
            $stmt->execute([$date]);
            $orderId = $stmt->fetch(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                $values = [
                    'order' => $orderId['id'],
                    'product' => $product->id
                ];
                $stmt = $conn->prepare('INSERT INTO order_details (order_id, product_id) VALUES (?, ?)');
                $stmt->execute(array_values($values));
            }

            $to = SMEMAIL;
            $subject = 'New order';
            $headers = [
                'From' => strip_tags($_POST['email']),
                'Content-Type' => 'text/html;charset=UTF-8',
                'Reply-To' => strip_tags($_POST['email'])
            ];

            ob_start();
            include 'email.php';
            $emailContent = ob_get_clean(); 

            $retval = mail($to, $subject, $emailContent, $headers);
            
            if ($retval) {
                unset($_SESSION['ids']);
                header('Location: index.php');
                exit;
            } else {
                $err['checkout_failed'] = translate('Checkout failed');
            }  
        }    
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
        .error {
            color: #FF0000;
        }
        .mywidth {
            width: 300px;   
        }
    </style>
</head>
<body>
    <h1><?= translate('Your cart') ?></h1>
    <table class="center">
        <tr>
            <th></th>
            <th><?= translate('Title') ?></th>
            <th><?= translate('Description') ?></th>
            <th><?= translate('Price') ?></th>
            <th><?= translate('Remove from cart') ?></th>
        </tr>
        <?php foreach ($products as $product): ?>
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
                    <form action="cart.php" method="post">
                        <input name="id" value="<?= $product->id ?>" type="hidden">
                        <button name="remove" value="remove"><?= translate('Remove') ?></button> 
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><br>
    <div >
    <form style="text-align: center;" method="post" action="cart.php">  
        <input type="text" name="name" placeholder="<?= translate('Name') ?>" class="mywidth">
        <?php if (array_key_exists('name_empty', $err)) : ?>
            <br>
            <span class="error"><?= $err['name_empty'] ?></span>
        <?php endif; ?>
        <br>
        <input type="text" name="email" placeholder="<?= translate('Contact details') ?>" class="mywidth">
        <?php if (array_key_exists('email_empty', $err)) : ?>
            <br>
            <span class="error"><?= $err['email_empty'] ?></span>
        <?php endif; ?>
        <br>
        <textarea name="comment" cols="40" rows="10" placeholder="<?= translate('Comments') ?>"></textarea>
        <br>
        <div style="text-align: center;">
            <a  href="index.php"><?= translate('Go to index') ?></a>
            <button><?= translate('Checkout') ?></button> 
        </div>
        <?php if (array_key_exists('checkout_failed', $err)) : ?>
            <span class="error"><?= $err['checkout_failed'] ?></span>
        <?php endif; ?>
    </form>
    </div>
</body>
</html>
