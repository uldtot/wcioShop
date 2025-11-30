<?php
$smartyTemplateFile = "login.tpl";
include_once "index.php";

// Fix if there isnt a template loaded
$smarty->display($smartyTemplateFile);
