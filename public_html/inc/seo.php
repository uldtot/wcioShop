<?php
// Load permalink settings for this URL.
$currentUrl = explode('?', $_SERVER['REQUEST_URI'], 2);
$currentUrl = $currentUrl[0];

$stmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE url = :url LIMIT 1");
$result = $stmt->execute(array(
	"url" => $currentUrl,
));


while($data = $stmt->fetch( PDO::FETCH_ASSOC )) {

	// Send the permalink data to Smarty or scripts
	$smarty->assign("SEOpermalinkData", $data);
	$_SETTING["SEOpermalinkData"] = $data;

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

	// Assign other data from permalink table	
	$smarty->assign("postType", $data["postType"]);
	$smarty->assign("postId", $data["postId"]);
	$smarty->assign("templateFile", $data["templateFile"]);
	$smarty->assign("smartyCache", $data["smartyCache"]);

	// Template file from the permalinks table
	$smartyTemplateFile = $data["templateFile"];

	// If we have set this URL to not be cached, then deactivate
	if($data["smartyCache"] == "0") {
		$smarty->caching  = false;
	} else {
		$smarty->caching  = true; //Activate when out of dev
	}

	// Assign SEO data to array 
	$_SETTING["seoArray"] = $data;
	
}

// In case the no template file is set.
if(!$smartyTemplateFile) { $smartyTemplateFile = "404.tpl"; }
