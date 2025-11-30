<?php

$wcioShopAdminSettingsMenu = array();

$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}settings WHERE columnName = 'wcioShopAdminSettingsMenu' AND settingMainGroup != 'Store settings' ORDER BY settingMainGroup");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        $url = preg_replace('/\s+/', '', $data['settingMainGroup']);

        $wcioShopAdminSettingsMenu[] = array
        (
              "id" => $data['id'],
              "columnNiceName" => $data['settingMainGroup'],
              "url" => $data['columnValue'],
        );
  }

$smarty->assign("wcioShopAdminSettingsMenu", $wcioShopAdminSettingsMenu);
?>
