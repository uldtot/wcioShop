<?php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle logout
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: /admin/login");
    exit;
}

// Check login session
$loggedInAdmin = $_SESSION["loggedInAdmin"] ?? null;

// Er vi på login-siden?
$isLoginPage = ($smartyTemplateFile ?? "") === "login.tpl";

// --------------------------------------------------------------------------------
// 1) Bruger er IKKE logget ind → kun login.tpl må vises
// --------------------------------------------------------------------------------
if (!$loggedInAdmin) {

    // Hvis det er login-form submission
    if ($isLoginPage && isset($_POST["adminEmail"], $_POST["adminPassword"])) {

        $adminEmail = $_POST["adminEmail"];
        $adminPassword = sha1($_POST["adminPassword"]);

        $stmt = $dbh->prepare("
            SELECT * FROM wcio_se_admin 
            WHERE adminEmail = :email 
            AND adminPassword = :pass 
            LIMIT 1
        ");

        $stmt->execute([
            "email" => $adminEmail,
            "pass"  => $adminPassword
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $_SESSION["loggedInAdmin"] = $result["id"];
            header("Location: /admin/");
            exit;
        }

        // Invalid login → show login again
        $smarty->display("login.tpl");
        exit;
    }

    // Hvis ingen login-form vises → redirect til login
    $smarty->display("login.tpl");
    exit;
}

// --------------------------------------------------------------------------------
// 2) Bruger ER logget ind → vi viser alle andre sider end login.tpl
// --------------------------------------------------------------------------------

if ($isLoginPage) {
    // Hvis en logget ind bruger prøver at se login → redirect
    header("Location: /admin/");
    exit;
}

// ALT ER OK – ADMIN ER LOGGET IND
// Fortsæt med normal admin-side

?>
