<?php


$wcioShopAdminCategories = array();

$stmt = $dbh->prepare("SELECT * FROM wcio_se_categories ORDER BY id DESC");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        	// Getting permlink data
        	$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'category' AND postId = :id LIMIT 1");
        	$result = $permalinkStmt->execute(array(
        		"id" => $data['id'],
        	));
        	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

            $wcioShopAdminCategories[] = array(
        	  'prdid' => $data['id'],
        	  'name' => $data['name'],
        	  'url' => $permalinkData["url"] ?? "",
            );

  }

$smarty->assign("wcioShopAdminCategories", $wcioShopAdminCategories);
?>
