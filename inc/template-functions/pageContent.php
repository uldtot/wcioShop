<?php
$pageData = array();
// Load data
$pageId = $_SETTING["seoArray"]["postId"];
$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}pages WHERE id = :id LIMIT 1");
$stmt->execute(array(
    "id" => $pageId,
));
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Add default data. 
$content = $data['content'];

$smarty->assign("pageContent", $content);
