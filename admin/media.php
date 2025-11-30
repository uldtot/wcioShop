<?php

$smartyTemplateFile = "media.tpl";

// Load index for smarty functions and login validation
include_once dirname(__FILE__) . '/index.php';
include_once dirname(__FILE__) . '/inc/media_functions.php';

// Evt. action/id (hvis du bruger dem andetsteds)
$action = $_REQUEST["action"] ?? null;
$pageId = $_REQUEST["id"] ?? null;

// Load template functions
include_once dirname(__FILE__) . '/inc/templateFunctions.php';

// Modifier til startswith
$smarty->registerPlugin('modifier', 'startswith', function ($string, $substring) {
    return strpos($string, $substring) === 0;
});

// Display template
$smarty->display($smartyTemplateFile);
