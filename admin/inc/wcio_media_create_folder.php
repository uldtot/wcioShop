<?php
// Load index for smarty functions and login validation
include(dirname(__FILE__) . '/../index.php');

// Hent den aktuelle mappe fra formularen
$currentFolder = $_POST['folderPath'] ?? '';

if (strpos($currentFolder, '/uploads') === 0) { 

// Hent det nye mappenavn fra formularen og fjern potentielle skadelige tegn
$newFolderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folderName']);

// Hvis mappen ikke er tom og findes, opret den
$newFolderPath = dirname(__FILE__) . '/../../' . $currentFolder . '/' . $newFolderName;

if (!is_dir($newFolderPath)) {
    if (mkdir($newFolderPath, 0777, true)) {
        // Success - Redirect til den aktuelle mappe på wcio_media.php
        header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
        exit;
    } else {
        die("Error: Unable to create folder.");
    }
} else {
    die("Error: Folder already exists.");
}

}
?>
