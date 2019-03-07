<?php 

$mysqli = new mysqli("localhost", "root", "root", "aplus");


if(isset($_POST['submit'])){
    $f1 = $_FILES['file1']['name'];
    $f2 = $_FILES['file2']['name'];

    $sql = "INSERT INTO images VALUES ('NULL','$f1','$f2')";
    if(mysqli_query($sql))
    {
        move_uploaded_file($_FILES['file1']['tmp_name'], "Pictures/$f1" );
        move_uploaded_file($_FILES['file2']['tmp_name'], "Pictures/$f2" );
        echo "File uploaded Succesfully";
    }
    else
    {
        echo "There is something error";
    }

}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


    <form method="post" enctype="multipart/form-data">

    <h1>Image 1</h1>
    <input type="file" name="file1">
    <h1>Image 2</h1>
    <input type="file" name="file2">
    <input type="submit" name="submit" value="Upload">

    </form>

</body>
</html>



