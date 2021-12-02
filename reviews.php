<?php

require_once 'common.php';

$reviews = [];

if (isset($_GET['added'])) {

}

if (isset($_GET['id'])) {
    $stmt = $conn->prepare('SELECT * FROM reviews WHERE product_id = ? AND approved != 0');
    $stmt->execute([$_GET['id']]);
    $reviews = $stmt->fetchALL(PDO::FETCH_CLASS);
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
        h1 {
            text-align: center;
            font-size: 50pt;
        }
        table, th, td {
            border: 1px solid #000000;
            text-align: center;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <?php if (isset($_GET['id'])) : ?>
        <a href="addReview.php?id=<?= $_GET['id'] ?>"><p style="text-align: center;"><?= translate('Add review') ?></p></a>
    <?php endif; ?>
    <?php if (! empty($reviews)) :?>
        <h1><?= translate('Reviews') ?></h1>
        <table class="center">
            <tr>
                <th><?= translate('Title') ?></th>
                <th><?= translate('Description') ?></th>
                <th><?= translate('Rating') ?></th>
            </tr>
            <?php foreach ($reviews as $review): ?>
                <tr> 
                    <td>
                        <?= $review->title ?>
                    </td>
                    <td>
                        <?= $review->description ?>
                    </td>
                    <td>
                        <?= $review->rating ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (! isset($_GET['added'])) : ?>
        <p style="text-align: center;"><?= translate('No reviews on this product yet') ?></p>
    <?php else : ?>
        <p style="text-align: center;"><?= translate('Thank you for the review') ?></p>
        <a href="index.php"><p style="text-align: center;"><?= translate('Go back to see the products') ?></p></a>
    <?php endif; ?>
</body>
</html>