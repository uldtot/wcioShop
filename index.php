<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */
session_start();

require(dirname(__FILE__) . '/inc/db.php'); //connect to database
require(dirname(__FILE__) . '/libs/Smarty.class.php'); //Smarty

$smarty = new Smarty; //Start smarty

$templateDir       = dirname(__FILE__) . "/templates/default/";
$smartyTemplateDir = "/templates/default/";

$smarty->force_compile  = true; //Online when forcing new complie
$smarty->debugging      = true; //Activate when out of dev for test
$smarty->caching        = false; //Activate when out of dev
$smarty->cache_lifetime = 21600; //120
$smarty->template_dir   = $templateDir; //Template dir
$smarty->assign('template_dir', $smartyTemplateDir);

// Load all shop settings from databse
$stmt = $dbh->prepare("SELECT columnName,columnValue FROM wcio_se_settings");
$result = $stmt->execute();
while($setting = $stmt->fetch( PDO::FETCH_ASSOC ))

      // Assign values to be used in files
      $_SETTING[$setting['columnName']] = $setting['columnValue'];

      // Assign values to smarty for use in templates.
      $smarty->assign('setting'.ucfirst($setting['columnName']).'',$setting['columnValue']); // Save setting for smarty

}

// SEO : Load the current URL from permalinks including meta for this URL
include(dirname(__FILE__) . '/inc/seo.php');

// If no cache of this page is done, then we need to load all functions etc to make the cache file.
if (!$smarty->isCached($smartyTemplateFile, $smartyTemplateFile)) {

      // Load template functions
      include(dirname(__FILE__) . '/inc/templateFunctions.php');

} //end if cache

// Display the page and all its functions
$smarty->display($smartyTemplateFile, $smartyTemplateFile);
