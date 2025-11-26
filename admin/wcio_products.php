<?php
$smartyTemplateFile = "products.tpl";

// Load index for smarty functions and login valitation
include(dirname(__FILE__) . '/index.php');


// Load functions for this file...
$action = $_REQUEST["action"] ?? null;
$pageId = $_REQUEST["id"] ?? null;

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Funktion til at hente både mapper og filer fra en given mappe
function getFilesAndFolders($folderPath) {
    $filesAndFolders = [
        'folders' => [],
        'files' => []
    ];

    // Check om mappen eksisterer
    if (is_dir($folderPath)) {
        // Åbn mappen og læs indholdet
        if ($handle = opendir($folderPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $fullPath = $folderPath . DIRECTORY_SEPARATOR . $entry;

                    if (is_dir($fullPath)) {
                        // Hvis det er en mappe, tilføj til folder-listen
                        $filesAndFolders['folders'][] = $entry;
                    } else {
                        // Hvis det er en fil, tilføj til fil-listen
                        $fileSize = filesize($fullPath);
                        $filesAndFolders['files'][] = [
                            'name' => $entry,
                            'size' => formatFileSize($fileSize),
                            'path' => $fullPath // Send filstien til Smarty
                        ];
                    }
                }
            }
            closedir($handle);
        }
    }
    return $filesAndFolders;
}


// Load functions for this file...
$action = $_REQUEST["action"] ?? null;
$productId = $_REQUEST["id"] ?? null;

// Media handle for products
// Hent den valgte mappe fra URL (hvis der er en)
$currentFolder = $_GET['folder'] ?? '/uploads';

// Funktion til at hente mapper og filer i den valgte mappe
$folderPath = dirname(__FILE__) . '/../' . $currentFolder;
$filesAndFolders = getFilesAndFolders($folderPath);

// Hvis der ikke er valgt mappe, vis "Root"
if (empty($currentFolder)) {
    $currentFolder = '';
    $parentFolder = ''; // Root har ikke en overordnet mappe
} else {
    // Hvis en mappe er valgt, brug explode til at få den forrige mappe
    $parentFolder = implode('/', array_slice(explode('/', $currentFolder), 0, -1));
}

// Send data til Smarty
$smarty->assign('filesAndFolders', $filesAndFolders);
$smarty->assign('currentFolder', $currentFolder);
$smarty->assign('parentFolder', $parentFolder);  // Send parentFolder til Smarty
$smarty->assign('currentId', $pageId);  

// Send data til Smarty for at vise om vi er i 'uploads' mappen
$smarty->assign('isUploadsFolder', $currentFolder === 'uploads');


$smarty->registerPlugin('modifier', 'startswith', function($string, $substring) {
    return strpos($string, $substring) === 0;
});


// Media handle END

// If we want to edit a product. Load data
        if (isset($pageId) && $action == "delete") {
                try {
                    // Prepare the delete query
                    $deleteQuery = "DELETE FROM wcio_se_products WHERE id = :id";
                    $deleteStmt = $dbh->prepare($deleteQuery);
                    $deleteStmt->bindParam(':id', $pageId);
                    
                    // Execute the delete statement
                    $deleteStmt->execute();
                    
                    // Delete postmeta
                    
                    
                    // Check if any rows were affected
                    if ($deleteStmt->rowCount() > 0) {
                        echo "Product deleted successfully.";
                    } else {
                        echo "No product found with the specified ID.";
                    }
                    
                    // Prepare the delete query
                    $deleteQuery = "DELETE FROM wcio_se_productmeta WHERE productId = :id";
                    $deleteStmt = $dbh->prepare($deleteQuery);
                    $deleteStmt->bindParam(':id', $pageId);
                    
                    // Execute the delete statement
                    $deleteStmt->execute();

                    // Delete permalink data

                    if (isset($pageId) && $action == "delete") {
                        $success = deletePermalink($pageId, 'product');
                        
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
    
        // If product id are zero (0) then its a new product. Add it first.
     if($productId == 0) {
                                $insertQuery = "INSERT INTO wcio_se_products (active) VALUES (0)";
                                $insertStmt = $dbh->prepare($insertQuery);
                                $insertStmt->execute();
                                $productId = $dbh->lastInsertId();
     }

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
                        $selectStmt->bindParam(':columnName', $key);
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
