<?php

$get_id = $_SETTING["SEOpermalinkData"]["postId"];

// Get product data
$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}products WHERE id = :get_id AND active = 1");
$stmt->execute([
    ":get_id" => $get_id
]);
$wcioDisplayProduct = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$wcioDisplayProduct) {
    // Produkt findes ikke
    header('Location: /404');
    return;
}

// Get permalink
$permalinkStmt = $dbh->prepare("
    SELECT * 
    FROM {$dbprefix}permalinks 
    WHERE postType = 'product' 
      AND postId = :id 
    LIMIT 1
");
$permalinkStmt->execute([
    "id" => $wcioDisplayProduct['id'],
]);
$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

// Get featured image
$attachmentStmt = $dbh->prepare("
    SELECT * 
    FROM {$dbprefix}attachments 
    WHERE attachmentType = 'primary' 
      AND attachmentPostId = :id 
    LIMIT 1
");
$attachmentStmt->execute([
    "id" => $wcioDisplayProduct['id'],
]);
$attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);

if (!$attachmentData["attachmentValue"] || !file_exists(dirname(__FILE__) . "/../../uploads/" . $attachmentData["attachmentValue"])) {
    $image = "noimage.png";
} else {
    $image = $attachmentData["attachmentValue"];
}

// Get price data
$priceStmt = $dbh->prepare("
    SELECT columnName, columnValue 
    FROM {$dbprefix}productmeta 
    WHERE productId = :id 
      AND (columnName LIKE '%salePrice_%' OR columnName LIKE '%price_%')
");
$priceStmt->execute([
    "id" => $wcioDisplayProduct['id'],
]);
$priceData = $priceStmt->fetchAll(PDO::FETCH_ASSOC);

$prices = [];
foreach ($priceData as $row) {
    $prices[$row['columnName']] = $row['columnValue'] + 0; // Cast til int/float
}

// Get other meta data
$otherStmt = $dbh->prepare("
    SELECT * 
    FROM {$dbprefix}productmeta 
    WHERE productId = :id
");
$otherStmt->execute([
    "id" => $wcioDisplayProduct['id'],
]);
$otherData = $otherStmt->fetchAll(PDO::FETCH_ASSOC);

$meta = [];
foreach ($otherData as $row) {
    $meta[$row['columnName']] = $row['columnValue'];
}

// Merge all into main product array
$wcioDisplayProduct['url'] = $permalinkData['url'];
$wcioDisplayProduct['image'] = $image;
$wcioDisplayProduct['price'] = $prices;
$wcioDisplayProduct['discount'] = $meta['discount'] ?? null;
$wcioDisplayProduct['excerpt'] = $meta['excerpt'] ?? null;
$wcioDisplayProduct['stock'] = $meta['stock'] ?? null;
$wcioDisplayProduct['meta'] = $meta ?? null;

$smarty->assign("wcioDisplayProduct", $wcioDisplayProduct);
