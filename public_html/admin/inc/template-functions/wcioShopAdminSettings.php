<?php

$wcioShopAdminSettings = array();

// Check if we need specific settings of just default
$mainGroup = $_GET["setting"] ?? "";

if($mainGroup) {
    
    $stmt = $dbh->prepare("SELECT * FROM {$dbprefix}settings WHERE columnValue = :mainGroup AND columnName = 'wcioShopAdminSettingsMenu' LIMIT 1");
    $stmt->bindParam(':mainGroup', $mainGroup, PDO::PARAM_STR);
    $result = $stmt->execute();
    
    $dataMain = $stmt->fetch(PDO::FETCH_ASSOC);
    
    /*
    Array
    (
        [id] => 48
        [autoload] => 0
        [settingOrder] => 0
        [columnName] => wcioShopAdminSettingsMenu
        [columnNiceName] => Mintenance Mode
        [settingMainGroup] => Maintenance mode
        [settingSecondaryGroup] => Maintenance mode
        [columnType] => 
        [columnTypeData] => 
        [columnValue] => maintenancemode
        [columnDescription] => 
    )*/

}

// Now fetch data
$settingMainGroup = $dataMain["settingMainGroup"] ?? "Store settings";
$stmt = $dbh->prepare("SELECT * FROM {$dbprefix}settings WHERE settingMainGroup = :settingMainGroup AND columnName != 'wcioShopAdminSettingsMenu' ORDER BY settingSecondaryGroup,settingOrder,columnNiceName");
$stmt->bindParam(':settingMainGroup', $settingMainGroup, PDO::PARAM_STR);
$result = $stmt->execute();



  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        $settingMainGroup = $data['settingMainGroup'];
        $settingSecondaryGroup = $dataMain['settingSecondaryGroup'] ?? $data['settingSecondaryGroup'];

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
