<?php


 $get_id = $_SETTING["SEOpermalinkData"]["postId"];
 // Array ( [id] => 5 [postType] => category [postId] => 489 [url] => /loes-te/ [templateFile] => category.tpl [SEOtitle] => Løs til til dig der selv vil bestemme [SEOkeywords] => [SEOdescription] => [SEOnoIndex] => 0 [smartyCache] => 1 )
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
   // $smarty->assign("categoryBreadcrumbs", $data_category);
?>
