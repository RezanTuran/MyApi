

<?php
function doRequest($endpoint, $data)
{
    $ch = curl_init();
    $user = 'admin';
    $pass = '75cbUrRd2khGG3EI5KnZOOSIEMPJ2FxA06W5uGJC';
    curl_setopt($ch, CURLOPT_URL, "http://{$user}:{$pass}@shopwarenew/api/{$endpoint}");
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


//---- ### Produkter ------### //

$query = "SELECT * FROM jos_shopper_products INNER JOIN jos_shopper_products_images ON 
jos_shopper_products.id = jos_shopper_products_images.product_id limit 10";


if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_object()) {
        // https://developers.shopware.com/developers-guide/rest-api/api-resource-article/#post-(create)
        // Produkt från hemmashoppen $row
        // Skapa produkt och varianter i shopware.
        
     
        $data = [
            'name' => $row->name,
            'taxId' => 1,
            'active' =>true,
            'description' =>$row->shortdesc,
            'descriptionLong' => $row->longdesc,
            'metaTitle'=> $row->name,
            'added' =>$row->promotionFlag_expire_at,
            'supplierId' => $row->brand_id,
            'categories' => array( //Kategori bara 3
                array('id' =>3),
            ),
            
            'mainDetail' => [
                'number' =>	$row->id,
                'supplierNumber' => "22", //$row->brand_id, // Produkt nummer
                'inStock' => 5,
                'active' =>true,
                'prices' => array(
                    array(
                        'price' => $row->hsprice,
                    )
                    ),
                ],
            'images' => array(
                    array(
                        'link' => 'http://restapi/bilder/upload/productimage.'.$row->id.'.'.$row->extension,
                        'main' => 1,
                        'position' => 1,
                    ),
                ),
                
            // 'downloads' => array(
            //         array(
            //             'link' => 'http://restapi/bilder/filer/productfile.'.$row->id.'.'.pdf,
            //             'main' => 1,
            //             'position' => 1,
            //         ),
            //     ),

            // If parent_id. Om det finns produkter som har samma parent_id som produktens egna id
            'configuratorSet' => array(
                'groups' => array(
                    array(
                        'name' => 'Varianter',
                        'options' => array(
                            // For each parent_id
                            array('name' => 'Salt&Pepparkvarn: Vulcanic (Orangea) '),
                            array('name' => 'Salt&Pepparkvarn: Ocean (Grön/Blåa)'),
                            array('name' => 'Salt&Pepparkvarn: Cerise (röda)'),
                            array('name' => 'Salt&Pepparkvarn: Svarta')
                            // End for each
                        )
                    )
                )
            ),
            // End parent_id

            'variants' => array(
                array(
                    'isMain' => true,
                    'active' => true,
                    'number' => $row->id,
                    'inStock' => 15,
                    'additionaltext' => 'Yellow',
                    'configuratorOptions' => array(
                        array('group' => 'Variants', 'option' => 'Salt&Pepparkvarn: Vulcanic (Orangea)'),
                    ),
                    'prices' => array(
                        array(
                            'price' => $row->hsprice,
                        ),
                    ),
                ),
            )
            
                    
            ];
            
        var_dump(doRequest("articles", $data));
    }
    
    $result->close();
}





//---- ### Kategorier ------### //

// $query = "SELECT * FROM jos_shopper_productcategories";
// if ($result = $mysqli->query($query)) {

    
//     while ($row = $result->fetch_object()) {
//         $data = [
//             'parentId' => 1,
//             'name'=>$row->name,
//             'metaDescription'=>$row->description,
//             'active'=>1,
            
//         ];

//         var_dump(doRequest("categories", $data));
//     }
    
//     $result->close();
// }

//-------------------------------------------------------------------------------------------------------//

//---- ### Märke ------### //

// $query = "SELECT * FROM jos_shopper_brands ";
// if ($result = $mysqli->query($query)) {

    
//     while ($row = $result->fetch_object()) {
//         $data = [
//             'name'=>$row->name,
//             'id'=> $row->id,
            
//         ];

//         var_dump(doRequest("manufacturers", $data));
//     }
    
//     $result->close();
// }

//--------------------------------------------------------------------------------------------------------//

//---- ### Bilder ------### //

// $query = "SELECT * FROM jos_shopper_products_images limit 15";
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




