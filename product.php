<?php

require_once 'common.php';

if (! $_SESSION['admin']) {
    echo 'You have to be logged in as an admin to see this page!';
    die;
}

$err = [];

if (isset($_GET['id'])) {
    $stmt = $conn->prepare('SELECT title, description, price, img FROM products WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if  ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['title'])) {
        $err['title'] = translate('You must choose a title!');
    }
    if (empty($_POST['description'])) {
        $err['description'] = translate('You must add a description to the product!');
    }
    if (empty($_POST['price'])) {
        $err['price'] = translate('You must give a price!');
    }
    if (! isset($_GET['id']) && ! is_uploaded_file($_FILES['file']['tmp_name'])) {
        $err['file_upload'] = translate('You must choose a file!');
    }
    if (empty($err)) {
        $values = [
            'title' => strip_tags($_POST['title']),
            'description' => strip_tags($_POST['description']),
            'price' => strip_tags($_POST['price'])
        ];
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            if ($_FILES['file']['error'] !== 0) {
                $err['error_upload'] = 'There was an error uploading the file!';
            }
            if (strtolower(explode('/', mime_content_type($_FILES['file']['tmp_name']))[0]) != 'image') {
                $err['not_image'] = translate('File is not an image!');
            }

            if (empty($err)) {
                $tmp = explode('.', $_FILES['file']['name']);
                $fileExt = strtolower(end($tmp));
        
                $productName = strtolower($tmp[0]);
                $fileNameNew = time() . '.' . $fileExt;
                $fileDestination = 'uploads/' . $fileNameNew; 
                if (move_uploaded_file($_FILES['file']['tmp_name'], $fileDestination)) {
                    $values['img'] = $fileDestination;
                } else {
                    $err['move_upload'] = translate('File couldn\'t be moved!');
                }
            }
        } elseif (isset($_GET['id'])) {
            $values['img'] = $product['img'];
        }
        if (! isset($_GET['id']) && empty($err)) {
            $stmt = $conn->prepare('INSERT INTO products (title, description, price, img) VALUES (?, ?, ?, ?)');
            $stmt->execute(array_values($values));
            header('Location: products.php');
            exit;
        } elseif (isset($_GET['id']) && empty($err)) {
            $values['id'] =  $_POST['productId'];
            $stmt = $conn->prepare('UPDATE products SET title=?, description=?, price=?, img=? WHERE id=?');
            $stmt->execute(array_values($values));
            header('Location: products.php');
            exit;
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
    <title><?= translate('Document') ?></title>
    <style>
        .error {
            color: #FF0000;
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .mywidth {
            width: 300px;   
        }
    </style>
</head>
<body>
    <h1 class="center"><?= translate('Add/Edit product') ?></h1>
    <form class="center" action="product.php<?= isset($_GET['id']) ? '?id='.$_GET['id'] : NULL?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="productId" value="<?= $_GET['id'] ?? NULL ?>">
        <input class="mywidth" type="text" name="title" placeholder="<?= translate('Title') ?>" value="<?= $product['title'] ?? ($_POST['title'] ?? NULL) ?>">
        <?php if (array_key_exists('title', $err)) : ?>
            <br>
            <span class="error"><?= $err['title'] ?></span>
        <?php endif; ?>
        <br>
        <input class="mywidth" type="text" name="description" placeholder="<?= translate('Description') ?>" value="<?= $product['description'] ?? ($_POST['description'] ?? NULL) ?>">
        <?php if (array_key_exists('description', $err)) : ?>
            <br>
            <span class="error"><?= $err['description'] ?></span>
        <?php endif; ?>
        <br>
        <input class="mywidth" type="number" step="0.001" name="price" placeholder="<?= translate('Price') ?>" value="<?= $product['price'] ?? ($_POST['price'] ?? NULL) ?>">
        <?php if (array_key_exists('price', $err)) : ?>
            <br>
            <span class="error"><?= $err['price'] ?></span>
        <?php endif; ?>
        <br>
        <input type="file" name="file">
        <button><?= translate('Save') ?></button>
        <br>
        <?php if (isset($_GET['id'])) : ?>
            <p><?= translate('Old image: ') ?></p><img src="<?= $product['img'] ?>" width="30" height="30">
        <?php endif; ?>
        <br>
        <?php if (array_key_exists('file_upload', $err)) : ?>
            <span class="error"><?= $err['file_upload'] ?></span>
        <?php endif; ?>
        <?php if (array_key_exists('not_image', $err)) : ?>
            <span class="error"><?= $err['not_image'] ?></span>
        <?php endif; ?>
        <?php if (array_key_exists('error_upload', $err)) : ?>
            <span class="error"><?= $err['error_upload'] ?></span>
        <?php endif; ?>
        <?php if (array_key_exists('move_upload', $err)) : ?>
            <span class="error"><?= $err['move_upload'] ?></span>
        <?php endif; ?>
    </form>
</body>
</html>