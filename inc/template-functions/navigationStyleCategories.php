<?php


$output = array();
$stmt = $dbh->prepare("SELECT * FROM wcio_se_categories ORDER BY sortorder");
$result = $stmt->execute();

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
{

// Getting permlink data
$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'category' AND postId = :id LIMIT 1");
$result = $permalinkStmt->execute(array(
	"id" => $data['id'],
));
$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

// Making data array
    $output[] = array(
        'id' => $data['id'],
        'name' => $data['name'],
        'isparent' => $data['isparent'],
        'gotparent' => $data['gotparent'],
        'productcount' => $data['productcount'],
        'sortorder' => $data['sortorder'],
        'url' => $permalinkData["url"],
    );
}
$smarty->assign("navigationStyleCategories", $output);


?>
