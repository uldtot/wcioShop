<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

 // Get all currencies from shop
$wcioShopAdminCurrenciesArray = explode(",", $_SETTING['storeCurrencies']);
$smarty->assign("wcioShopAdminCurrenciesArray", $wcioShopAdminCurrenciesArray);

// Get prices for a product