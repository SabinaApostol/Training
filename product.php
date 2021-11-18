<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
 include 'index.php';
 var_dump($products);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit']) {
        $file = $_FILES['file'];
        echo "AAAA";
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = strtolower(end(explode('.', $fileName)));
        $productName =  strtolower(explode('.', $fileName)[0]);
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowed)){

            if ($fileError === 0){
                if ($fileSize < 900000){
                    $fileNameNew = $productName . "." . $fileExt;
                    echo $fileNameNew;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    header("Location: index.php?upload succes{$fileNameNew}!");
                }
                else {
                    echo "File is too big!";
                }
            }
            else {
                echo 'TYhere was an error uploading the file!';
            }

        } else {
            echo 'Wrong file type!';
        }
    }}
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="file" id="file">
        <input type="submit" value="Upload Image" name="submit">
    </form>
   
</body>
</html>