<?php

require_once 'common.php';

$err = '';

if (!empty($_POST['idEdit'])) {
    $id = $_POST['idEdit'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($_POST['save']) && ! empty($_POST['title']) && ! empty($_POST['description']) && ! empty($_POST['price']) && ! empty($_POST['file'])) {
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = strtolower(end(explode('.', $fileName)));
        $productName =  strtolower(explode('.', $fileName)[0]);
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 900000) {
                    $fileNameNew = $productName . "." . $fileExt;
                    echo $fileNameNew;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    header("Location: index.php?upload succes{$fileNameNew}!");
                } else {
                    $err = 'File is too big!';
                }
            } else {
                $err = 'There was an error uploading the file!';
            }
        } else {
            $err = 'Wrong file type!';
        }
    } elseif (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['price']) ||  empty($_POST['file'])) {
        $err = 'All fields are required!';
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
    <form class="center" action="product.php" method="post" enctype="multipart/form-data">
        <input class="mywidth" type="text" name="title" placeholder="<?= translate('Title')?>">
        <br>
        <input class="mywidth" type="text" name="description" placeholder="<?= translate('Description')?>">
        <br>
        <input class="mywidth" type="number" step="0.001" name="price" placeholder="<?= translate('Price')?>">
        <br>
        <input type="file" name="file" >
        <input type="submit" value="Save" name="save">
        <br>
        <span class="error"><?php echo $err;?></span>
    </form>
    <br>
    
</body>
</html>