<?php
// Assign metas
$smarty->assign("SEOtitle", "");
$smarty->assign("SEOkeywords", "");
$smarty->assign("SEOdescription", "");
$smarty->assign("SEOindex", "");
$smarty->assign("SEOdefaultSiteName", ""); // This is used like this: If the <title> is lore ipsum | {$storeSeoShortName}

// Template file from the permalinks table
$smartyTemplateFile = "index.tpl";
?>
