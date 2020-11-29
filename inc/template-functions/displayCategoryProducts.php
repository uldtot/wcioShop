<?php

 $get_id = $_SETTING["SEOpermalinkData"]["postId"];

    $displayCategoryProducts = array();


        $stmt = $dbh->prepare("SELECT prdid FROM wcio_se_product_categories WHERE catid=:get_id");
        $result = $stmt->execute(array(
            ":get_id" => $get_id
        ));

        $query_products_count = "";
        while ($data_category_products = $stmt->fetch(PDO::FETCH_ASSOC))
        {

            $query_products = $dbh->prepare("SELECT id,name,price,discount,shorttext,stock FROM wcio_se_products WHERE id=:prdid");
            $result = $query_products->execute(array(
                ":prdid" => $data_category_products['prdid']
            ));

            while ($data_products = $query_products->fetch(PDO::FETCH_ASSOC))
            {


				// Getting permlink data
				$permalinkStmt = $dbh->prepare("SELECT * FROM wcio_se_permalinks WHERE postType = 'product' AND postId = :id LIMIT 1");
				$result = $permalinkStmt->execute(array(
					"id" => $data_products['id'],
				));
				$permalinkData = $permalinkStmt->fetch(PDO::FETCH_ASSOC);

				// Getting featured image
				$attachmentStmt = $dbh->prepare("SELECT * FROM wcio_se_attachments WHERE attachmentType = 'productFeaturedImage' AND attachmentPostId = :id LIMIT 1");
				$result = $attachmentStmt->execute(array(
					"id" => $data_products['id'],
				));
				$attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);

				if(!file_exists(dirname(__FILE__)."../../uploads/".$attachmentData["attachmentValue"]."")) {
					$image = "noimage.png";
				} else {
					$iamge = $attachmentData["attachmentValue"];
				}


                $displayCategoryProducts[] = array(
                    'prdid' => $data_products['id'],
                    'name' => $data_products['name'],
                    'price' => $data_products['price'],
			  'image' => $image,
                    'discount' => $data_products['discount'],
                    'shorttext' => $data_products['shorttext'],
                    'stock' => $data_products['stock'],
			  'url' => $permalinkData["url"],
                );

            }
            //while



        }
        //while


    $smarty->assign("displayCategoryProducts", $displayCategoryProducts);
