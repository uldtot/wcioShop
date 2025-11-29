<?php


$output = array();
$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}categories ORDER BY name ASC");
$result = $stmt->execute();

while ($data = $stmt->fetch(PDO::FETCH_ASSOC))
{

// Getting permlink data
$permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'category' AND postId = :id LIMIT 1");
$result = $permalinkStmt->execute(array(
	"id" => $data['id'],
));
$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

// Making data array
    $output[] = array(
        'id' => $data['id'],
        'name' => $data['name'],
        'url' => $permalinkData["url"],
    );
}
$smarty->assign("navigationStyleCategories", $output);


?>
