<?php

//Logout
if(isset($_GET["logout"])) {
      // Destroy any sessions to make sure any data is removed
      session_destroy();

      // If no one is logged in, we send them to login
      header("Location: /admin/login/");
}

if(isset($_SESSION["loggedInAdmin"])) {
      $loggedInAdmin = $_SESSION["loggedInAdmin"];
} else {
      $loggedInAdmin = "";
}

// Login
if($loggedInAdmin == "" && $smartyTemplateFile == "login.tpl") {

      // If there is a new login, then check it before doing anything else
      if( isset($_POST["adminEmail"]) && isset($_POST["adminPassword"])) {

            $adminEmail = $_POST["adminEmail"];
            $adminPassword = $_POST["adminPassword"];

            // Fetch the admin user
            $stmt = $dbh->prepare("SELECT * FROM wcio_se_admin WHERE adminEmail = :adminEmail AND adminPassword = :adminPassword LIMIT 1");
            $result = $stmt->execute(array(
                  "adminEmail" => $adminEmail,
                  "adminPassword" => sha1($adminPassword)
            ));
            $resultData = $stmt->fetchAll( PDO::FETCH_ASSOC );

            if(count($resultData) == "1") {

                  $_SESSION["loggedInAdmin"] = $resultData["0"]["id"];
                  header("Location: /admin/");
            }

      } else {
            // Destroy any sessions to make sure any data is removed
            session_destroy();

      }

} else if($loggedInAdmin != "") { // Just making sure there is a session for this incase someone changes something somewhere.


       // We need to load all functions etc that are used in template files for admin. DO NOT CACHE!
      // Load template functions
      include(dirname(__FILE__) . '/templateFunctions.php');

} else {
      // This should never be valid...
      session_destroy();
      $smartyTemplateFile = "404.tpl";
}
?>
