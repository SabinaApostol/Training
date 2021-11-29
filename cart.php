<?php

require_once 'common.php';

$err = [
    'Name field is empty'=>false,
    'Email field is empty'=>false,
    'Invalid name'=>false,
    'Invalid email'=>false,
    'Checkout failed'=>false

];
$fields = [
    'name'=>'',
    'email'=>'',
    'comment'=>''
];

if (! empty($_SESSION['ids'])) {
    $idValues = createArrayToBind($_SESSION['ids']);
    $stmt = $conn->prepare('SELECT * FROM products WHERE id IN(' . $idValues . ')');
    $products = execAndFetch($stmt, array_values($_SESSION['ids']));

} else {
    $products = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (! empty($_POST['remove']) && $_POST['remove'] == 'remove' && ! empty($_POST['id']) && ($key = array_search($_POST['id'], $_SESSION['ids'])) !== false) {
        unset($_SESSION['ids'][$key]);
        header('Location: cart.php');
        exit;
    }

    if (empty($_POST['email'])) {
        $err['Email field is empty'] = true;
    }

    if (empty($_POST['name'])) {
        $err['Name field is empty'] = true;
    } 
    
    if (! $err['Name field is empty'] && ! $err['Email field is empty']) {
        $fields['name'] = $_POST['name'];
        $fields['email'] = $_POST['email'];

        if (! empty($_SESSION['ids'])) {

            $customerDetails = [
                'name'=>$fields['name'],
                'email'=>$fields['email']
                
            ];
            
            $products = serialize($products);
            $customerDetails = serialize($customerDetails);
            $date = date('Y-m-d H:i:s');
            $values = [
                'date' => $date,
                'customerDetails' => $customerDetails,
                'products' => $products
            ];
            $placeHolders = createArrayToBind($values);
            $stmt = $conn->prepare('INSERT INTO orders(creationDate, customerDetails, purchasedProducts) VALUES(' . $placeHolders . ')');
            $stmt->execute(array_values($values));

            $to = SMEMAIL;
            $subject = 'New order';
            $headers = [
                'From: '. $fields['email'],
                'Content-Type:text/html;charset=UTF-8',
                'Reply-To: ' . $fields['email']
            ];
            $headers = implode("\r\n", $headers);

            ob_start();
            include 'email.php';
            $emailContent = ob_get_contents();
            ob_end_clean();

            $returnpath = '-f' . $email; 

            $retval = mail($to, $subject, $emailContent, $headers, $returnpath);
            
            if ($retval) {
                unset($_SESSION['ids']);
                header('Location: index.php');
                exit;
            } else {
                $err['Checkout failed'] = true;
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
        <input type="text" name="name" placeholder="<?= translate('Name') ?>" class="mywidth" value="<?= $_POST['name'] ?? NULL ?>">
        <?php if ($err['Name field is empty']) : ?>
            <br>
            <span class="error"><?= translate('Name field is empty!') ?></span>
        <?php endif; ?>
        <br>
        <input type="text" name="email" placeholder="<?= translate('Contact details') ?>" class="mywidth" value="<?= $_POST['email'] ?? NULL ?>">
        <?php if ($err['Email field is empty']) : ?>
            <br>
            <span class="error"><?= translate('Email field is empty!') ?></span>
        <?php endif; ?>
        <br>
        <textarea name="comment" cols="40" rows="10" placeholder="<?= translate('Comments') ?>" value="<?= $_POST['comment'] ?? NULL ?>"></textarea>
        <br>
        <div style="text-align: center;">
            <a  href="index.php"><?= translate('Go to index') ?></a>
            <button><?= translate('Checkout') ?></button> 
        </div>
        <?php if ($err['Checkout failed']) : ?>
            <span class="error"><?= translate('Checkout failed!') ?></span>
        <?php endif; ?>
    </form>
    </div>
</body>
</html>
