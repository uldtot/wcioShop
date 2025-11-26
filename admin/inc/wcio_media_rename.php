<?php
// Load index for smarty functions and login validation
include(dirname(__FILE__) . '/../index.php');

// Rename file or folder
if (isset($_POST['newName']) && isset($_POST['fileName']) && isset($_POST['currentFolder'])) {
   
    $newName = $_POST['newName'];
    $fileName = $_POST['fileName'];
    $currentFolder = $_POST['currentFolder'];

    // Path to the current file or folder
    $fullPath = ABSPATH."$currentFolder/$fileName";  // Use realpath to get the absolute path
    $newPath = ABSPATH."$currentFolder/$newName";

    // Check if the current folder is under /uploads
    if (strpos($currentFolder, '/uploads') !== 0) {
        // If it's not in /uploads or its subfolders, redirect to the current folder with an error code
        header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder) . "&err=not-allowed-path");
        exit;
    }

    // Check if the file exists before renaming
    if (!file_exists($fullPath)) {
        // If the file or folder doesn't exist, redirect with an error code
        header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder) . "&err=file-not-found");
        exit;
    }

    // If it's a folder
    if (is_dir($fullPath)) {
        if (!rename($fullPath, $newPath)) {
            // If renaming the folder fails, redirect with an error code
            header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder) . "&err=folder-rename-failed");
            exit;
        }
    } 
    // If it's a file
    elseif (file_exists($fullPath)) {
        if (!rename($fullPath, $newPath)) {
            // If renaming the file fails, redirect with an error code
            header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder) . "&err=file-rename-failed");
            exit;
        }
    }

    // Redirect back after successful renaming
    header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
    exit;
} 
?>
