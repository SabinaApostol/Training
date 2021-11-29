<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translate('Document') ?></title>
</head>
<body>
    <table>
        <tr>
            <th><?= translate('Image') ?></th>
            <th><?= translate('Title') ?></th>
            <th><?= translate('Description') ?></th>
            <th><?= translate('Price') ?></th>
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
    <p><?= translate('Name: ') ?><?= $_POST['name'] ?></p>
    <p><?= translate('Email: ') ?><?= $_POST['email'] ?></p>
    <p><?= translate('Comments: ') ?><?= $_POST['comment'] ?? NULL ?></p>
</body>
</html>