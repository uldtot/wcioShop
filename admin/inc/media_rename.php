<?php
// Load index for smarty functions and login validation
include_once dirname(__FILE__) . '/../index.php';

// Base uploads mappe
$baseDir = realpath(ABSPATH . 'uploads');

if (
    isset($_POST['newName']) &&
    isset($_POST['fileName']) &&
    isset($_POST['currentFolder'])
) {
    $currentFolder = $_POST['currentFolder'];
    $fileName = $_POST['fileName'];
    $newName = $_POST['newName'];

    // Normalisér currentFolder og valider tilgang
    $fullCurrentPath = realpath($baseDir . '/' . ltrim($currentFolder, '/'));

    if ($fullCurrentPath === false || strpos($fullCurrentPath, $baseDir) !== 0) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=invalid-folder");
        exit;
    }

    // Saniter fil-/mappenavne
    $fileName = basename($fileName);
    $fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $fileName);

    $newName = basename($newName);
    $newName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $newName);

    if ($fileName === '' || $newName === '') {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=invalid-name");
        exit;
    }

    // Fulde stier
    $fullPath = $fullCurrentPath . '/' . $fileName;
    $newPath = $fullCurrentPath . '/' . $newName;

    // Kontrollér at fil eller mappe eksisterer
    if (!file_exists($fullPath)) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=file-not-found");
        exit;
    }

    // Brug realpath til at validere den oprindelige sti
    $realOriginal = realpath($fullPath);
    if ($realOriginal === false || strpos($realOriginal, $baseDir) !== 0) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=invalid-path");
        exit;
    }

    // Undgå overskrivning af eksisterende filer
    if (file_exists($newPath)) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=target-exists");
        exit;
    }

    // Omdøb
    if (!rename($fullPath, $newPath)) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=rename-failed");
        exit;
    }

    // Success
    header("Location: /admin/media.php?folder=" . urlencode($currentFolder));
    exit;
}
?>
