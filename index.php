<?php
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
$stmt = $dbh->prepare("SELECT type,value1 FROM wcio_se_settings");
$result = $stmt->execute();
while($setting = $stmt->fetch( PDO::FETCH_ASSOC )) {

      $_SETTING[$setting['type']] = $setting['value1'];
      $smarty->assign('setting'.ucfirst($setting['type']).'',$setting['value1']); // Save setting for smarty

}

// SEO : Load the current URL from permalinks table and load meta


// Ready to load content

/* INDEX */
$smartyTemplateFile = "index.tpl";
// If no cache of this page is done, then we need to load all functions etc to make the cache file.

if (!$smarty->isCached($smartyTemplateFile, $smartyTemplateFile)) {

    // Load only the template-fucntions that is used in the template files.
    $templateFiles = array(
        "$smartyTemplateFile", // This is the current template file
        "template-parts/head.tpl", // This is required template file
        "template-parts/header.tpl", // This is required template file
        "template-parts/footer.tpl" // This is required template file
    );

    foreach ($templateFiles as $key => $templateFile) {

        if(file_exists($templateDir . $templateFile)) {
              $fc = file_get_contents($templateDir . $templateFile);

              $data = array();
              preg_match_all('/\$([a-zA-Z0-9]*)/is', $fc, $data, PREG_PATTERN_ORDER);
              unset($data[0]);
              $data = $data[1];
              $data = array_unique($data); // Array is now (1, 2, 3)

              foreach ($data as $templateFunction) {

                  $templateFunctionFile = dirname(__FILE__) . "/inc/template-functions/$templateFunction.php";

                  if (file_exists($templateFunctionFile)) {

                      // Inclkue the php file to be used in template..
                      include($templateFunctionFile);
                  }
              }
        }
    }
} //end if cache


$smarty->display($smartyTemplateFile, $smartyTemplateFile);
