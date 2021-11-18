<?php
    if ($_POST['submit']) {
        $file = $_FILES['file'];
        echo "AAAA";
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = strtolower(end(explode('.', $fileName)));
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowed)){

            if ($fileError === 0){
                if ($fileSize < 900000){
                    $fileNameNew = uniqid('', true) . "." . $fileExt;
                    $fileDestination = 'uploads/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    header("Location: index.php?upload succes!");
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
    }
    ?>