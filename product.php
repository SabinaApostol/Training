<?php

require_once 'common.php';

$err = $title = $description = $price = $img = '';

if (! empty($_POST['idEdit'])) {
    $id = $_POST['idEdit'];
} else {
    $id = 0;
}

if ($id != 0) {
    $stmt = $conn->prepare('SELECT title, description, price, img FROM products WHERE id = ' . $_POST['idEdit']);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = $product['title'];
    $description = $product['description'];
    $price = $product['price'];
    $img = $product['img'];
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['productId'] == 0) {
        if ((is_uploaded_file($_FILES['file']['tmp_name'])) && (! empty($_POST['title'])) && (! empty($_POST['description'])) && (! empty($_POST['price']))) {
            $file = $_FILES['file'];
            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileError = $_FILES['file']['error'];
            $fileType = $_FILES['file']['type'];

            $tmp = explode('.', $fileName);
            $fileExt = strtolower(end($tmp));

            $productName = strtolower(explode('.', $fileName)[0]);
            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 9000000) {
                        $fileNameNew = time() . '.' . $fileExt;
                        $fileDestination = 'uploads/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);

                        $taValues = [
                            'title' => $_POST['title'],
                            'description' => $_POST['description'],
                            'price' => $_POST['price'],
                            'img' => $fileDestination
                        ];
                        $placeHolders = createArrayToBind($taValues);
                        $stmt = $conn->prepare('INSERT INTO products (title, description, price, img) VALUES (' . $placeHolders . ')');
                        $stmt->execute(array_values($taValues));
                        header('Location: products.php');
                        exit;
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
    } else {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $file = $_FILES['file'];
                $fileName = $_FILES['file']['name'];
                $fileTmpName = $_FILES['file']['tmp_name'];
                $fileSize = $_FILES['file']['size'];
                $fileError = $_FILES['file']['error'];
                $fileType = $_FILES['file']['type'];

                $tmp = explode('.', $fileName);
                $fileExt = strtolower(end($tmp));

                $productName =  strtolower(explode('.', $fileName)[0]);
                $allowed = array('jpg', 'jpeg', 'png');

                if (in_array($fileExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 9000000) {

                            $fileNameNew = time() . '.' . $fileExt;
                            $fileDestination = 'uploads/' . $fileNameNew;
                            move_uploaded_file($fileTmpName, $fileDestination);
                            $img = $fileDestination;
                        
                        } else {
                            $err = 'File is too big!';
                        }
                    } else {
                        $err = 'There was an error uploading the file!';
                    }
                } else {
                    $err = 'Wrong file type!';
                }
            } else {
                $img = $_POST['image'];
            }
        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['price'])) {
            $err = 'All fields are required!';
        } else {
            $taValues = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'img' => $img,
                'id' => $_POST['productId']
            ];
            $stmt = $conn->prepare('UPDATE products SET title=?, description=?, price=?, img=? WHERE id=?');
            $stmt->execute(array_values($taValues));
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
    <form class="center" action="product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="productId" value="<?= $id ?>">
        <input class="mywidth" type="text" name="title" placeholder="<?= translate('Title') ?>" value="<?= $title ?>">
        <br>
        <input class="mywidth" type="text" name="description" placeholder="<?= translate('Description') ?>" value="<?= $description ?>">
        <br>
        <input class="mywidth" type="number" step="0.001" name="price" placeholder="<?= translate('Price') ?>" value="<?= $price ?>">
        <br>
        <input type="hidden" name="image" value="<?= $img ?>">
        <input type="file" name="file">
        <button><?= translate('Save') ?></button>
        <br>
        <span class="error"><?= $err ?></span>
    </form>
</body>
</html>