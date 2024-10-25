<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

$smartyTemplateFile = "pages.tpl";

// Load index for smarty functions and login valitation
include(dirname(__FILE__) . '/index.php');

// Load functions for this file...
$action = $_REQUEST["action"] ?? null;
$pageId = $_REQUEST["id"] ?? null;

// If we want to edit a product. Load data
        if (isset($pageId) && $action == "delete") {
                try {
                    // Prepare the delete query
                    $deleteQuery = "DELETE FROM wcio_se_pages WHERE id = :id";
                    $deleteStmt = $dbh->prepare($deleteQuery);
                    $deleteStmt->bindParam(':id', $pageId);
                    
                    // Execute the delete statement
                    $deleteStmt->execute();
                    
                    // Check if any rows were affected
                    if ($deleteStmt->rowCount() > 0) {
                        echo "Category deleted successfully.";
                    } else {
                        echo "No category found with the specified ID.";
                    }

                    // Delete permalink data

                    if (isset($pageId) && $action == "delete") {
                        $success = deletePermalink($pageId, 'category');
                        
                        if ($success) {
                            //echo "Permalink deleted successfully.";
                        } else {
                            //echo "Failed to delete permalink or it does not exist.";
                        }
                    }


                } catch (PDOException $e) {
                    // Handle any errors
                    echo "Error: " . $e->getMessage();
                }
            }


// If we want to edit a product. Load data
if (isset($pageId) && $action == "edit") {

        $pageData = array();
        // Load product data
        $stmt = $dbh->prepare("SELECT * FROM wcio_se_pages WHERE id = :id LIMIT 1");
        $stmt->execute(array(
                "id" => $pageId,
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Getting permlink data
        $permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'page' AND postId = :id LIMIT 1");
        $permalinkStmt->execute(array(
                "id" => $pageId,
        ));
        $permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);


        // Add default data. 
        $pageData['id'] = $data['id'];
        $pageData['name'] = $data['name'] ?? "";
        $pageData['description'] = $data['description'] ?? "";
        $pageData['url'] = $permalinkData["url"] ?? "";

        // SEO data
        $seoData = fetchSeoData($pageId, "page");

        $pageData['SEOtitle'] = $seoData['SEOtitle'] ?? "";
        $pageData['SEOkeywords'] = $seoData['SEOkeywords'] ?? "";
        $pageData['SEOdescription'] = $seoData['SEOdescription'] ?? "";
        $pageData['SEOnoIndex'] = $seoData['SEOnoIndex'] ?? 0;

        $smarty->assign("pageData", $pageData);

        // Get all categories from shop for parent category later
        $wcioShopAdminCategoriesArray = array();

        $stmt = $dbh->prepare("SELECT * FROM wcio_se_pages ORDER BY id DESC");
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
if (isset($pageId) && $action == "update") {


        $pageId = $_POST["id"];
        $categoryName = $_POST["name"];
        $categoryPermalink = $_POST["permalink"];
        $categoryDescription = $_POST["fullDescription"];

        $categorySEOtitle = $_POST["SEOtitle"];
        $categorySEOkeywords = $_POST["SEOkeywords"];
        $categorySEOdescription = $_POST["SEOdescription"];
        $categorySEOnoIndex = isset($_POST["SEOnoIndex"]) ? 1 : 0;


        try {


                // Check if its new or update
                if($pageId == 0) {

                        // Insert the new category
                        $insertQuery = "INSERT INTO wcio_se_pages (name, description) VALUES (:name, :description)";
                        $insertStmt = $dbh->prepare($insertQuery);
                        $insertStmt->bindParam(':name', $categoryName);
                        $insertStmt->bindParam(':description', $categoryDescription);
                        
                        // Execute the insert statement
                        $insertStmt->execute();

                        // Get the last inserted ID
                        $pageId = $dbh->lastInsertId();

                  } else {

                        $updateQuery = "UPDATE wcio_se_pages SET name = :name, description = :description WHERE id = :id";
                        $updateStmt = $dbh->prepare($updateQuery);
                        $updateStmt->bindParam(':name', $categoryName);
                        $updateStmt->bindParam(':description', $categoryDescription);
                        $updateStmt->bindParam(':id', $pageId);
                        $updated = $updateStmt->execute();
                        $rowCount = $updateStmt->rowCount();
                
                }

                        // Now update the SEO table
                        // Example usage
                        $posttype = "category";
                        // Assuming $categoryName and $pageId are defined
                        $fallbackUrl = !empty($categoryName) ? $categoryName : $pageId;

                        $saveSuccess = savePermalink($categoryPermalink, $pageId, $posttype, $categorySEOtitle, $categorySEOdescription, $categorySEOdescription, $categorySEOnoIndex, $fallbackUrl);

                        if ($saveSuccess) {
                               // echo "Permalink saved successfully.";
                        } else {
                               // echo "Failed to save the permalink.";
                        }

        } catch (PDOException $e) {
                // Rollback the transaction on error

                echo "Error: " . $e->getMessage();
        }


        header("Location: /admin/wcio_categories.php?id=$pageId&action=edit"); // Redirect

}


// IF we want to save or update a product. Id determains if its one or the other.
if ($action == "add") {

        // Add default data. 
        $pageData['id'] = "0";
        $pageData['name'] = "";
        $pageData['description'] = "";
        $pageData['url'] = "";

        // SEO data
        $pageData['SEOtitle'] = "";
        $pageData['SEOkeywords'] = "";
        $pageData['SEOdescription'] = "";
        $pageData['SEOnoIndex'] = 0;

        $smarty->assign("pageData", $pageData);


        // overwrite the template file
        $smartyTemplateFile = "pagesView.tpl";
}


// Load template functions
include(dirname(__FILE__) . '/inc/wcio_templateFunctions.php');


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
