<?php
 $smartyTemplateFile = "login.tpl";
 include("index.php");

 // Fix if there isnt a template loaded
 $smarty->display($smartyTemplateFile);