<?php
// Load permalink settings for this URL.
$currentUrl = explode('?', $_SERVER['REQUEST_URI'], 2);
$currentUrl = $currentUrl[0];

$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE url = :url LIMIT 1");
$result = $stmt->execute(array(
    "url" => $currentUrl,
));


while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

    // Send the permalink data to Smarty or scripts
    $smarty->assign("SEOpermalinkData", $data);
    $_SETTING["SEOpermalinkData"] = $data;

    // If we have set a Seo shortname in settings, then add it
    if ($_SETTING["storeSeoShortName"] != "") {
        $seoTitle = $data["SEOtitle"] . $_SETTING["storeSeoShortNameSeperator"] . $_SETTING["storeSeoShortName"];
    } else {
        $seoTitle = $data["SEOtitle"];
    }
    // Assign metas
    $smarty->assign("SEOtitle", $seoTitle);
    $smarty->assign("SEOkeywords", $data["SEOkeywords"]);
    $smarty->assign("SEOdescription", $data["SEOdescription"]);
    $smarty->assign("SEOnoIndex", $data["SEOnoIndex"]);

    // Assign other data from permalink table	
    $smarty->assign("postType", $data["postType"]);
    $smarty->assign("postId", $data["postId"]);
    $smarty->assign("templateFile", $data["templateFile"]);
    $smarty->assign("smartyCache", $data["smartyCache"]);

    // Template file from the permalinks table
    $smartyTemplateFile = $data["templateFile"];


    // Sæt cache-id ud fra URL (og evt. sprog/valuta)
    $lang     = $_SETTING['language'] ?? 'da';
    $currency = $_SETTING['currency'] ?? 'DKK';

    $cacheId = sprintf(
        'url|%s|lang=%s|cur=%s',
        md5($currentUrl),
        $lang,
        $currency
    );

    $_SETTING['smartyCacheId'] = $cacheId;


    // -------------------------
    // CACHE-STYRING:
    // 1) Database-værdi overrule (0 eller 1)
    // 2) Hvis ingen værdi → fallback til automatisk logik
    // -------------------------

    // Templates der ALDRIG må caches (fallback når DB ikke definerer noget)
    $noCacheTemplates = [
        'cart.tpl',
        'checkout.tpl',
        'checkout-step1.tpl',
        'checkout-step2.tpl',
        'login.tpl',
        'account.tpl',
        'order-view.tpl',
        'wcioShopLiveSearch.tpl'
    ];
    
    unset($data["smartyCache"]); // unset smartyCache from DB for the moment.
    if ($data["smartyCache"] === "0") {

        // DB siger: ingen cache
        $smarty->caching = false;
    } elseif ($data["smartyCache"] === "1") {

        // DB siger: cache på
        $smarty->caching = true;
    } else {

        // Ingen database-værdi → brug automatisk logik
        if (in_array($smartyTemplateFile, $noCacheTemplates)) {
            $smarty->caching = false;
        } else {
            $smarty->caching = true;
        }
    }


    // Assign SEO data
    $_SETTING["seoArray"] = $data;
}

// In case the no template file is set.
if (!$smartyTemplateFile) {
    $smartyTemplateFile = "404.tpl";
}
