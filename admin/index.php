<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */
session_start();

require(dirname(__FILE__) . '/../inc/db.php'); //connect to database
require(dirname(__FILE__) . '/../libs/Smarty.class.php'); //Smarty

$smarty = new Smarty; //Start smarty

$templateDir       = dirname(__FILE__) . "/templates/admin/";
$smartyTemplateDir = "/templates/admin/";

$smarty->force_compile  = true; // Force admin to always recompile
$smarty->debugging      = true; //Deactivate when out of dev for test

$smarty->template_dir   = $templateDir; //Template dir
$smarty->assign('template_dir', $smartyTemplateDir);

// Load all shop settings from databse
$stmt = $dbh->prepare("SELECT columnName,columnValue FROM wcio_se_settings WHERE autoload = 1");
$result = $stmt->execute();
while($setting = $stmt->fetch( PDO::FETCH_ASSOC )) {

      // Assign values to be used in files
      $_SETTING[$setting['columnName']] = $setting['columnValue'];

      // Assign values to smarty for use in templates.
      $smarty->assign('setting'.ucfirst($setting['columnName']).'',$setting['columnValue']); // Save setting for smarty

}

// SEO : Load the current URL from permalinks including meta for this URL
require(dirname(__FILE__) . '/../inc/seo.php');

// Because this is admin, we require someone to be logged in. If thery are not, then we dont provide access to functions
require(dirname(__FILE__) . '/inc/login.php');

// Display the page and all its functions
$smarty->display($smartyTemplateFile);
