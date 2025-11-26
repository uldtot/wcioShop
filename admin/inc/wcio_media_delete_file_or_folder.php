<?php
// Load index for smarty functions and login validation
include(dirname(__FILE__) . '/../index.php');

// Hent den aktuelle mappe og filen
$currentFolder = $_POST['currentFolder'] ?? '';
$itemToDelete = $_POST['filePath'] ?? '';
$isFolderToDelete = isset($_POST['deleteFolder']) && $_POST['deleteFolder'] == '1';

// Hvis itemToDelete er tom eller mappen er ugyldig, afbryd
if (empty($itemToDelete) || empty($currentFolder)) {
    header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
    exit;
}

// Sikre, at vi kun arbejder med filer og mapper i den ønskede mappe
$itemToDelete = basename($itemToDelete); // Sikkerhedsforanstaltning mod directory traversal
$itemPath = dirname(__FILE__) . '/../../' . $currentFolder . '/' . $itemToDelete;

// Kontrollér om man forsøger at slette udenfor uploads mappen
$allowedFolder = '/uploads'; // Kun tillad sletning i uploads mappen

// Tjek om currentFolder starter med /uploads
if (strpos($currentFolder, $allowedFolder) !== 0) {
    // Hvis mappen ikke ligger under uploads, afbryd
    header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
    exit;
}

// Hvis det er en fil, slet den
if (is_file($itemPath)) {
    unlink($itemPath);
} elseif ($isFolderToDelete && is_dir($itemPath)) {
    // Hvis det er en mappe, slet den (kun hvis den er tom)
    rmdir($itemPath);
}

// Redirect tilbage til den mappe, man var i
header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
exit;
