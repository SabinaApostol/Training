<?php

require_once 'common.php';

$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $err = 'Complete all required fields!';
    } elseif ($_POST['username'] == ADMINUSERNAME && $_POST['password'] == ADMINPASSWORD) {
        header('Location: products.php');
        exit;
    } else {
        $err = 'Invalid credentials!';
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
        <span class="error">*</span>
        <br>
        <input type="password" name="password" placeholder="<?= translate('Password') ?>">
        <span class="error">*</span>
        <br>
        <button><?= translate('Login') ?></button>
        <br>
        <span class="error"><?php echo $err ?></span>
    </form>
</body>
</html>