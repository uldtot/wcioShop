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

                $displayCategoryProducts[] = array(
                    'prdid' => $data_products['id'],
                    'name' => $data_products['name'],
                    'price' => $data_products['price'],
                    'image' => $data_products['file2'],
                    'discount' => $data_products['discount'],
                    'shorttext' => $data_products['shorttext'],
                    'stock' => $data_products['stock'],
                    'url' => $data_products['name']
                );

            }
            //while



        }
        //while


    $smarty->assign("displayCategoryProducts", $displayCategoryProducts);
