<?php
// Starting session if not
if (session_status() === PHP_SESSION_NONE) {
      session_start();
 }

//Logout
if(isset($_GET["logout"]) ) {
      // Destroy any sessions to make sure any data is removed
      session_destroy();

      // If no one is logged in, we send them to login
            // Display the page and all its functions
            $smartyTemplateFile = "login.tpl";
            $smarty->display($smartyTemplateFile);
}

$loggedInAdmin = $_SESSION["loggedInAdmin"] ?? "";

// IF we are trying to login
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
                  // Display the page and all its functions
                  $smartyTemplateFile = "login.tpl";
      $smarty->display($smartyTemplateFile);
      }

} else if($loggedInAdmin == "" ){ // Just making sure there is a session for this incase someone changes something somewhere.

      // Display the page and all its functions
      $smartyTemplateFile = "login.tpl";
      $smarty->display($smartyTemplateFile);
      



} 
?>
