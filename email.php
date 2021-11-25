<?php

$name = $_POST['name'];
$email = $_POST['email'];
$comments = '';

$products = $_POST['products'];
// $products = unserialize($products);
var_dump($products);
die;

// if (! empty($_SESSION['ids'])) {
//     $idValues = createArrayToBind($_SESSION['ids']);
//     $stmt = $conn->prepare('SELECT * FROM products WHERE id IN(' . $idValues . ')');
//     $products = execAndFetch($stmt, array_values($_SESSION['ids']));

// } else {
//     $products = [];
// }

if (! empty($_POST['comment'])) {
    $comments = $_POST['comment'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <table>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        <?php foreach ($products as $product) : ?>
        <tr>
            <td>
                <a href="<?= "http://localhost:4000/" . $product->img ?>">
                    <img src="<?= "http://localhost:4000/" . $product->img ?>" alt="<?= $product->title?>" />
                </a>
            </td>
            <td><?= $product->title ?></td>
            <td><?= $product->description ?></td>
            <td><?= $product->price ?></td>
        </tr>
        <?php endforeach ?>
    </table>
    <br><br>
    <p>Name: <?= $name ?></p>
    <p>Email: <?= $email ?></p>
    <p>Comments: <?= $comments ?></p>
</body>
</html>