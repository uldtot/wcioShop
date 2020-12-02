<?php
$liveSearchOrders = array();
$liveSearchProducts = array();

$q = $_GET["q"];

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
	  'url' => $permalinkData["url"],
    );


}

    // We just need an output to load this in template. Settings will be added later
    $smarty->assign("liveSearchOrders", $liveSearchOrders);


// Products
$stmt = $dbh->prepare("SELECT * FROM wcio_se_products WHERE
      id = :q OR
      partno = :q OR
      name LIKE :q OR
      shorttext LIKE :q
      ORDER BY id DESC
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


    $liveSearchProducts[] = array(
	  'id' => $data['id'],
	  'active' => $data['active'],
	  'partno' => $data['partno'],
	  'name' => $data['name'],
	  'shorttext' => $data['shorttext'],
	  'url' => $permalinkData["url"],
    );


}

    // We just need an output to load this in template. Settings will be added later
    $smarty->assign("liveSearchProducts", $liveSearchProducts);

//


?>
