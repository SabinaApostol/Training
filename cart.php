<?php

require_once 'common.php';

$err = "";
$name = $contact = $comment = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (! empty($_POST["id"]) && ($key = array_search($_POST['id'], $_SESSION['ids'])) !== false) {
        unset($_SESSION['ids'][$key]);
        header('Location: cart.php');
        exit;
    }

    if (empty($_POST['email']) || empty($_POST['name'])) {
        $err = 'Complete all required fields!';
    } else {
        $name = test_input($_POST["name"]);
        $email = test_input($_POST["email"]);

        if (empty($_POST['comment'])) {
            $comment = '';
          } else {
            $comment = test_input($_POST['comment']);
          }

        if (! preg_match("/^[a-zA-Z-' ]*$/", $name) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $err = 'Please correctly complete the required fields!';
        } elseif (! empty($_SESSION['ids'])) {
            $idValues = implode(', ', $_SESSION['ids']);
            $stmt = $conn->prepare('SELECT * FROM products WHERE id IN(' . $idValues . ')');
            $stmt->execute();
            $products = $stmt->fetchALL(PDO::FETCH_CLASS);
            $to = SMEMAIL;

            $subject = 'New order';

            $header ='From: '. $email . "\r\n" .
            'Reply-To: ' . $email . "\r\n";
        
            $message = $name . ' wtih the email ' . $email . ' wants the following products: ' . "\r\n";
            foreach ($products as $product) {
                $message .= $product->img . ' - ' .$product->id . ' - ' . $product->title;
                $message .=' - ' . $product->description . ' - ' . $product->price . "\r\n";
            }

            if ($comment !== '') {
                $message .= 'Comments: ' . $comment . "\r\n";
            }

            $retval = mail($to, $subject, $message, $header);
            if( $retval == true ) {
                unset($_SESSION['ids']);
                header('Location: index.php');
                exit;
            } else {
                echo 'Checkout failed...';
            }
        }
      }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (! empty($_SESSION['ids'])) {
    $idValues = implode(', ', $_SESSION['ids']);
    $stmt = $conn->prepare('SELECT * FROM products WHERE id IN(' . $idValues . ')');
    $stmt->execute();
    $products = $stmt->fetchALL(PDO::FETCH_CLASS);
} else {
    $products = [];
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
                    <?= $product->title;?>
                </td>
                <td>
                    <?= $product->description;?>
                </td>
                <td>
                    <?= $product->price;?>
                </td>
                <td>
                    <form action="cart.php" method="post">
                        <input name="id" value="<?= $product->id ?>" type="hidden">
                        <input type="submit" value="Remove">
                    </form> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><br>
    <div >
    <form style="text-align: center;" method="post" action="cart.php">  
        <input type="text" name="name" placeholder="<?= translate('Name') ?>" class="mywidth">
        <span class="error">*</span>
        <br>
        <input type="text" name="email" placeholder="<?= translate('Contact details') ?>" class="mywidth">
        <span class="error">*</span>
        <br>
        <textarea name="comment" cols="40" rows="10" placeholder="<?= translate('Comments') ?>"></textarea>
        <br>
        <div style="text-align: center;">
            <a  href="index.php"><?= translate('Go to index') ?></a>
            <input type="submit" name="submit" value="Checkout"> 
        </div>
        <span class="error"><?php echo $err;?></span>
    </form>
    </div>
</body>
</html>
