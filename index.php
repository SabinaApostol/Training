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
    </style>
</head>
<body>
    <h1>List of products</h1>
    <table class="center">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "training";
        $conn = new PDO("mysql:host={$servername};dbname={$dbname}", $username, $password);
        $stmt = $conn->prepare("SELECT title, description, price FROM products;");
        $stmt->execute();

        $products = $stmt->fetchALL(PDO::FETCH_CLASS);
        ?>
        <?php foreach($products as $product): ?>
            <tr>
                <td>
                    <?php echo $product->title;?>
                </td>
                <td>
                    <?php echo $product->description;?>
                </td>
                <td>
                    <?php echo $product->price;?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>