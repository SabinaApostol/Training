<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h1 {
            text-align: center;
            font-size: 50pt;
        }
        table, th, td {
            border: 1px solid #000000;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
        img {
            height: 30px;
            width: 30px;
        }
    </style>
</head>
<body>
    <h1>Your cart</h1>
    <table class="center">
        <tr>
            <th></th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Remove from cart</th>
        </tr>
        <?php
            include 'config.php';
            $conn = require 'common.php';
            $stmt = $conn->prepare("SELECT id, title, description, price FROM products;");
            $stmt->execute();

            $products = $stmt->fetchALL(PDO::FETCH_CLASS);
            var_dump($_SESSION)
        ?>
        <?php foreach($_SESSION['productsInCart'] as $product): ?>
            <tr> 
                <td>
                    <img src='./book.jpg'/> 
                </td>
                <td>
                    <?php echo $product->title;?>
                </td>
                <td>
                    <?php echo $product->description;?>
                </td>
                <td>
                    <?php echo $product->price;?>
                </td>
                <td>
                    <a href="<?php removeFromCart($product); ?>">Remove</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a  href="index.php">Go to index</a>
    </div>
    <?php
        function removeFromCart($product) {// var_dump($product);
        }
    ?>
</body>
</html>
