<?php
/*
* wcioShop
* Version 1.0.0
* Author: Kim Vinberg <support@websitecare.io>
* Source: https://github.com/websitecareio/wcioShop
* License: https://github.com/websitecareio/wcioShop/blob/master/LICENSE
 */
session_start();

/** Absolute path to the store directory. */
if (!defined('ABSPATH')) {
      define('ABSPATH', dirname(__DIR__, 1));
}

if (!defined('storeadmin')) {
      define('storeadmin', true);
}

require_once(dirname(__FILE__) . '/../inc/db.php'); //connect to database
require_once(dirname(__FILE__) . '/../libs/Smarty.class.php'); //Smarty

// Permalink function
// Permalink function
function savePermalink(
      $newUrl, 
      $postId, 
      $postType, 
      $seoTitle = '', 
      $seoKeywords = '', 
      $seoDescription = '', 
      $seoNoIndex = 0, // Default to 0 (false),
      $fallbackUrl = ""
  ) {
      global $dbh; // Use the global database handler
      
      if($newUrl == "") {
            $newUrl = $fallbackUrl;
      }
      // Remove leading and trailing slashes from the URL
      $newUrl = trim($newUrl, '/');
      $newUrl = "/" . sanitizeSeoUrl($newUrl) . "/"; // Making sure all URLs have this.
  
      // Base URL without suffix
      $baseUrl = $newUrl;
      $suffix = 0;
      
      // Check for existing URLs and find a unique one
      do {
          // Prepare the query to check for existing URLs
          $checkQuery = "SELECT COUNT(*) FROM wcio_se_permalinks WHERE url = :url AND postType = :postType AND postId != :postId";
          $checkStmt = $dbh->prepare($checkQuery);
          $checkStmt->bindParam(':url', $newUrl);
          $checkStmt->bindParam(':postType', $postType);
          $checkStmt->bindParam(':postId', $postId);
          $checkStmt->execute();
      
          // Fetch the count of existing URLs
          $count = $checkStmt->fetchColumn();
      
          // If the URL already exists for another post, modify it
          if ($count > 0) {
              $suffix++;
              $newUrl = $baseUrl . '-' . $suffix;
          }
      } while ($count > 0);
      
      // Try to update the existing permalink first
      $updateQuery = "UPDATE wcio_se_permalinks 
                      SET url = :url, 
                          SEOtitle = :seoTitle, 
                          SEOkeywords = :seoKeywords, 
                          SEOdescription = :seoDescription, 
                          SEOnoIndex = :seoNoIndex 
                      WHERE postType = :postType AND postId = :postId";
      $updateStmt = $dbh->prepare($updateQuery);
      $updateStmt->bindParam(':url', $newUrl);
      $updateStmt->bindParam(':seoTitle', $seoTitle);
      $updateStmt->bindParam(':seoKeywords', $seoKeywords);
      $updateStmt->bindParam(':seoDescription', $seoDescription);
      $updateStmt->bindParam(':seoNoIndex', $seoNoIndex);
      $updateStmt->bindParam(':postType', $postType);
      $updateStmt->bindParam(':postId', $postId);
      $updated = $updateStmt->execute();
      
      // If no rows were updated, insert a new permalink
      if ($updateStmt->rowCount() === 0) {


        switch ($postType) {
            case "category":
                $templateFile = "category.tpl";
                break;
            case "page":
                    $templateFile = "page.tpl";
                    break;
                    
            case "product":
                $templateFile = "product.tpl";
                break;
        }


          $insertQuery = "INSERT INTO wcio_se_permalinks (url, templateFile, postType, postId, SEOtitle, SEOkeywords, SEOdescription, SEOnoIndex) 
                          VALUES (:url, :templateFile, :postType, :postId, :seoTitle, :seoKeywords, :seoDescription, :seoNoIndex)";
          $insertStmt = $dbh->prepare($insertQuery);
          $insertStmt->bindParam(':url', $newUrl);
          $insertStmt->bindParam(':postType', $postType);
          $insertStmt->bindParam(':postId', $postId);
          $insertStmt->bindParam(':seoTitle', $seoTitle);
          $insertStmt->bindParam(':seoKeywords', $seoKeywords);
          $insertStmt->bindParam(':seoDescription', $seoDescription);
          $insertStmt->bindParam(':seoNoIndex', $seoNoIndex);
          $insertStmt->bindParam(':templateFile', $templateFile);

          
          $inserted = $insertStmt->execute();
      
          return $inserted; // Return whether the insert was successful
      }
      
      return $updated; // Return whether the update was successful
  }
  
  function deletePermalink($postId, $postType) {
    global $dbh; // Use the global database handler
    
    try {
        // Prepare the delete query
        $deleteQuery = "DELETE FROM wcio_se_permalinks WHERE postId = :postId AND postType = :postType";
        $deleteStmt = $dbh->prepare($deleteQuery);
        $deleteStmt->bindParam(':postId', $postId);
        $deleteStmt->bindParam(':postType', $postType);
        
        // Execute the delete statement
        $deleted = $deleteStmt->execute();
        
        // Check if any rows were affected
        if ($deleted && $deleteStmt->rowCount() > 0) {
            return true; // Deletion was successful
        } else {
            return false; // No rows were deleted (might not exist)
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
        return false; // Indicate failure
    }
}

// Fetch SEO data
function fetchSeoData($postId, $postType) {
      global $dbh; // Use the global database handler
      
      // Prepare the query to fetch SEO data
      $query = "SELECT SEOtitle, SEOkeywords, SEOdescription, SEOnoIndex FROM wcio_se_permalinks WHERE postId = :postId AND postType = :postType";
      $stmt = $dbh->prepare($query);
      $stmt->bindParam(':postId', $postId);
      $stmt->bindParam(':postType', $postType);
      $stmt->execute();
      
      // Fetch the data
      $seoData = $stmt->fetch(PDO::FETCH_ASSOC);
      
      // Return the fetched SEO data or null if not found
      return $seoData ?: null;
  }
  
  //sanitizeSeoUrl
  function sanitizeSeoUrl($url) {
      // Remove any leading or trailing whitespace
      $url = trim($url);
      
      // Define character replacements
      $charReplacements = [
          'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'å' => 'aa', 'ã' => 'a', 'ā' => 'a',
          'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'ē' => 'e',
          'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i', 'ī' => 'i',
          'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'ø' => 'oe', 'õ' => 'o', 'ō' => 'o',
          'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u', 'ū' => 'u',
          'ç' => 'c', 'ñ' => 'n', 'ý' => 'y', 'ÿ' => 'y',
          'Æ' => 'Ae', 'æ' => 'ae', 'Ø' => 'Oe', 'ø' => 'oe', 'Å' => 'Aa', 'å' => 'aa',
          'ß' => 'ss', 'þ' => 'th', 'Þ' => 'Th', '&' => '-', ' ' => '-' 
          // Add more replacements as needed
      ];
      
      // Replace special characters with their ASCII equivalents
      $url = str_replace(array_keys($charReplacements), array_values($charReplacements), $url);
      
      // Convert to lowercase
      $url = strtolower($url);
      
      // Remove any unwanted characters (keep alphanumeric, dashes, underscores, and slashes)
      $url = preg_replace('/[^a-z0-9\-\/]/', '', $url);
      
      // Replace spaces with hyphens
      $url = preg_replace('/\s+/', '-', $url);
      
      // Remove duplicate hyphens
      $url = preg_replace('/-+/', '-', $url);
      
      // Remove trailing hyphens
      $url = rtrim($url, '-');
  
      return $url;
  }
$smarty = new Smarty; //Start smarty
// set directory where compiled templates are stored

$templateDir       = dirname(__FILE__) . "/../templates/admin/";
$smartyTemplateDir = "/../templates/admin/";


$smarty->force_compile  = true; // Force admin to always recompile
$smarty->debugging      = false; //Deactivate when out of dev for test

$smarty->template_dir   = $templateDir; //Template dir
$smarty->assign('template_dir', $smartyTemplateDir);
$smarty->setCompileDir(dirname(__FILE__) . '/../templates_c');

// Load all shop settings from databse
$stmt = $dbh->prepare("SELECT columnName,columnValue FROM wcio_se_settings WHERE autoload = 1");
$result = $stmt->execute();
while ($setting = $stmt->fetch(PDO::FETCH_ASSOC)) {

      // Assign values to be used in files
      $_SETTING[$setting['columnName']] = $setting['columnValue'];

      // Assign values to smarty for use in templates.
      $smarty->assign('setting' . ucfirst($setting['columnName']) . '', $setting['columnValue']); // Save setting for smarty

}

// Because this is admin, we require someone to be logged in. If thery are not, then we dont provide access to functions
include(dirname(__FILE__) . '/inc/wcio_validateLogin.php');

if (!isset($smartyTemplateFile) || $smartyTemplateFile == "index.tpl") {

      // Default template file.
      $smartyTemplateFile = "index.tpl";

      // Load template functions
      include(dirname(__FILE__) . '/inc/wcio_templateFunctions.php');

      // Display the page and all its functions
      $smarty->display($smartyTemplateFile);
}
