<?php

 $get_id = $_SETTING["SEOpermalinkData"]["postId"];

    $displayCategoryProducts = array();


        $stmt = $dbh->prepare("SELECT prdid FROM {$dbprefix}product_categories WHERE catid=:get_id");
        $result = $stmt->execute(array(
            ":get_id" => $get_id
        ));
   

        $query_products_count = "";
        while ($dataCategoryProducts = $stmt->fetch(PDO::FETCH_ASSOC))
        {

            $query_products = $dbh->prepare("SELECT * FROM {$dbprefix}products WHERE id=:prdid AND active = 1");
            $result = $query_products->execute(array(
                ":prdid" => $dataCategoryProducts['prdid']
            ));


            while ($dataProducts = $query_products->fetch(PDO::FETCH_ASSOC))
            {
                

				// Getting permlink data
				$permalinkStmt = $dbh->prepare("SELECT * FROM {$dbprefix}permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
				$result = $permalinkStmt->execute(array(
					"id" => $dataProducts['id'],
				));
				$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

				// Getting featured image
				$attachmentStmt = $dbh->prepare("SELECT * FROM {$dbprefix}attachments WHERE attachmentType = 'primary' AND attachmentPostId = :id LIMIT 1");
				$result = $attachmentStmt->execute(array(
					"id" => $dataProducts['id'],
				));
				$attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);

	if(!$attachmentData["attachmentValue"] || !file_exists(dirname(__FILE__)."/../../uploads/".$attachmentData["attachmentValue"]."")) {
		$image = "noimage.png";
	} else {
		$image = $attachmentData["attachmentValue"];
	}
				
				// Get prices from _productmeta
                $priceStmt = $dbh->prepare("
                    SELECT columnName, columnValue 
                    FROM {$dbprefix}productmeta 
                    WHERE productId = :id 
                      AND (columnName LIKE '%salePrice_%' OR columnName LIKE '%price_%')
                ");
                $priceStmt->execute([
                    "id" => $dataProducts['id'],
                ]);
                
                $priceData = $priceStmt->fetchAll(PDO::FETCH_ASSOC);
                
                          // Byg array med columnName som key
                $prices = [];
                foreach ($priceData as $row) {
                    $prices[$row['columnName']] = $row['columnValue'] + 0; // +0 for at caste til int/float hvis muligt
                }
                
                // Get other from product meta
                $otherStmt = $dbh->prepare("
                    SELECT *
                    FROM {$dbprefix}productmeta 
                    WHERE productId = :id
                ");
                $otherStmt->execute([
                    "id" => $dataProducts['id'],
                ]);
                
                $otherData = $otherStmt->fetchAll(PDO::FETCH_ASSOC);
                
                $allData = [];
                foreach ($otherData as $row) {
                    $allData[$row['columnName']] = $row['columnValue'];
                }

                $displayCategoryProducts[] = array(
                    'prdid' => $dataProducts['id'],
                    'name' => $dataProducts['name'],
                    'price' => $prices,
			  'image' => $image,
                    'discount' => $allData['discount'],
                    'excerpt' => $allData['excerpt'],
                    'stock' => $allData['stock'],
			  'url' => $permalinkData["url"],
                );

            }
            //while



        }
        //while

    $smarty->assign("displayCategoryProducts", $displayCategoryProducts);
