<?php
$smartyTemplateFile = "products.tpl";

// Load index for smarty functions and login validation
include_once dirname(__FILE__) . '/index.php';
include_once dirname(__FILE__) . '/inc/media_functions.php';

// --------------------------------------------------
// Input-sanitizing
// --------------------------------------------------

$action   = $_REQUEST["action"] ?? null;
$idParam  = $_REQUEST["id"] ?? null;

// Sørg for at id altid er et heltal eller null
$pageId    = (is_numeric($idParam) ? (int)$idParam : null);
$productId = $pageId; // samme id bruges til produktet


// Modifier til startswith
$smarty->registerPlugin('modifier', 'startswith', function ($string, $substring) {
    return strpos($string, $substring) === 0;
});

// --------------------------------------------------
// PRODUCT DELETE
// --------------------------------------------------

// Her kunne man med fordel kræve POST + CSRF token, men vi ændrer ikke flowet, kun sikkerhed ift. id.
if ($pageId !== null && $action === "delete") {
    try {
        // Slet produkt
        $deleteQuery = "DELETE FROM {$dbprefix}products WHERE id = :id";
        $deleteStmt  = $dbh->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $pageId, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Tjek om noget blev slettet
        /*
        if ($deleteStmt->rowCount() > 0) {
            echo "Product deleted successfully.";
        } else {
            echo "No product found with the specified ID.";
        }
        */

        // Slet productmeta
        $deleteQuery = "DELETE FROM {$dbprefix}productmeta WHERE productId = :id";
        $deleteStmt  = $dbh->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $pageId, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Slet permalink data
        $success = deletePermalink($pageId, 'product');
        // du kan evt. logge $success

    } catch (PDOException $e) {
        // I produktion: log fejl i stedet for at vise rå besked
        echo "Error deleting product.";
        // error_log($e->getMessage());
    }
}

// --------------------------------------------------
// PRODUCT EDIT – load data
// --------------------------------------------------

if ($productId !== null && $action === "edit") {

    $productData = [];

    // Load product data
    $stmt = $dbh->prepare("SELECT * FROM {$dbprefix}products WHERE id = :id LIMIT 1");
    $stmt->execute([
        "id" => $productId,
    ]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Permalink data
    $permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
    $permalinkStmt->execute([
        "id" => $productId,
    ]);
    $permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

    // Billede
    $attachmentStmt = $dbh->prepare("SELECT * FROM {$dbprefix}attachments WHERE attachmentType = 'productFeaturedImage' AND attachmentPostId = :id LIMIT 1");
    $attachmentStmt->execute([
        "id" => $productId,
    ]);
    $attachmentData  = $attachmentStmt->fetch(PDO::FETCH_ASSOC);
    $attachmentValue = $attachmentData["attachmentValue"] ?? "";

    if (!$attachmentValue || !file_exists(ABSPATH . "/uploads/$attachmentValue")) {
        $images = "noimage.png";
    } else {
        $images = $attachmentData["attachmentValue"];
    }

    // Alle productmeta
    $postmetaStmt = $dbh->prepare("SELECT * FROM {$dbprefix}productmeta WHERE productId = :id");
    $postmetaStmt->execute([
        "id" => $productId,
    ]);
    $postmetaData = $postmetaStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($postmetaData as $postmeta) {
        $productData[$postmeta["columnName"]] = $postmeta["columnValue"];
    }

    // Standard felter
    $productData['id']       = $productId;
    $productData['active']   = $data['active'] ?? 0;
    $productData['name']     = $data['name'] ?? "";
    $productData['images']   = $images;
    $productData['featured'] = $data['featured'] ?? 0;
    $productData['url']      = $permalinkData["url"] ?? "";

    // SEO
    $seoData = fetchSeoData($productId, "product");

    $productData['SEOtitle']       = $seoData['SEOtitle'] ?? "";
    $productData['SEOkeywords']    = $seoData['SEOkeywords'] ?? "";
    $productData['SEOdescription'] = $seoData['SEOdescription'] ?? "";
    $productData['SEOnoIndex']     = $seoData['SEOnoIndex'] ?? 0;

    $smarty->assign("productData", $productData);

    // Alle kategorier
    $wcioShopAdminCategoriesArray = [];

    $stmt = $dbh->prepare("SELECT * FROM {$dbprefix}categories ORDER BY id DESC");
    $stmt->execute();
    $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categoriesData as $value) {
        $stmt = $dbh->prepare("SELECT count(*) FROM {$dbprefix}product_categories WHERE prdid = :id AND catid = :catid LIMIT 1");
        $stmt->execute([
            "id"    => $productId,
            "catid" => $value["id"],
        ]);
        $inCat = (int)$stmt->fetchColumn();

        $wcioShopAdminCategoriesArray[] = [
            "id"                => $value["id"],
            "name"              => $value["name"],
            "productInCategory" => $inCat
        ];
    }

    $smarty->assign("wcioShopAdminCategoriesArray", $wcioShopAdminCategoriesArray);

    // Brug edit-view
    $smartyTemplateFile = "productsView.tpl";
}

