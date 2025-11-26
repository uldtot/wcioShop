<?php

 // Get all currencies from shop
$wcioShopAdminCurrenciesArray = explode(",", $_SETTING['storeCurrencies']);
$smarty->assign("wcioShopAdminCurrenciesArray", $wcioShopAdminCurrenciesArray);

// Get prices for a product