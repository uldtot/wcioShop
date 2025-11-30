<?php
// Load index for smarty functions and login validation
include_once dirname(__FILE__) . '/../index.php';

// Base uploads mappe
$baseDir = realpath(ABSPATH . 'uploads');

// Hent currentFolder
$currentFolder = $_POST['folderPath'] ?? '';

// Normalisér sti og sikr at vi kun arbejder under uploads
$fullCurrentPath = realpath($baseDir . '/' . ltrim($currentFolder, '/'));
if ($fullCurrentPath === false || strpos($fullCurrentPath, $baseDir) !== 0) {
    die("Error: Invalid folder path.");
}

// Kontroller uploadet fil
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

    // Maks størrelse (1000 MB som i dit eksempel)
    $maxFileSize = 1000 * 1024 * 1024; 
    if ($_FILES['file']['size'] > $maxFileSize) {
        die("Error: File is too large.");
    }

    // Filnavn — rens for farlige tegn
    $origName = basename($_FILES['file']['name']);
    $cleanName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $origName);

    if ($cleanName === '') {
        die("Error: Invalid filename.");
    }

    // Forhindre double-extension angreb (.php.jpg → php-kode skjult)
    $allowedExtensions = ['jpg','jpeg','png','gif','svg','webp','pdf','txt','zip','csv','doc','docx','xls','xlsx'];
    $ext = strtolower(pathinfo($cleanName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExtensions)) {
        die("Error: File type not allowed.");
    }

    // Fuldt path til upload
    $uploadPath = $fullCurrentPath . '/' . $cleanName;

    // Undgå overskrivning – tilføj timestamp hvis navn findes
    if (file_exists($uploadPath)) {
        $uploadPath = $fullCurrentPath . '/' . time() . '_' . $cleanName;
    }

    // Upload filen
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {

        // Redirect back
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder));
        exit;
    } else {
        header("Location: /admin/media.php?folder=" . urlencode($currentFolder) . "&err=file-upload-failed");
        exit;
    }
}

?>
