<?php
include(dirname(__FILE__) . '/../index.php');

// Allowed base folder
$baseDir = realpath(dirname(__FILE__) . '/../../uploads');

// Hent den aktuelle mappe
$currentFolder = $_POST['folderPath'] ?? '';

// Normaliser stien (fjern ../ osv)
$fullCurrentPath = realpath($baseDir . '/' . ltrim($currentFolder, '/'));

if ($fullCurrentPath === false || strpos($fullCurrentPath, $baseDir) !== 0) {
    die("Error: Invalid folder path.");
}

// Rens foldernavn
$newFolderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folderName']);
if ($newFolderName === '') {
    die("Error: Invalid folder name.");
}

$newFolderPath = $fullCurrentPath . '/' . $newFolderName;

// Opret mappe
if (!is_dir($newFolderPath)) {
    if (mkdir($newFolderPath, 0777, true)) {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder));
        exit;
    } else {
        die("Error: Unable to create folder.");
    }
} else {
    die("Error: Folder already exists.");
}
?>
