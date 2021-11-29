<?php

require_once 'common.php';

$err = [];
$_SESSION['admin'] = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        $err['username'] = translate('Username is required!');
    }

    if (empty($_POST['password'])) {
        $err['password'] = translate('Password is required!');
    }
    
    if (empty($err)) {
        if ($_POST['username'] == ADMINUSERNAME && $_POST['password'] == ADMINPASSWORD) {
            $_SESSION['admin'] = true;
            header('Location: products.php');
            exit;
        } else {
            $err['invalid'] = translate('Invalid credentials!');
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
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="center"><?= translate('Login to admin account') ?></h1>
    <form class="center" method="post" action="login.php">
        <input type="text" name="username" placeholder="<?= translate('Username') ?>">
        <?php if (array_key_exists('username', $err)) : ?>
            <br>
            <span class="error"><?= $err['username'] ?></span>
        <?php endif; ?>
        <br>
        <input type="password" name="password" placeholder="<?= translate('Password') ?>">
        <?php if (array_key_exists('password', $err)) : ?>
            <br>
            <span class="error"><?= $err['password'] ?></span>
        <?php endif; ?>
        <br>
        <button><?= translate('Login') ?></button>
        <br>
        <?php if (array_key_exists('invalid', $err)) : ?>
            <span class="error"><?= $err['invalid'] ?></span>
        <?php endif; ?>
    </form>
</body>
</html>