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
        if (isset($categoryId) && $action == "delete") {
                try {
                    // Prepare the delete query
                    $deleteQuery = "DELETE FROM {$dbprefix}categories WHERE id = :id";
                    $deleteStmt = $dbh->prepare($deleteQuery);
                    $deleteStmt->bindParam(':id', $categoryId);
                    
                    // Execute the delete statement
                    $deleteStmt->execute();
                    
                    // Check if any rows were affected
                    if ($deleteStmt->rowCount() > 0) {
                        echo "Category deleted successfully.";
                    } else {
                        echo "No category found with the specified ID.";
                    }

                    // Delete permalink data

                    if (isset($categoryId) && $action == "delete") {
                        $success = deletePermalink($categoryId, 'category');
                        
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
if (isset($categoryId) && $action == "edit") {

        $categoryData = array();
        // Load product data
        $stmt = $dbh->prepare("SELECT * FROM {$dbprefix}categories WHERE id = :id LIMIT 1");
        $stmt->execute(array(
                "id" => $categoryId,
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Getting permlink data
        $permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'category' AND postId = :id LIMIT 1");
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

        $stmt = $dbh->prepare("SELECT * FROM {$dbprefix}categories ORDER BY id DESC");
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


        $categoryId = $_POST["id"];
        $categoryName = $_POST["name"];
        $categoryPermalink = $_POST["permalink"];
        $categoryDescription = $_POST["fullDescription"];

        $categorySEOtitle = $_POST["SEOtitle"];
        $categorySEOkeywords = $_POST["SEOkeywords"];
        $categorySEOdescription = $_POST["SEOdescription"];
        $categorySEOnoIndex = isset($_POST["SEOnoIndex"]) ? 1 : 0;


        try {


                // Check if its new or update
                if($categoryId == 0) {

                        // Insert the new category
                        $insertQuery = "INSERT INTO {$dbprefix}categories (name, description) VALUES (:name, :description)";
                        $insertStmt = $dbh->prepare($insertQuery);
                        $insertStmt->bindParam(':name', $categoryName);
                        $insertStmt->bindParam(':description', $categoryDescription);
                        
                        // Execute the insert statement
                        $insertStmt->execute();

                        // Get the last inserted ID
                        $categoryId = $dbh->lastInsertId();

                  } else {

                        $updateQuery = "UPDATE {$dbprefix}categories SET name = :name, description = :description WHERE id = :id";
                        $updateStmt = $dbh->prepare($updateQuery);
                        $updateStmt->bindParam(':name', $categoryName);
                        $updateStmt->bindParam(':description', $categoryDescription);
                        $updateStmt->bindParam(':id', $categoryId);
                        $updated = $updateStmt->execute();
                        $rowCount = $updateStmt->rowCount();
                
                }

                        // Now update the SEO table
                        // Example usage
                        $posttype = "category";
                        // Assuming $categoryName and $categoryId are defined
                        $fallbackUrl = !empty($categoryName) ? $categoryName : $categoryId;

                        $saveSuccess = savePermalink($categoryPermalink, $categoryId, $posttype, $categorySEOtitle, $categorySEOdescription, $categorySEOdescription, $categorySEOnoIndex, $fallbackUrl);

                        if ($saveSuccess) {
                               // echo "Permalink saved successfully.";
                        } else {
                               // echo "Failed to save the permalink.";
                        }

        } catch (PDOException $e) {
                // Rollback the transaction on error

                echo "Error: " . $e->getMessage();
        }


        header("Location: /admin/categories.php?id=$categoryId&action=edit"); // Redirect

}


// IF we want to save or update a product. Id determains if its one or the other.
if ($action == "add") {

        // Add default data. 
        $categoryData['id'] = "0";
        $categoryData['name'] = "";
        $categoryData['description'] = "";
        $categoryData['url'] = "";

        // SEO data
        $categoryData['SEOtitle'] = "";
        $categoryData['SEOkeywords'] = "";
        $categoryData['SEOdescription'] = "";
        $categoryData['SEOnoIndex'] = 0;

        $smarty->assign("categoryData", $categoryData);


        // overwrite the template file
        $smartyTemplateFile = "categoriesView.tpl";
}


// Load template functions
include(dirname(__FILE__) . '/inc/templateFunctions.php');


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
