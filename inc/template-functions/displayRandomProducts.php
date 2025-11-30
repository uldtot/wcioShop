<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/uldtot/wcioShop
 */

$displayRandomProducts = array();
$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}products WHERE active=1 ORDER BY rand() LIMIT 8");
$result = $stmt->execute();

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
{

	// Getting permlink data
	$permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
	$result = $permalinkStmt->execute(array(
		"id" => $data['id'],
	));
	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

	// Getting featured image
	$attachmentStmt = $dbh->prepare("SELECT * FROM {$dbprefix}attachments WHERE attachmentType = 'primary' AND attachmentPostId = :id LIMIT 1");
	$result = $attachmentStmt->execute(array(
		"id" => $data['id'],
	));
	$attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);

	if(!$attachmentData["attachmentValue"] || !file_exists(dirname(__FILE__)."/../../uploads/".$attachmentData["attachmentValue"]."")) {
		$image = "noimage.png";
	} else {
		$image = $attachmentData["attachmentValue"];
	}

    $displayRandomProducts[] = array(
	  'prdid' => $data['id'],
	  'name' => $data['name'],
	  'price' => $data['price'] ?? 0,
	  'image' => $image,
	  'discount' => $data['discount'],
	  'shorttext' => $data['shorttext'],
	  'stock' => $data['stock'],
	  'url' => $permalinkData["url"],
    );
}

$smarty->assign("displayRandomProducts", $displayRandomProducts);
