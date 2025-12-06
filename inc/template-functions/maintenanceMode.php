<?php
$pluginSlug =  "maintenancemode";
$pluginSettingMainGroup = "Maintenance mode";

// Load all shop settings from databse
$stmt = $dbh->prepare("SELECT columnName,columnValue FROM {$dbprefix}settings WHERE settingMainGroup = :settingMainGroup");
$result = $stmt->execute(array(
      "settingMainGroup" => $pluginSettingMainGroup,
));
while($setting = $stmt->fetch( PDO::FETCH_ASSOC )) {

      // Assign values to be used in files
      $_SETTING[$pluginSlug.$setting['columnName']] = $setting['columnValue'];

}

$output = "";

if($_SETTING[$pluginSlug."Active"] == "1") {
$output = "<div style=\"    text-align: center;
    font-weight: bold;
    margin: 0px;
    font-size: 16px;
    position: relative;
    width: 100%;
    background: #000;
    color: #fff;
    z-index: 1;
    margin-bottom: -15px;\">".$_SETTING[$pluginSlug."Message"]."</div>";
}
$smarty->assign("maintenanceMode", $output);
?>
