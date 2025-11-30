<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */
 
 $smartyTemplateFile = "orders.tpl";

// Load index for smarty functions and login valitation
include_once dirname(__FILE__) . '/index.php';

// Load functions for this file...

// Load template functions
include_once dirname(__FILE__) . '/inc/templateFunctions.php';


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
