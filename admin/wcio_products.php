<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */

$smartyTemplateFile = "products.tpl";

// Load index for smarty functions and login valitation
include(dirname(__FILE__) . '/index.php');

// Load functions for this file...
$action = $_REQUEST["action"] ?? null;
$productId = $_REQUEST["id"] ?? null;



// If we want to edit a product. Load data
if (isset($productId) && $action == "edit") {

        $productData = array();
        // Load product data
        $stmt = $dbh->prepare("SELECT * FROM wcio_se_products WHERE id = :id LIMIT 1");
        $stmt->execute(array(
                "id" => $productId,
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Getting permlink data
        $permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
        $permalinkStmt->execute(array(
                "id" => $productId,
        ));
        $permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

        // Get images for this product
        $attachmentStmt = $dbh->prepare("SELECT * FROM wcio_se_attachments WHERE attachmentType = 'productFeaturedImage' AND attachmentPostId = :id LIMIT 1");
        $attachmentStmt->execute(array(
                "id" => $productId,
        ));
        $attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);
        $attachmentValue = $attachmentData["attachmentValue"] ?? "";

        if (!$attachmentValue || !file_exists(ABSPATH . "/uploads/$attachmentValue")) {
                $images = "noimage.png";
        } else {
                $images = $attachmentData["attachmentValue"];
        }

        // Get all product meta data
        $postmetaStmt = $dbh->prepare("SELECT * FROM wcio_se_productmeta WHERE productId = :id");
        $postmetaStmt->execute(array(
                "id" => $productId,
        ));
        $postmetaData = $postmetaStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($postmetaData as $key => $postmeta) {
                $productData[$postmeta["columnName"]] = $postmeta["columnValue"];
        }

        // Add default data. 
        $productData['id'] = $productId;
        $productData['active'] = $data['active'] ?? 0;
        $productData['name'] = $data['name'] ?? "";
        $productData['images'] = $images;
        $productData['featured'] = $data['featured'] ?? 0;
        $productData['url'] = $permalinkData["url"] ?? "";
     
        // SEO data
      $seoData = fetchSeoData($productId, "product");

      $productData['SEOtitle'] = $seoData['SEOtitle'] ?? "";
      $productData['SEOkeywords'] = $seoData['SEOkeywords'] ?? "";
      $productData['SEOdescription'] = $seoData['SEOdescription'] ?? "";
      $productData['SEOnoIndex'] = $seoData['SEOnoIndex'] ?? 0;


        $smarty->assign("productData", $productData);

        // Get all categories from shop
        $wcioShopAdminCategoriesArray = array();

        $stmt = $dbh->prepare("SELECT * FROM wcio_se_categories ORDER BY id DESC");
        $stmt->execute(array());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop all categories and check if current product is in them
        foreach ($data as $key => $value) {

                // Now check if we get a hit.
                $stmt = $dbh->prepare("SELECT count(*) FROM wcio_se_product_categories WHERE prdid = :id AND catid = :catid LIMIT 1");
                $stmt->execute(array(
                        "id" => $productId,
                        "catid" => $value["id"],
                ));
                $data = $stmt->fetchColumn();

                $wcioShopAdminCategoriesArray[] = array(
                        "id" => $value["id"],
                        "name" => $value["name"],
                        "productInCategory" => $data
                );
        }

        $smarty->assign("wcioShopAdminCategoriesArray", $wcioShopAdminCategoriesArray);



        // overwrite the template file
        $smartyTemplateFile = "productsView.tpl";
}

// IF we want to save or update a product. Id determains if its one or the other.
if (isset($productId) && $action == "update") {

        // Get all fields from the post
        foreach ($_POST as $key => $value) {

                // Just skipping some fields we do not need
                if ($key == "id" || $key == "action" ||  $key == "name" || $key == "active" ||  $key == "productCategories" || $key == "permalink") {
                        continue;
                }

                // Now process the fields.
                try {

                        // Check if the row exists based on columnName and productId
                        $selectQuery = "SELECT COUNT(*) FROM wcio_se_productmeta 
    WHERE columnName = :columnName AND productId = :productId";
                        $selectStmt = $dbh->prepare($selectQuery);
                        $selectStmt->bindParam(':columnName', $columnName);
                        $selectStmt->bindParam(':productId', $productId);
                        $selectStmt->execute();
                        $rowCount = $selectStmt->fetchColumn();

                        if ($rowCount > 0) {

                                // Update the row
                                $updateQuery = "UPDATE wcio_se_productmeta SET columnValue = :columnValue WHERE columnName = :columnName AND productId = :productId";
                                $updateStmt = $dbh->prepare($updateQuery);
                                $updateStmt->bindParam(':columnValue', $value);
                                $updateStmt->bindParam(':columnName', $key);
                                $updateStmt->bindParam(':productId', $productId);
                                $updated = $updateStmt->execute();
                                $rowCount = $updateStmt->rowCount();
                        } else {

                                $insertQuery = "INSERT INTO wcio_se_productmeta (columnName, columnValue, productId) VALUES (:columnName, :columnValue, :productId)";
                                $insertStmt = $dbh->prepare($insertQuery);
                                $insertStmt->bindParam(':columnName', $key);
                                $insertStmt->bindParam(':columnValue', $value);
                                $insertStmt->bindParam(':productId', $productId);
                                $insertStmt->execute();
                        }
                } catch (PDOException $e) {
                        // Rollback the transaction on error

                        echo "Error: " . $e->getMessage();
                }
        }

        // Now update productname

        $productName = $_POST["name"] ?? "";
        $active = isset($_POST["active"]) ? 1 : 0; // If checkbox is checked, set active to 1, else 0
        $updateQuery = "UPDATE wcio_se_products SET name = :name, active = :active WHERE id = :productId";
        $updateStmt = $dbh->prepare($updateQuery);
        $updateStmt->bindParam(':name', $productName);
        $updateStmt->bindParam(':active', $active);
        $updateStmt->bindParam(':productId', $productId);
        $updated = $updateStmt->execute();
        $rowCount = $updateStmt->rowCount();

        

        // Now update the categories


        try {
                // Assuming $dbh is your PDO database connection, $productId is provided (e.g., from a form or URL)
                $categories = $_POST['productCategories']; // Array of selected category IDs from the form (checkboxes)

                // Check if categories are selected
                if (!empty($categories)) {


                        // Begin transaction to ensure data consistency (both DELETE and INSERT are handled atomically)
                        $dbh->beginTransaction();

                        //  Delete all categories for this product (prdid)
                        $deleteQuery = "DELETE FROM wcio_se_product_categories WHERE prdid = :prdid";
                        $deleteStmt = $dbh->prepare($deleteQuery);
                        $deleteStmt->bindParam(':prdid', $productId, PDO::PARAM_INT);
                        $deleteStmt->execute(); // Execute the delete query

                        // Insert the selected categories for this product (prdid)
                        $insertQuery = "INSERT INTO wcio_se_product_categories (prdid, catid) VALUES (:prdid, :catid)";
                        $insertStmt = $dbh->prepare($insertQuery);
                        $insertStmt->bindParam(':prdid', $productId, PDO::PARAM_INT); // Bind prdid to each insert

                        // Loop through the selected categories and insert each one
                        foreach ($categories as $catid) {
                                $insertStmt->bindParam(':catid', $catid, PDO::PARAM_INT); // Bind each catid
                                $insertStmt->execute(); // Execute the insert query
                        }

                        // Commit the transaction to finalize both DELETE and INSERT operations
                        $dbh->commit();
                }
        } catch (PDOException $e) {
                // Rollback the transaction if there's an error
                $dbh->rollBack();
                echo 'Error: ' . $e->getMessage();
        }


        // Permalink
        $cpageName = $_POST["name"];
        $pagePermalink = $_POST["permalink"];
      
        $pageSEOtitle = $_POST["SEOtitle"] ?? "";
        $pageSEOkeywords = $_POST["SEOkeywords"];
        $pageSEOdescription = $_POST["SEOdescription"];
        $pageSEOnoIndex = isset($_POST["SEOnoIndex"]) ? 1 : 0;

           // Now update the SEO table
                        // Example usage
                        $posttype = "product";
                        // Assuming $cpageName and $pageId are defined
                        $fallbackUrl = !empty($productName) ? $productName : $productId;

                        $saveSuccess = savePermalink($pagePermalink, $productId, $posttype, $pageSEOtitle, $pageSEOdescription, $pageSEOdescription, $pageSEOnoIndex, $fallbackUrl);

                        if ($saveSuccess) {
                               // echo "Permalink saved successfully.";
                        } else {
                               // echo "Failed to save the permalink.";
                        }





        header("Location: /admin/wcio_products.php?id=$productId&action=edit"); // Redirect

}



// IF we want to save or update a product. Id determains if its one or the other.
if ($action == "add") {

        // overwrite the template file
        $smartyTemplateFile = "productsView.tpl";
}




// Load template functions
include(dirname(__FILE__) . '/inc/wcio_templateFunctions.php');


// Display the page and all its functions
$smarty->display($smartyTemplateFile);
