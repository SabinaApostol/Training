<?php

require_once 'common.php';

$reviews = [];
$id = 0;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} 
if ($id !=0) {
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE idProduct = $id AND approved = 0");
    $stmt->execute();
    $reviews = $stmt->fetchALL(PDO::FETCH_CLASS);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($_POST['deleteR']) && $_POST['deleteR'] == 'deleteR' && ! empty($_POST['idDeleteR']) ) {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = {$_POST['idDeleteR']}");
        $stmt->execute();
        header("Location: editReview.php?id={$_POST['idProdD']}");
        exit;
    }
    if (! empty($_POST['approve']) && $_POST['approve'] == 'approve' && ! empty($_POST['idProdE']) && ! empty($_POST['idApprove'])) {
        $stmt = $conn->prepare("UPDATE reviews SET approved = 1 WHERE idProduct = {$_POST['idProdE']} AND id = {$_POST['idApprove']}");
        $stmt->execute();
        header("Location: editReview.php?id={$_POST['idProdE']}");
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
        .center{
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
                        <form action="editReview.php" method="post">
                            <input type="number" name="idProdE" value="<?= isset($_POST['idProdE']) ? $_POST['idProdE'] : $_GET['id'] ?>">      
                            <input name="idApprove" value="<?= $review->id ?>" type="hidden">
                            <button name="approve" value="approve"><?= translate('Approve') ?></button>
                        </form> 
                    </td>
                    <td>
                        <form action="editReview.php" method="post">
                            <input type="number" name="idProdD" value="<?= isset($_POST['idProdD']) ? $_POST['idProdD'] : $_GET['id'] ?>">    
                            <input name="idDeleteR" value="<?= $review->id ?>" type="hidden">
                            <button name="deleteR" value="deleteR"><?= translate('Delete') ?></button>
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