<?php
// Load index for smarty functions and login validation
include dirname(__FILE__) . '/../index.php';

// Definér base uploads-mappen
$baseDir = realpath(dirname(__FILE__) . '/../../uploads');

// Hent input
$currentFolder = $_POST['currentFolder'] ?? '';
$itemToDelete = $_POST['filePath'] ?? '';
$isFolderToDelete = isset($_POST['deleteFolder']) && $_POST['deleteFolder'] === '1';

// Hvis input mangler, redirect
if (empty($itemToDelete) || empty($currentFolder)) {
    header("Location: /admin/media.php?folder=" . urlencode($currentFolder));
    exit;
}

// Normalisér og valider currentFolder
$fullCurrentPath = realpath($baseDir . '/' . ltrim($currentFolder, '/'));
if ($fullCurrentPath === false || strpos($fullCurrentPath, $baseDir) !== 0) {
    die("Error: Invalid folder path.");
}

// Rens filnavn (basename er ok, men vi sanitiserer også)
$itemName = basename($itemToDelete);
$itemName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $itemName);

if ($itemName === '') {
    die("Error: Invalid filename.");
}

// Konstruér og valider fuld sti til filen/mappen
$itemPath = realpath($fullCurrentPath . '/' . $itemName);

// Hvis filen ikke findes, realpath bliver false – men vi tillader rmdir (som kræver ikke realpath)
if ($itemPath !== false && strpos($itemPath, $baseDir) !== 0) {
    die("Error: Invalid delete path.");
}

// Håndtér sletning
if (is_file($itemPath)_
