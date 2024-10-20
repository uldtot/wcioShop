<?php
$get_id = $_SETTING["SEOpermalinkData"]["postId"];

$SeCategory = "";
$SeProducts = "";
$SeCategoryCount = "0";
$SeProductsCount = "0";

$stmt = $dbh->prepare("SELECT * FROM wcio_se_categories WHERE id=:get_id");
$result = $stmt->execute(array(
    ":get_id" => $get_id
));
$SeCategoryCount = $stmt->rowCount();

$data_category = $stmt->fetch(PDO::FETCH_ASSOC);

$smarty->assign("category", $data_category);
$smarty->assign("categoryDescription", explode("<--split-->", $data_category["description"]));
