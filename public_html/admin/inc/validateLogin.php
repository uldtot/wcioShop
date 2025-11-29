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

    // Login-form submission
    if ($isLoginPage && isset($_POST["adminEmail"], $_POST["adminPassword"])) {

        $adminEmail = $_POST["adminEmail"];
        $adminPassword = $_POST["adminPassword"];

        // Hent brugeren baseret på email
        $stmt = $dbh->prepare("
            SELECT * FROM {$dbprefix}admin
            WHERE adminEmail = :email
            LIMIT 1
        ");

        $stmt->execute([
            "email" => $adminEmail
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Tjek password
        if ($user && password_verify($adminPassword, $user["adminPassword"])) {

            $_SESSION["loggedInAdmin"] = $user["id"];

            header("Location: /admin/");
            exit;
        }

        // Invalid login → show login again
        $smarty->display("login.tpl");
        exit;
    }

    $smarty->display("login.tpl");
    exit;
}


// --------------------------------------------------------------------------------
// 2) Bruger ER logget ind → vi viser alle andre sider end login.tpl
// --------------------------------------------------------------------------------

if ($isLoginPage) {
    header("Location: /admin/");
    exit;
}

