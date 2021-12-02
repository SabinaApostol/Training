<?php

require_once 'common.php';

$err = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['title'])) {
        $err['title'] = translate('Title is required!');
    }
    if (empty($_POST['description'])) {
        $err['description'] = translate('Description is required!');
    }

    if (empty($_POST['rating'])) {
        $err['Rating'] = translate('Rating is required!');
    } elseif (! is_numeric($_POST['rating']) && $_POST['rating'] > 5 || $_POST['rating'] < 1) {
        $err['invalid_rating'] = translate('Rating is not valid!');
    }

    if (empty($err)) {
        $values = [
            'product_id' => $_GET['id'],
            'title' => strip_tags($_POST['title']),
            'description' => strip_tags($_POST['description']),
            'rating' => $_POST['rating']
        ];
        $stmt = $conn->prepare('INSERT INTO reviews (product_id, title, description, rating, approved) VALUES (?, ?, ?, ?, 0)');
        $stmt->execute(array_values($values));
        header('Location: reviews.php?added=1');
        exit;
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
    <h1 class="center"><?= translate('Add review') ?></h1>
    <form class="center" method="post" action="addReview.php?id=<?= $_GET['id'] ?>">
        <input type="text" name="title" placeholder="<?= translate('Title') ?>" size="40" value="<?= $_POST['title'] ?? NULL ?>">
        <?php if (isset($err['title'])) :?>
            <br>
            <span class="error"><?= $err['title'] ?></span>
        <?php endif; ?>
        <br>
        <textarea name="description" placeholder="<?= translate('Description') ?>" cols="40" rows="10"></textarea>
        <?php if (isset($err['description'])) :?>
            <br>
            <span class="error"><?= $err['description'] ?></span>
        <?php endif; ?>
        <br>
        <input type="number" step="0.1" name="rating"  max="5.0" min="1.0" placeholder="<?= translate('Rating') ?>" value="<?= $_POST['rating'] ?? NULL ?>">
        <?php if (isset($err['rating'])) :?>
            <br>
            <span class="error"><?= $err['rating'] ?></span>
        <?php endif; ?>
        <br>
        <?php if (isset($err['invalid_rating'])) :?>
            <br>
            <span class="error"><?= $err['invalid_rating'] ?></span>
        <?php endif; ?>
        <button><?= translate('Add review') ?></button>
        <br>
    </form>
</body>
</html>