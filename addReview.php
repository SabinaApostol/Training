<?php

require_once 'common.php';

$err = [
    'Title'=>false,
    'Description'=>false,
    'Rating'=>false,
    'Invalid rating'=>false
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['title'])) {
        $err['Title'] = true;
    }
    if (empty($_POST['description'])) {
        $err['Description'] = true;
    }

    if (empty($_POST['rating'])) {
        $err['Rating'] = true;
    } elseif ($_POST['rating'] > 5 || $_POST['rating'] < 1) {
        $err['Invalid rating'] = true;
    }

    if ((! $err['Title']) && (! $err['Description']) && (! $err['Rating']) && (! $err['Invalid rating'])) {
        $taValues = [
            'idProduct' => $_POST['idProd'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'rating' => $_POST['rating']
        ];
        $placeHolders = createArrayToBind($taValues);
        $stmt = $conn->prepare('INSERT INTO reviews (idProduct, title, description, rating, approved) VALUES (' . $placeHolders . ', 0)');
        $stmt->execute(array_values($taValues));
        header('Location: index.php');
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
    <form class="center" method="post" action="addReview.php">
        <input type="hidden" name="idProd" value="<?= isset($_POST['idProd']) ? $_POST['idProd'] : $_GET['id'] ?>">
        <input type="text" name="title" placeholder="<?= translate('Title') ?>" size="40" value="<?= isset($_POST['title']) ? $_POST['title'] : '' ?>">
        <?php if ($err['Title'] != '') :?>
            <br>
            <span class="error"><?= translate('Title is required') ?></span>
        <?php endif; ?>
        <br>
        <textarea name="description" placeholder="<?= translate('Description') ?>" cols="40" rows="10"></textarea>
        <?php if ($err['Description'] != '') :?>
            <br>
            <span class="error"><?= translate('Description is required') ?></span>
        <?php endif; ?>
        <br>
        <input type="number" step="0.1" name="rating"  max="5.0" min="1.0" placeholder="<?= translate('Rating') ?>" value="<?= isset($_POST['rating']) ? $_POST['rating'] : '' ?>">
        <?php if ($err['Rating'] != '') :?>
            <br>
            <span class="error"><?= translate('Rating is required') ?></span>
        <?php endif; ?>
        <br>
        <button><?= translate('Add') ?></button>
        <br>
    </form>
</body>
</html>