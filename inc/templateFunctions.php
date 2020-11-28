<?php

// In case the SEO function does not include remplate file, then its a 404
if(!$smartyTemplateFile) { $smartyTemplateFile = "404.tpl" }

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

?>
