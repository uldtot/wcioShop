<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

$smartyTemplateFile = "categories.tpl";

// Load index for smarty functions and login valitation
include(dirname(__FILE__) . '/index.php');

// Load functions for this file...
$action = $_REQUEST["action"] ?? null;
$categoryId = $_REQUEST["id"] ?? null;


// If we want to edit a product. Load data
if (isset($categoryId) && $action == "edit") {

        $categoryData = array();
        // Load product data
        $stmt = $dbh->prepare("SELECT * FROM wcio_se_categories WHERE id = :id LIMIT 1");
        $stmt->execute(array(
                "id" => $categoryId,
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Getting permlink data
        $permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'category' AND postId = :id LIMIT 1");
        $permalinkStmt->execute(array(
                "id" => $categoryId,
        ));
        $permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);


        // Add default data. 
        $categoryData['id'] = $data['id'];
        $categoryData['name'] = $data['name'] ?? "";
        $categoryData['description'] = $data['description'] ?? "";
        $categoryData['url'] = $permalinkData["url"] ?? "";

        // SEO data
        $seoData = fetchSeoData($categoryId, "category");

        $categoryData['SEOtitle'] = $seoData['SEOtitle'] ?? "";
        $categoryData['SEOkeywords'] = $seoData['SEOkeywords'] ?? "";
        $categoryData['SEOdescription'] = $seoData['SEOdescription'] ?? "";
        $categoryData['SEOnoIndex'] = $seoData['SEOnoIndex'] ?? 0;

        $smarty->assign("categoryData", $categoryData);

        // Get all categories from shop for parent category later
        $wcioShopAdminCategoriesArray = array();

        $stmt = $dbh->prepare("SELECT * FROM wcio_se_categories ORDER BY id DESC");
        $stmt->execute(array());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop all categories and check if current product is in them
        foreach ($data as $key => $value) {

                $wcioShopAdminCategoriesArray[] = array(
                        "id" => $value["id"],
                        "name" => $value["name"],
                );
        }

        $smarty->assign("wcioShopAdminCategoriesArray", $wcioShopAdminCategoriesArray);



        // overwrite the template file
        $smartyTemplateFile = "categoriesView.tpl";
}

// IF we want to save or update a product. Id determains if its one or the other.
if (isset($categoryId) && $action == "update") {


        $categoryid = $_POST["id"];
        $categoryName = $_POST["name"];
        $categoryPermalink = $_POST["permalink"];
        $categoryDescription = $_POST["fullDescription"];
        
        $categorySEOtitle = $_POST["SEOtitle"];
        $categorySEOkeywords = $_POST["SEOkeywords"];
        $categorySEOdescription = $_POST["SEOdescription"];
        $categorySEOnoIndex = $_POST["SEOnoIndex"];

        try {

                $updateQuery = "UPDATE wcio_se_categories SET name = :name, description = :description WHERE id = :id";
                $updateStmt = $dbh->prepare($updateQuery);
                $updateStmt->bindParam(':name', $categoryName);
                $updateStmt->bindParam(':description', $categoryDescription);
                $updateStmt->bindParam(':id', $categoryid);
                $updated = $updateStmt->execute();
                $rowCount = $updateStmt->rowCount();

                // Now update the SEO table
               // Example usage
               $posttype = "category";
               $saveSuccess = savePermalink($categoryPermalink, $categoryid, $posttype, $categorySEOtitle, $categorySEOdescription, $categorySEOdescription, $categorySEOnoIndex);

                if ($saveSuccess) {
                echo "Permalink saved successfully.";
                } else {
                echo "Failed to save the permalink.";
                }

        } catch (PDOException $e) {
                // Rollback the transaction on error

                echo "Error: " . $e->getMessage();
        }


        header("Location: /admin/wcio_categories.php?id=$categoryId&action=edit"); // Redirect

}


// IF we want to save or update a product. Id determains if its one or the other.
if ($action == "add") {

        // overwrite the template file
        $smartyTemplateFile = "categoriesView.tpl";
}


// Load template functions
include(dirname(__FILE__) . '/inc/wcio_templateFunctions.php');


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
