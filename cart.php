<?php

require_once 'common.php';

$err = '';
$name = $contact = $comment = '';

if (! empty($_SESSION['ids'])) {
    $idValues = createArrayToBind($_SESSION['ids']);
    $stmt = $conn->prepare('SELECT * FROM products WHERE id IN(' . $idValues . ')');
    $products = execAndFetch($stmt, array_values($_SESSION['ids']));

} else {
    $products = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (! empty($_POST['id']) && ($key = array_search($_POST['id'], $_SESSION['ids'])) !== false) {
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
            $to = SMEMAIL;
            $subject = 'New order';

            $headers = 'From: ' . $email . " <" . $email . ">"; 
  
            $semi_rand = md5(time());  
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  

            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

            $message = "--{$mime_boundary}\n" ;
            
            $message .= $_POST['name'] . ' with the email ' . $_POST['email'] . ' wants the following products:' . "\n";

            
            foreach ($products as $product) {
                $message .= $product->title . ' - ' . $product->description . ' - ' .  $product->price . "\n";
            }

            if (! empty($_POST['comment'])) {
                $message .= $_POST['comment'] . "\n";
            }

            foreach ($products as $product) {
                $file = $product->img;
                if(!empty($file) > 0){ 
                    if(is_file($file)){ 
                        $message .= "--{$mime_boundary}\n"; 
                        $fp =    fopen($file,"rb"); 
                        $data =  fread($fp,filesize($file)); 
                
                        fclose($fp); 
                        $data = chunk_split(base64_encode($data)); 
                        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                        "Content-Description: ".basename($file)."\n" . 
                        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
                    } 
                } 
            }
            
            $message .= "--{$mime_boundary}--"; 
            $returnpath = "-f" . $email; 

            $retval = mail($to, $subject, $message, $headers, $returnpath);
        
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
                        <button><?= translate('Remove') ?></button> 
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
            <button><?= translate('Checkout') ?></button> 
        </div>
        <span class="error"><?php echo $err ?></span>
    </form>
    </div>
</body>
</html>
