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
    <script type="text/javascript">
        // $(document).ready(function () {
        //     createCookie("height", $(window).height(), "1");
        //     });
        // function createCookie(name, value, days) {
        //     document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
        // }
    //    function myAjax(value_myfunction) {
    //         console.log(value_myfunction);
    //         var xmlhttp = new XMLHttpRequest();
    //         xmlhttp.onreadystatechange = function() {
    //         if (this.readyState == 4 && this.status == 200) {
    //             document.getElementById("results").innerHTML += this.responseText; 
    //             // note '+=', adds result to the existing paragraph, remove the '+' to replace.
    //         }
    //         };
    //         console.log(window.location + "?sendValue=" + value_myfunction);
    //         xmlhttp.open("GET", window.location + "?sendValue=" + value_myfunction, true);
    //     }   
    </script>
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
            $_SESSION['productsInCart'] = [];
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
                    <a href="#" onclick="myAjax(<?php echo $product->id; ?>)">Add</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p id="results"></p>
    <br>
    <div style="text-align: center;">
        <a  href="cart.php">Go to cart</a>
    </div>
    <?php 
       echo $_COOKIE["height"];
    ?>
</body>
</html>