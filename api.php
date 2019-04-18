

<?php
function doRequest($endpoint, $data)
{
    $ch = curl_init();
    $user = 'admin';
    $pass = 'rT4sXTZT68hIoD3zefZmFzEEIVQXJv5KPqcmrufv';
    curl_setopt($ch, CURLOPT_URL, "http://{$user}:{$pass}@shopwarenew/api/{$endpoint}");
    curl_setopt($ch, CURLOPT_POST, 1);

    $postdata = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $out = curl_exec($ch);
    $errors = curl_error($ch);
    curl_close($ch);

    echo $postdata;

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
 $query = "SELECT * FROM  jos_shopper_products 
 WHERE jos_shopper_products.parent_id = 0 limit 100";

//-- ### Images ----### // 
$allImages = [];
$imageQuery = "SELECT * FROM jos_shopper_products_images ORDER BY id DESC";
if ($result = $mysqli->query($imageQuery)) {
    while ($row = $result->fetch_object()) {
        $pid = $row->product_id;
        if(!isset($allImages[$pid])) {
            $allImages[$pid] = [];
        }
        $allImages[$row->product_id][] = $row;   
    }
}
//--- ### PDF Filer ----### //
$allFiles = [];
$fileQuery = "SELECT * FROM jos_shopper_products_files ORDER BY id DESC";
if ($result = $mysqli->query($fileQuery)) {
    while ($row = $result->fetch_object()) {
        $pid = $row->product_id;
        if(!isset($allFiles[$pid])) {
            $allFiles[$pid] = [];
        }
        $allFiles[$row->product_id][] = $row;   
    }
}
// --- ### Varianter --- ### //
$allVariants = [];
$variantQuery = "SELECT * FROM jos_shopper_products WHERE parent_id > 0 ";
if ($result = $mysqli->query($variantQuery)) {
    while ($row = $result->fetch_object()) {
         if(!isset($allVariants[$row->id])) {
            $allVariants[$row->id] = [];
         }
            $allVariants[$row->id] = [
                'isMain' => true,
                'number' => 1,
                'inStock' => 15,
                'additionaltext' => $row->name,
                'prices' => array(
                    array(
                        'price' => $row->hsprice,
                    )
                ),    
            ];
        }
}

if ($result = $mysqli->query($query)) {
    /* fetch object array */
    while ($row = $result->fetch_object()) {
        // https://developers.shopware.com/developers-guide/rest-api/api-resource-article/#post-(create)
        // Produkt från hemmashoppen $row
        // Skapa produkt och varianter i shopware.
        $images = [];
        if(isset($allImages[$row->id])) {
            foreach($allImages[$row->id] as $idx => $img) {
                $images[] = [
                    'link' => 'http://restapi/bilder/upload/productimage.'.$img->id.'.'.$img->extension,
                    'main' => ($idx == 0) ? 1 : 0,
                    'position' => $idx,
                ];
            }
        }
        $downloads = [];
        if(isset($allFiles[$row->id])) {
            foreach($allFiles[$row->id] as $idx => $dock) {
                $downloads[] = [
                    'link' => 'http://restapi/bilder/filer/productfile.'.$dock->id.'.'.$dock->extension,
                    'main' => ($idx == 0) ? 1 : 0,
                    'position' => $idx,
                ];
            }
        }
        
        $data = [
            'name' => $row->name,
            'taxId' => 1,
            'active' =>true,
            'description' =>$row->shortdesc,
            'descriptionLong' => $row->longdesc,
            'metaTitle'=> $row->name,
            'added' =>$row->promotionFlag_expire_at,
            'supplierId' => $row->brand_id,
            'categories' => array( 
                array('id' =>3),
            ),
            'mainDetail' => [
                'number' =>	$row->id,
                'supplierNumber' => "22",
                'inStock' => 5,
                'active' =>true,
                'prices' => array(
                    array(
                        'price' => $row->hsprice,
                    )
                    ),
                ],
            'images' => $images,
            'downloads' => $downloads,
            'variants' => $allVariants[$row->id],
            
            
                        
            ];
            
        doRequest("articles", $data);
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




