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
    <h1>List of products</h1>
    <table class="center">
        <tr>
            <th></th>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
            <th>Add to cart</th>
        </tr>
        <?php
            include 'config.php';
            $conn = require 'common.php';
            $stmt = $conn->prepare("SELECT id, title, description, price FROM products;");
            $stmt->execute();

            $products = $stmt->fetchALL(PDO::FETCH_CLASS);
        ?>
        <?php foreach($products as $product): ?>
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
                    <a href="#">Add</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <div style="text-align: center;">
        <a  href="cart.php">Go to cart</a>
    </div>
</body>
</html>
