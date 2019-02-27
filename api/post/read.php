<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Post.php';

  // Databaskoplingen
  $database = new Database();
  $db = $database->connect();

  // initera 
  $post = new Post($db);

  // Blog post query
  $result = $post->read();
  //Rad räknare
  $num = $result->rowCount();

  // Check post
  if($num > 0) {
    // Post array
    $posts_arr = array();
     

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $post_item = array(
        'id' => $id,
        'name' => $name,
        'price' =>$price,
        // 'hsprice' =>$hsprice,
        // 'webprice' =>$webprice,
        // 'SKU' =>$SKU,
        // 'shortdesc' =>$shortdesc,
        // 'longdesc' =>$longdesc,
        // // 'match' =>$match,
        // 'brand_id' =>$brand_id,
        // 'published_kallbergs' =>$published_kallbergs,
        // 'published_hs' =>$published_hs,
        // 'hs_ordinariepris' =>$hs_ordinariepris,
        // 'promotionFlag' => $promotionFlag,
        // 'prisgaranti' => $prisgaranti,
        // 'featured' => $featured,
        // 'net_price' => $net_price,
        // 'net_shipping_price' => $net_shipping_price,
        // 'parent_id' => $parent_id,
        // 'inherit_parent_price' => $inherit_parent_price,
        // 'have_variants' => $have_variants,
        // 'promotionFlag_expire_at'
      );

      // Tryck data
      array_push($posts_arr, $post_item);

    }

    // Formatera till JSON
    echo json_encode($posts_arr);

  } else {
    // Om det inte skickas
    echo json_encode(
      array('message' => 'No Posts Found')
    );
  }
