<?php

$wcioShopAdminMaintenanceModeSettings = array();

$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}settings WHERE settingMainGroup = 'Maintenance mode' AND columnName != 'wcioShopAdminSettingsMenu' ORDER BY settingSecondaryGroup,settingOrder,columnNiceName");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        $settingMainGroup = $data['settingMainGroup'];
        $settingSecondaryGroup = $data['settingSecondaryGroup'];

        $wcioShopAdminMaintenanceModeSettings[$settingMainGroup][$settingSecondaryGroup][] = array
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

$smarty->assign("wcioShopAdminMaintenanceModeSettings", $wcioShopAdminMaintenanceModeSettings);
?>
