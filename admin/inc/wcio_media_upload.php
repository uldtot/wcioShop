<?php
// Load index for smarty functions and login validation
include(dirname(__FILE__) . '/../index.php');

// Hent den aktuelle mappe fra formularen
$currentFolder = $_POST['folderPath'] ?? '';

if (strpos($currentFolder, '/uploads') === 0) { 

// Hvis filen uploades korrekt
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    // Validér filstørrelse (maks 5MB f.eks.)
    $maxFileSize = 1000 * 1024 * 1024; // 5MB
    if ($_FILES['file']['size'] > $maxFileSize) {
        die("Error: File is too large."); 
    }

    // Angiv upload-mappen og filnavn
    $uploadPath = ABSPATH.'' . $currentFolder . '/' . basename($_FILES['file']['name']);

    // Hvis filen allerede findes, tilføj et suffix for at undgå overskrivning
    if (file_exists($uploadPath)) {
        $uploadPath = ABSPATH.'' . $currentFolder . '/' . time() . '_' . basename($_FILES['file']['name']);
    }

    // Udfør upload
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
  
        // Success - Redirect til den aktuelle mappe på wcio_media.php
        header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder));
        exit;
    } else {
        
        header("Location: /admin/wcio_media.php?folder=" . urlencode($currentFolder)."&err=file-upload-failed.");
        exit;
    }
}
}
?>
