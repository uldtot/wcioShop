<?php
$wcioShopAdminLiveSearch = "1"; // This is just to mkae sure there is something to check if its loaded
$liveSearchSettings = array();
$liveSearchApps = array();
$liveSearchOrders = array();
$liveSearchProducts = array();

$q = $_GET["q"] ?? "";
if(strlen($q) > 3) {
// Orders
$stmt = $dbh->prepare("SELECT * FROM wcio_se_porders WHERE
      id = :q OR
      cart_id = :q OR
      firstname LIKE :q OR
      lastname LIKE :q OR
      adress LIKE :q OR
      email LIKE :q OR
      phone LIKE :q
      ORDER BY id DESC
      LIMIT 6");
$result = $stmt->execute(array(
      ":q" => "%{$q}%",
));

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	// Getting permlink data
	$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'order' AND postId = :id LIMIT 1");
	$result = $permalinkStmt->execute(array(
		"id" => $data['id'],
	));
	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);


    $liveSearchOrders[] = array(
	  'id' => $data['id'],
	  'firstname' => $data['firstname'],
	  'lastname' => $data['lastname'],
	  'url' => $permalinkData["url"] ?? "",
    );


}


// Products
$stmt = $dbh->prepare("SELECT * FROM wcio_se_products WHERE
      id = :q OR
      name LIKE :q
      LIMIT 6");
$result = $stmt->execute(array(
      ":q" => "%{$q}%",
));

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
{
	// Getting permlink data
	$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
	$result = $permalinkStmt->execute(array(
		"id" => $data['id'],
	));
	$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

      $url =  $permalinkData["url"] ?? "";
    $liveSearchProducts[] = array(
	  'id' => $data['id'],
	  'active' => $data['active'],
	  'name' => $data['name'],
	  'url' => $url,
    );


}

} // End if strlen

    $smarty->assign("liveSearchProducts", $liveSearchProducts);
    $smarty->assign("liveSearchOrders", $liveSearchOrders);
    $smarty->assign("wcioShopAdminLiveSearch", $wcioShopAdminLiveSearch);
    $smarty->assign("liveSearchSettings", $liveSearchSettings);
    $smarty->assign("liveSearchApps", $liveSearchApps);

//

?>
