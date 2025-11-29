<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */


 $smartyTemplateFile = "login.tpl";
 include("index.php");

 // Fix if there isnt a template loaded
 $smarty->display($smartyTemplateFile);