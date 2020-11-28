<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

// Load permalink settings for this URL.
$currentUrl = $_SERVER['REQUEST_URI'];

$stmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks LIMIT 1");
$result = $stmt->execute(array(
	"url" => $currentUrl,
));

while($data = $stmt->fetch( PDO::FETCH_ASSOC )) {

	// If we have set a Seo shortname in settings, then add it
	if($_SETTING["storeSeoShortName"] != "" ) {
		$seoTitle = $data["SEOtitle"].$_SETTING["storeSeoShortNameSeperator"].$_SETTING["storeSeoShortName"];
	} else {
		$seoTitle = $data["SEOtitle"];
	}
	// Assign metas
	$smarty->assign("SEOtitle", $seoTitle);
	$smarty->assign("SEOkeywords", $data["SEOkeywords"]);
	$smarty->assign("SEOdescription", $data["SEOdescription"]);
	$smarty->assign("SEOnoIndex", $data["SEOnoIndex"]);

	// Template file from the permalinks table
	$smartyTemplateFile = $data["templateFile"];

}

// In case the no template file is set.
if(!$smartyTemplateFile) { $smartyTemplateFile = "404.tpl"; }

?>
