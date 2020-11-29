<?php
$displayRandomProducts = array();

$q = $_GET["q"];

$stmt = $dbh->prepare("SELECT * FROM wcio_se_products WHERE name LIKE :name AND active='1' LIMIT 8");
$result = $stmt->execute(array(
      ":name" => "%{$q}%",
));

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
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

    $displayRandomProducts[] = array(
	  'prdid' => $data['id'],
	  'name' => $data['name'],
	  'price' => $data['price'],
	  'image' => $image,
	  'discount' => $data['discount'],
	  'shorttext' => $data['shorttext'],
	  'stock' => $data['stock'],
	  'url' => $permalinkData["url"],
    );
}

    // We just need an output to load this in template. Settings will be added later
    $smarty->assign("wcioShopLiveSearch", $displayRandomProducts);

?>
