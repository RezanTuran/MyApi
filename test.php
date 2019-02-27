<?php
function doRequest($endpoint, $data)
{
    $ch = curl_init();
    $user = 'admin';
    $pass = 'x0XnWur5G9DClWPc3xa3GrXe7rTBGQlSWDd5dA0m';
    curl_setopt($ch, CURLOPT_URL, "http://{$user}:{$pass}@shopware.test/api/{$endpoint}");
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

/*
 * Exempel hur vi hämtar ut produkter till prisjakt, går ej att använda denna rakt av dock.
 *
    SELECT p.id,p.SKU, b.name as brand_name, p.id, pg.name as productgroup_name, hs.price as price, 
    pi.extension AS image_extension, pi.id AS image_id, pro_sto.type as storage_type, pro_sto.number as storage_number, pro_sto.date as storage_date, pro_sto.storage_id, 
    parenti.extension AS parent_image_extension, parenti.id AS parent_image_id FROM jos_shopper_products AS p
    LEFT OUTER JOIN jos_shopper_brands AS b ON p.brand_id = b.id
    LEFT OUTER JOIN jos_shopper_products_productgroups AS ppg ON ppg.product_id = p.id
    LEFT OUTER JOIN jos_shopper_productgroups AS pg ON pg.id = ppg.productgroup_id
    LEFT OUTER JOIN jos_shopper_products_images AS pi ON pi.product_id = p.id AND detail_page_image = 1
    LEFT OUTER JOIN jos_shopper_products_images AS parenti ON parenti.product_id = p.parent_id AND parenti.detail_page_image = 1
    LEFT OUTER JOIN jos_shopper_products_marketing AS pm ON pm.product_id = p.id

    LEFT OUTER JOIN jos_shopper_marketing AS marketing ON pm.marketing_id = marketing.id
    LEFT OUTER JOIN jos_shopper_products_storage AS pro_sto ON pro_sto.product_id = p.id
    LEFT OUTER JOIN jos_shopper_products AS parentProduct ON parentProduct.id = p.parent_id

    WHERE pm.marketing_id = '4' AND p.have_variants = 0  AND (p.parent_id = 0 OR parentProduct.id IS NOT NULL)
    GROUP BY p.id;
 */



$mysqli = new mysqli("localhost", "root", "root", "hemmashoppen");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 
$query = "SELECT * FROM jos_shopper_products limit 0,5";
$query = "SELECT * FROM jos_shopper_productcategories limit 0,5";


if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_object()) {
        // https://developers.shopware.com/developers-guide/rest-api/api-resource-article/#post-(create)
        // Produkt från hemmashoppen $row
        // Skapa produkt och varianter i shopware.
     
        $data = [
            'name' => $row->name,
            'taxId' => 1,
            'supplierId' => 1,
            'active' =>1,
            'description' =>$row->shortdesc,
            'descriptionLong' => $row->longdesc,
            'metaTitle'=> $row->name,
            'added' =>$row->promotionFlag_expire_at,
            'mainDetail' => [
                'number' =>	$row->id,
                'supplierNumber' => '22',
                'inStock' => 5,
                'prices' => array(
                    array(
                        'price' => $row->hsprice,
                    )
                    
                    ),

            ]
        ];

        var_dump(doRequest("articles", $data));
    }

    /* free result set */
    
    $result->close();
}