// --------------------------------------------------
// PRODUCT UPDATE / SAVE
// --------------------------------------------------

if ($productId !== null && $action === "update") {

    // Hvis productId er 0 → nyt produkt
    if ($productId === 0) {
        $insertQuery = "INSERT INTO {$dbprefix}products (active) VALUES (0)";
        $insertStmt  = $dbh->prepare($insertQuery);
        $insertStmt->execute();
        $productId = (int)$dbh->lastInsertId();
    }

    // Gem meta-felter
    foreach ($_POST as $key => $value) {

        // Spring disse felter over
        if (in_array($key, ["id", "action", "name", "active", "productCategories", "permalink", "SEOtitle", "SEOkeywords", "SEOdescription", "SEOnoIndex"], true)) {
            continue;
        }

        try {
            $selectQuery = "SELECT COUNT(*) FROM {$dbprefix}productmeta 
                            WHERE columnName = :columnName AND productId = :productId";
            $selectStmt = $dbh->prepare($selectQuery);
            $selectStmt->bindParam(':columnName', $key);
            $selectStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $selectStmt->execute();
            $rowCount = (int)$selectStmt->fetchColumn();

            if ($rowCount > 0) {
                $updateQuery = "UPDATE {$dbprefix}productmeta 
                                SET columnValue = :columnValue 
                                WHERE columnName = :columnName AND productId = :productId";
                $updateStmt = $dbh->prepare($updateQuery);
                $updateStmt->bindParam(':columnValue', $value);
                $updateStmt->bindParam(':columnName', $key);
                $updateStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                $insertQuery = "INSERT INTO {$dbprefix}productmeta (columnName, columnValue, productId) 
                                VALUES (:columnName, :columnValue, :productId)";
                $insertStmt = $dbh->prepare($insertQuery);
                $insertStmt->bindParam(':columnName', $key);
                $insertStmt->bindParam(':columnValue', $value);
                $insertStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
                $insertStmt->execute();
            }
        } catch (PDOException $e) {
            echo "Error updating product meta.";
            // error_log($e->getMessage());
        }
    }

    // Update produktets navn og active-flag
    $productName = $_POST["name"] ?? "";
    $active      = isset($_POST["active"]) ? 1 : 0;

    $updateQuery = "UPDATE {$dbprefix}products SET name = :name, active = :active WHERE id = :productId";
    $updateStmt  = $dbh->prepare($updateQuery);
    $updateStmt->bindParam(':name', $productName);
    $updateStmt->bindParam(':active', $active, PDO::PARAM_INT);
    $updateStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $updateStmt->execute();

    // Opdater kategorier
    try {
        $categories = $_POST['productCategories'] ?? [];

        // Du kan vælge at altid rydde og re-inserte, også hvis tomt
        $dbh->beginTransaction();

        $deleteQuery = "DELETE FROM {$dbprefix}product_categories WHERE prdid = :prdid";
        $deleteStmt  = $dbh->prepare($deleteQuery);
        $deleteStmt->bindParam(':prdid', $productId, PDO::PARAM_INT);
        $deleteStmt->execute();

        if (!empty($categories) && is_array($categories)) {
            $insertQuery = "INSERT INTO {$dbprefix}product_categories (prdid, catid) VALUES (:prdid, :catid)";
            $insertStmt  = $dbh->prepare($insertQuery);
            $insertStmt->bindParam(':prdid', $productId, PDO::PARAM_INT);

            foreach ($categories as $catid) {
                $catid = (int)$catid;
                $insertStmt->bindParam(':catid', $catid, PDO::PARAM_INT);
                $insertStmt->execute();
            }
        }

        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo 'Error updating categories.';
        // error_log($e->getMessage());
    }

    // Permalink & SEO
    $pagePermalink     = $_POST["permalink"]      ?? "";
    $pageSEOtitle      = $_POST["SEOtitle"]       ?? "";
    $pageSEOkeywords   = $_POST["SEOkeywords"]    ?? "";
    $pageSEOdescription = $_POST["SEOdescription"] ?? "";
    $pageSEOnoIndex    = isset($_POST["SEOnoIndex"]) ? 1 : 0;

    $posttype    = "product";
    $fallbackUrl = !empty($productName) ? $productName : $productId;

    $saveSuccess = savePermalink(
        $pagePermalink,
        $productId,
        $posttype,
        $pageSEOtitle,
        $pageSEOdescription,
        $pageSEOdescription,
        $pageSEOnoIndex,
        $fallbackUrl
    );

    // Redirect tilbage til edit-view
    header("Location: /admin/products.php?id=" . urlencode((string)$productId) . "&action=edit");
    exit;
}

// --------------------------------------------------
// ADD PRODUCT – bare vis tom view
// --------------------------------------------------

if ($action === "add") {
    $smartyTemplateFile = "productsView.tpl";
}

// --------------------------------------------------
// Load template functions og display
// --------------------------------------------------

include_once dirname(__FILE__) . '/inc/templateFunctions.php';

$smarty->display($smartyTemplateFile);
