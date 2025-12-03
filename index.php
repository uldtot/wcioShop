<?php
session_start();

/** Absolute path to the store directory. */
if (!defined('ABSPATH')) {
      define('ABSPATH', __DIR__ . '/');
}

if (!defined('storeadmin')) {
      define('storeadmin', false);
}

require_once dirname(__FILE__) . '/inc/db.php'; // Connect to database
require_once dirname(__FILE__) . '/libs/Smarty.class.php'; //Smarty

$smarty = new Smarty; //Start smarty

$templateDir       = dirname(__FILE__) . "/templates/default/";
$smartyTemplateDir = "/templates/default/";

$smarty->force_compile  = false; // Dont force recompile when live
$smarty->debugging      = false; // Deactivate when out of dev for test

$smarty->cache_lifetime = 21600; //120
$smarty->template_dir   = $templateDir; // Template dir
$smarty->assign('template_dir', $smartyTemplateDir);

// Load all shop settings from databse and assign all with autoload enabled
$stmt = $dbh->prepare("SELECT columnName,columnValue FROM {$dbprefix}settings WHERE autoload = 1");
$result = $stmt->execute();
while ($setting = $stmt->fetch(PDO::FETCH_ASSOC)) {

      // Assign values to be used in files
      $_SETTING[$setting['columnName']] = $setting['columnValue'];

      // Assign values to smarty for use in templates.
      $smarty->assign('setting' . ucfirst($setting['columnName']) . '', $setting['columnValue']); // Save setting for smarty

}

// SEO : Load the current URL from permalinks including meta for this URL
include_once dirname(__FILE__) . '/inc/seo.php';


// If no cache of this page is done, then we need to load all functions etc. to make the cache file.
$cacheId = $_SETTING['smartyCacheId'] ?? null;


if ($smarty->caching && $cacheId) {

    if (!$smarty->isCached($smartyTemplateFile, $cacheId)) {
        
           // Load template functions
      include_once dirname(__FILE__) . '/inc/templateFunctions.php';
      
    }

    $smarty->display($smartyTemplateFile, $cacheId);

} else {
  
    // Enten caching = false, eller ingen cacheId → vis uden cache
          // Load template functions
      include_once dirname(__FILE__) . '/inc/templateFunctions.php';
    $smarty->display($smartyTemplateFile);
}




