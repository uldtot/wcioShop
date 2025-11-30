<?php

$wcioShopAdminOrders = array();

$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}porders ORDER BY id DESC");
$result = $stmt->execute();

while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {

      $wcioShopAdminOrders[] = array(
            "id" => $data['id'],
            "orderId" => $data['cart_id'],
            "timestamp" => $data['timestamp'],
            "orderStatus" => $data['cart_status'],
            "firstname" => $data['firstname'],
            "lastname" => $data['lastname'],
            "total" => $data['cart_total'],
      );
}

$smarty->assign("wcioShopAdminOrders", $wcioShopAdminOrders);
