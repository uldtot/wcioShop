<?php


$wcioShopAdminPages = array();

$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}pages ORDER BY id DESC");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        	// Getting permlink data
        	$permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'page' AND postId = :id LIMIT 1");
        	$result = $permalinkStmt->execute(array(
        		"id" => $data['id'],
        	));
        	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

            $wcioShopAdminPages[] = array(
        	  'id' => $data['id'],
        	  'name' => $data['name'],
        	  'url' => $permalinkData["url"] ?? "",
            );

  }

$smarty->assign("wcioShopAdminPages", $wcioShopAdminPages);
?>
