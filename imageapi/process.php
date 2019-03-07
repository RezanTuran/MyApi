
<?php
 
    require_once("connection.php");
    if(isset($_POST['upload']))
    {
        $Image = $_FILES['image']['name'];
        $Type = $_FILES['image']['type'];
        $Temp = $_FILES['image']['tmp_name'];
        $Size = $_FILES['image']['size'];

        $ImageExt = explode('.',$Image);
        $AllowExt = strtolower(end($ImageExt));
        $Allow = array('jpg','jpeg','png');
        $Target = "img/".$Image;

        if(in_array($AllowExt,$Allow))
        {
            if($Size<1000000)
            {
                $Query = " INSERT INTO image (image) VALUES ('$Image') ";
                mysqli_query($con,$Query);
                //move_uploaded_file($Temp,$Target);
            }
            else
            {
                echo " Bilder är för stor";
            }
        }
        else
        {
            echo " Ladda annan typ av bild ";
        }



        print_r($Image);

    }
    else
    {
        header("location: index.php");
    }
?>
