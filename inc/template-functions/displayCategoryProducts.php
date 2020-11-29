<?php

 $get_id = $_SETTING["SEOpermalinkData"]["postId"];

    $displayCategoryProducts = array();

    $stmt = $dbh->prepare("SELECT * FROM wcio_se_categories WHERE id=:get_id");
    $result = $stmt->execute(array(
        ":get_id" => $get_id
    ));
    $SeCategoryCount = $stmt->rowCount();

    if ($SeCategoryCount > '0')
    {
        $data_category = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $dbh->prepare("SELECT prdid FROM wcio_se_product_categories WHERE catid=:get_id");
        $result = $stmt->execute(array(
            ":get_id" => $get_id
        ));

        $query_products_count = "";
        while ($data_category_products = $stmt->fetch(PDO::FETCH_ASSOC))
        {

            $query_products = $dbh->prepare("SELECT * FROM wcio_se_products WHERE id=:prdid");
            $result = $query_products->execute(array(
                ":prdid" => $data_category_products['prdid']
            ));

            while ($data_products = $query_products->fetch(PDO::FETCH_ASSOC))
            {

                $displayCategoryProducts[] = array(
                    'prdid' => $data_products['id'],
                    'name' => $data_products['name'],
                    'price' => $data_products['price'],
                    'file2' => $data_products['file2'],
                    'discount' => $data_products['discount'],
                    'shorttext' => $data_products['shorttext'],
                    'stock' => $data_products['stock'],
                    'url' => $data_products['name']
                );

            }
            //while



        }
        //while

    }

    $smarty->assign("displayCategoryProducts", $displayCategoryProducts);
