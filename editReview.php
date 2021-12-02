<?php

require_once 'common.php';

$reviews = [];

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? AND approved = 0");
    $stmt->execute([$_GET['id']]);
    $reviews = $stmt->fetchALL(PDO::FETCH_CLASS);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($_POST['delete']) && $_POST['delete'] == 'delete' && ! empty($_POST['id'])) {
        $stmt = $conn->prepare('DELETE FROM reviews WHERE id = ?');
        $stmt->execute([$_POST['id']]);
        header("Location: editReview.php?id={$_GET['id']}");
        exit;
    }
    if (! empty($_POST['approve']) && $_POST['approve'] == 'approve' && ! empty($_POST['id'])) {
        $stmt = $conn->prepare("UPDATE reviews SET approved = 1 WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header("Location: editReview.php?id={$_GET['id']}");
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
    <?php if (! empty($reviews)) : ?>
        <h1><?= translate('Reviews') ?></h1>
        <table class="center">
            <tr>
                <th><?= translate('Title') ?></th>
                <th><?= translate('Description') ?></th>
                <th><?= translate('Rating') ?></th>
                <th><?= translate('Approve') ?></th>
                <th><?= translate('Delete') ?></th>
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
                    <td>
                        <form action="editReview.php?id=<?= $_GET['id'] ?>" method="post">
                            <input name="id" value="<?= $review->id ?>" type="hidden">
                            <button name="approve" value="approve"><?= translate('Approve') ?></button>
                        </form> 
                    </td>
                    <td>
                        <form action="editReview.php?id=<?= $_GET['id'] ?>" method="post">   
                            <input name="id" value="<?= $review->id ?>" type="hidden">
                            <button name="delete" value="delete"><?= translate('Delete') ?></button>
                        </form> 
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p><?= translate('There are not any new reviews') ?></p>
    <?php endif; ?>
</body>
</html>