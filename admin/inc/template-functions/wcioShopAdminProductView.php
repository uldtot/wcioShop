<?php

$cart_id = $_GET["id"] ?? "";
if ($cart_id == "") {
      header("Location: /admin/orders/");
}


$stmt = $dbh->prepare("SELECT * FROM wcio_se_porders WHERE cart_id = :cart_id LIMIT 1");
$result = $stmt->execute(array(
      "cart_id" => $cart_id,
));

$data = $stmt->fetch(PDO::FETCH_ASSOC);

$wcioShopAdminOrders = array();

foreach ($data as $key => $value) {

      $wcioShopAdminOrders[$key] = $value;
}


// Products for order
$wcioShopAdminOrdersViewProducts = array();
$wcioShopAdminOrdersViewProductsShippingWeight = "0";
$wcioShopAdminOrdersViewProductsVat = "0";
$wcioShopAdminOrdersViewProductsTotal = "0";

$stmt = $dbh->prepare("SELECT * FROM wcio_se_corders WHERE cart_id = :cart_id");
$result = $stmt->execute(array(
      "cart_id" => $cart_id,
));

while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

      // Getting featured image
      $attachmentStmt = $dbh->prepare("SELECT * FROM wcio_se_attachments WHERE attachmentType = 'productFeaturedImage' AND attachmentPostId = :id LIMIT 1");
      $result = $attachmentStmt->execute(array(
            "id" => $data["prd_id"],
      ));
      $attachmentData = $attachmentStmt->fetch(PDO::FETCH_ASSOC);
      $image = "noimage.png";
      if ($attachmentStmt->fetchColumn()) {
            if (file_exists(dirname(__FILE__) . "../../uploads/" . $attachmentData["attachmentValue"] . "")) {
                  $image = $attachmentData["attachmentValue"];
            }
      }

      $wcioShopAdminOrdersViewProducts[] = array(
            'prdid' => $data['prd_id'],
            'name' => $data['prd_name'],
            'amount' => $data['prd_amount'],
            'price' => $data['prd_price'],
            'weight' => $data['prd_weight'],
            'image' => $image,
            'attribute' => $data['prd_attribute'],
            'vat' => $data['prd_vat'],
      );
      $wcioShopAdminOrdersViewProductsShippingWeight += (int)$data['prd_weight'];
      $wcioShopAdminOrdersViewProductsVat += $data['prd_price'] * $data['prd_vat'] / 100;
      $wcioShopAdminOrdersViewProductsTotal += $data['prd_amount'] * $data['prd_price'];
}

$smarty->assign("wcioShopAdminOrdersView", $wcioShopAdminOrders);
$smarty->assign("wcioShopAdminOrdersViewProducts", $wcioShopAdminOrdersViewProducts);
$smarty->assign("wcioShopAdminOrdersViewProductsShippingWeight", $wcioShopAdminOrdersViewProductsShippingWeight);
$smarty->assign("wcioShopAdminOrdersViewProductsVat", $wcioShopAdminOrdersViewProductsVat);
$smarty->assign("wcioShopAdminOrdersViewProductsTotal", $wcioShopAdminOrdersViewProductsTotal);
