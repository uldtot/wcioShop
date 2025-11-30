<?php


$get_id = $_SETTING["SEOpermalinkData"]["postId"];

// Array ( [id] => 5 [postType] => category [postId] => 489 [url] => /loes-te/ [templateFile] => category.tpl [SEOtitle] => Løs til til dig der selv vil bestemme [SEOkeywords] => [SEOdescription] => [SEOnoIndex] => 0 [smartyCache] => 1 )
//
$stmt = $dbh->prepare("SELECT * FROM wcio_se_products WHERE id=:get_id");
$result = $stmt->execute(array(
      ":get_id" => $get_id
));

$wcioDisplayProduct = $stmt->fetch(PDO::FETCH_ASSOC);

if (!file_exists(dirname(__FILE__) . "../../uploads/" . $wcioDisplayProduct["featuredImage"] . "")) {
      $image = "noimage.png";
} else {
      $image = $wcioDisplayProduct["featuredImage"];
}

$wcioDisplayProduct["featuredImage"] = $image;

$smarty->assign("wcioDisplayProduct", $wcioDisplayProduct);
// $smarty->assign("categoryBreadcrumbs", $wcioDisplayProduct);
