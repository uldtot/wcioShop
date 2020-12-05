<?php

$wcioShopAdminSettings = array();

$stmt = $dbh->prepare("SELECT * FROM wcio_se_settings WHERE settingMainGroup = 'Store settings' ORDER BY settingSecondaryGroup,settingOrder,columnNiceName");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        $settingMainGroup = $data['settingMainGroup'];
        $settingSecondaryGroup = $data['settingSecondaryGroup'];

        $wcioShopAdminSettings[$settingMainGroup][$settingSecondaryGroup][] = array
        (
              "id" => $data['id'],
              "autoload" => $data['autoload'],
              "settingOrder" => $data['settingOrder'],
              "columnName" => $data['columnName'],
              "columnNiceName" => $data['columnNiceName'],
              "settingMainGroup" => $settingMainGroup,
              "settingSecondaryGroup" => $settingSecondaryGroup,
              "columnType" => $data['columnType'],
              "columnValue" => $data['columnValue'],
              "columnDescription" => $data['columnDescription'],
        );
  }

$smarty->assign("wcioShopAdminSettings", $wcioShopAdminSettings);
?>
