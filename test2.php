

<?php
function doRequest($endpoint, $data)
{
    $ch = curl_init();
    $user = 'admin';
    $pass = '9aHfsQm2ubBggtmyTyoEqAmEi9VEfrT9fIAgP8M5';
    curl_setopt($ch, CURLOPT_URL, "http://{$user}:{$pass}@newshopware/api/{$endpoint}");
    curl_setopt($ch, CURLOPT_POST, 1);

    $postdata = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $out = curl_exec($ch);
    $errors = curl_error($ch);
    curl_close($ch);

    var_dump($errors);

    return $out;
}
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');





$mysqli = new mysqli("localhost", "root", "root", "hemmashoppen");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//--------------------------------------------------------------------------------------------------------//

//---- ### Produkter ------### //

 $query = "SELECT * FROM jos_shopper_products limit 10";


if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_object()) {
        // https://developers.shopware.com/developers-guide/rest-api/api-resource-article/#post-(create)
        // Produkt från hemmashoppen $row
        // Skapa produkt och varianter i shopware.
     
        $data = [
            'name' => $row->name,
            'taxId' => 1,
            'active' =>1,
            'description' =>$row->shortdesc,
            'descriptionLong' => $row->longdesc,
            'metaTitle'=> $row->name,
            'added' =>$row->promotionFlag_expire_at,
            'supplierId' =>$row->brand_id,
            //  'categories' => array(
            //      array('id' =>3),
         //), 

            'mainDetail' => [
                'number' =>	$row->id,
                'supplierNumber' => "22", //$row->brand_id, // Produkt nummer
                'inStock' => 5,
                'active' =>1,
                'prices' => array(
                    array(
                        'price' => $row->hsprice,
                    )
                    
                    ),
                ],
            ];

        var_dump(doRequest("articles", $data));
    }
    
    $result->close();
}

// ---- ### Kategorier ------### //

// $query = "SELECT * FROM jos_shopper_productcategories limit 5";
// if ($result = $mysqli->query($query)) {

    
//     while ($row = $result->fetch_object()) {
//         $data = [
//             'parentId' => 1,
//             'name'=>$row->name,
//             'metaDescription'=>$row->description,
//             'active'=>1,
//             'position'=>0,
            
//         ];

//         var_dump(doRequest("categories", $data));
//     }
    
//     $result->close();
// }

// //--------------------------------------------------------------------------------------------------------//

//---- ### Märke ------### //

$query = "SELECT * FROM jos_shopper_brands limit 10 ";
if ($result = $mysqli->query($query)) {

    
    while ($row = $result->fetch_object()) {
        $data = [
            'name'=>$row->name,
            
        ];

        var_dump(doRequest("manufacturers", $data));
    }
    
    $result->close();
}

//--------------------------------------------------------------------------------------------------------//

//---- ### Bilder ------### //

// $query = "SELECT * FROM jos_shopper_products_images limit 5";
// if ($result = $mysqli->query($query)) {

    
//     while ($row = $result->fetch_object()) {
//         $data = [
//             'album'=>-1,
//             'name'=>$row->order,
//             'file'=>'http://restapi/bilder/upload/productimage.'.$row->id.'.'.$row->extension,
//             'description'=>$row->visible_detail,
//         ];

//         var_dump(doRequest("media", $data));
//     }
    
//     $result->close();
// }

//--------------------------------------------------------------------------------------------------------//




