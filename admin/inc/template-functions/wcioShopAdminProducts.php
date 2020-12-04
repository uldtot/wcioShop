<?php

$wcioShopAdminProducts = array();

$stmt = $dbh->prepare("SELECT * FROM wcio_se_products ORDER BY active,id DESC");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        	// Getting permlink data
        	$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
        	$result = $permalinkStmt->execute(array(
        		"id" => $data['id'],
        	));
        	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

        	// Getting featured image
        	$attachmentStmt = $dbh->prepare("SELECT * FROM wcio_se_attachments WHERE attachmentType = 'productFeaturedImage' AND attachmentPostId = :id LIMIT 1");
        	$result = $attachmentStmt->execute(array(
        		"id" => $data['id'],
        	));
        	$attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);

        	if(!file_exists(dirname(__FILE__)."../../uploads/".$attachmentData["attachmentValue"]."")) {
        		$image = "noimage.png";
        	} else {
        		$iamge = $attachmentData["attachmentValue"];
        	}

            $wcioShopAdminProducts[] = array(
        	  'prdid' => $data['id'],
        	  'active' => $data['active'],
        	  'name' => $data['name'],
        	  'partno' => $data['partno'],
        	  'price' => $data['price'],
        	  'image' => $image,
        	  'discount' => $data['discount'],
        	  'shorttext' => $data['shorttext'],
        	  'stock' => $data['stock'],
        	  'url' => $permalinkData["url"],
            );

  }

$smarty->assign("wcioShopAdminProducts", $wcioShopAdminProducts);
?>
