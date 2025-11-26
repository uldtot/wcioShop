<?php
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
                        echo "Page deleted successfully.";
                    } else {
                        echo "No page found with the specified ID.";
                    }

                    // Delete permalink data

                    if (isset($pageId) && $action == "delete") {
                        $success = deletePermalink($pageId, 'page');
                        
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
        $pageData['content'] = $data['content'] ?? "";
        $pageData['url'] = $permalinkData["url"] ?? "";
        $pageData['isHomePage'] = $permalinkData["isHomePage"] ?? "";

        // SEO data
        $seoData = fetchSeoData($pageId, "page");

        $pageData['SEOtitle'] = $seoData['SEOtitle'] ?? "";
        $pageData['SEOkeywords'] = $seoData['SEOkeywords'] ?? "";
        $pageData['SEOdescription'] = $seoData['SEOdescription'] ?? "";
        $pageData['SEOnoIndex'] = $seoData['SEOnoIndex'] ?? 0;

        $smarty->assign("pageData", $pageData);

        // Get all data from shop for parent later
        $wcioShopAdminPagesArray = array();

        $stmt = $dbh->prepare("SELECT * FROM wcio_se_pages ORDER BY id DESC");
        $stmt->execute(array());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop all data and check if current product is in them
        foreach ($data as $key => $value) {

                $wcioShopAdminPagesArray[] = array(
                        "id" => $value["id"],
                        "name" => $value["name"],
                );
        }

        $smarty->assign("wcioShopAdminPagesArray", $wcioShopAdminPagesArray);



        // overwrite the template file
        $smartyTemplateFile = "pagesView.tpl";
}

// IF we want to save or update a product. Id determains if its one or the other.
if (isset($pageId) && $action == "update") {

        $pageId = $_POST["id"];
        $cpageName = $_POST["name"];
        $pagePermalink = $_POST["permalink"];
        $pageContent = $_POST["content"];
        $isHomePage = isset($_POST["isHomePage"]) ? 1 : 0;

        $pageSEOtitle = $_POST["SEOtitle"];
        $pageSEOkeywords = $_POST["SEOkeywords"];
        $pageSEOdescription = $_POST["SEOdescription"];
        $pageSEOnoIndex = isset($_POST["SEOnoIndex"]) ? 1 : 0;

        try {


                // Check if its new or update
                if($pageId == 0) {

                        // Insert the new content
                        $insertQuery = "INSERT INTO wcio_se_pages (name, content) VALUES (:name, :content)";
                        $insertStmt = $dbh->prepare($insertQuery);
                        $insertStmt->bindParam(':name', $cpageName);
                        $insertStmt->bindParam(':content', $pageContent);
                        
                        // Execute the insert statement
                        $insertStmt->execute();

                        // Get the last inserted ID
                        $pageId = $dbh->lastInsertId();

                  } else {

                        $updateQuery = "UPDATE wcio_se_pages SET name = :name, content = :content WHERE id = :id";
                        $updateStmt = $dbh->prepare($updateQuery);
                        $updateStmt->bindParam(':name', $cpageName);
                        $updateStmt->bindParam(':content', $pageContent);
                        $updateStmt->bindParam(':id', $pageId);
                        $updated = $updateStmt->execute();
                        $rowCount = $updateStmt->rowCount();
                
                }

                        // Now update the SEO table
                        // Example usage
                        $posttype = "page";
                        // Assuming $cpageName and $pageId are defined
                        $fallbackUrl = !empty($cpageName) ? $cpageName : $pageId;

                        $saveSuccess = savePermalink($pagePermalink, $pageId, $posttype, $pageSEOtitle, $pageSEOkeywords, $pageSEOdescription, $pageSEOnoIndex, $fallbackUrl);

                        if ($saveSuccess) {
                               // echo "Permalink saved successfully.";
                        } else {
                               // echo "Failed to save the permalink.";
                        }


                                
           // If homepage update
try {
    if ($isHomePage == 1) {

        // Fetch the current homepage based on its URL and template file
        $currentHomepageStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE url = '/' AND templateFile = 'index.tpl' LIMIT 1");
        $currentHomepageStmt->execute();
        $currentHomepageData = $currentHomepageStmt->fetch(PDO::FETCH_ASSOC);


        if ($currentHomepageData) {
            // Update the old homepage permalink with a new URL and template file
            $oldHomepageId = $currentHomepageData['postId'];
            $oldHomepageUrl = $currentHomepageData['url'];
            $oldTemplateFile = "page.tpl";  // Setting the template file to 'page.tpl'

            $updateOldHomepagePermalink = savePermalink(
                '',  // Empty URL to remove '/' from the current homepage
                $oldHomepageId,
                "page",
                $currentHomepageData['SEOtitle'] ?? '',
                $currentHomepageData['SEOdescription'] ?? '',
                $currentHomepageData['SEOkeywords'] ?? '',
                $currentHomepageData['SEOnoIndex'] ?? 0,
                $currentHomepageData['id']
            );

            // Update the old homepage template file to 'page.tpl'
            $updateOldHomepageStmt = $dbh->prepare("UPDATE wcio_se_permalinks SET templateFile = :templateFile WHERE postId = :postId");
            $updateOldHomepageStmt->execute([
                ':templateFile' => $oldTemplateFile,
                ':postId' => $oldHomepageId
            ]);
        }

       
        // Set the new page as the homepage
        $newHomepageUrl = '/';
        $newTemplateFile = 'index.tpl';  // Set this page's template file to 'index.tpl'

            // Update the current page's template file to 'index.tpl'
        $updateNewHomepageStmt = $dbh->prepare("UPDATE wcio_se_permalinks SET templateFile = :templateFile, url = '/', isHomePage = 1 WHERE postId = :postId");
        $updateNewHomepageStmt->execute([
            ':templateFile' => $newTemplateFile,
            ':postId' => $pageId
        ]);
    }
} catch (PDOException $e) {
    // Rollback transaction or handle error
    echo "Error: " . $e->getMessage(); die();
}




        } catch (PDOException $e) {
                // Rollback the transaction on error

                echo "Error: " . $e->getMessage();
        }


        header("Location: /admin/wcio_pages.php?id=$pageId&action=edit"); // Redirect

}


// IF we want to save or update a product. Id determains if its one or the other.
if ($action == "add") {

        // Add default data. 
        $pageData['id'] = "0";
        $pageData['name'] = "";
        $pageData['content'] = "";
        $pageData['url'] = "";
        $pageData['isHomePage'] = 0;

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
