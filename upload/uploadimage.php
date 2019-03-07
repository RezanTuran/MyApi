<?php

define("SERVER","localhost");
define("USER","root");
define("PASSWORD","root");
define("DB", "shopwarenew");

//Define database connection
$mysql = new mysqli(SERVER,USER,PASSWORD,DB);

$respone = array(); 

if($mysql -> connect_error){
    $respone["MESSAGE"] = "ERROR IN SERVER";
    $respone["STATUS"] = 500;
}else{
    if(is_uploaded_file($_FILES["product_image"]["tpm_name"]) && @$_POST["product_name"]){
        $tpm_file = $_FILES["product_image"]["tmp_name"]; // get file from client
        $img_name = $_FILES["product_image"]["name"]; // get file name
        $upload_dir = "./images/". $img_name; // folder for upload image

        $sql = "INSERT INTO s_articles_img";

        if(move_uploaded_file($tmp_file, $upload_dir) && $mysql->query($sql)){
            $respone["MESSAGE"] = "UPLOAD SUCCED";
            $respone["STATUS"] = 200;
        }else{
            $respone["MESSAGE"] = "UPLOAD FARILED";
            $respone["STATUS"] = 404;
        }

    }else{
        $respone["MESSAGE"] = "INVALID REQUEST";
        $respone["STATUS"] = 400;

    }
}
    echo json_encode($respone);

?>