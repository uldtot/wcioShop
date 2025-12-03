<?php


function formatFileSize($bytes)
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }

    // Default back to bytes
    return $bytes . ' bytes';
}


// Funktion til at hente både mapper og filer fra en given fysisk mappe
// $folderPath = fysisk sti, $webFolder = web-sti (fx /uploads/2025)
function getFilesAndFolders($folderPath, $webFolder)
{
    $filesAndFolders = [
        'folders' => [],
        'files' => []
    ];

    if (is_dir($folderPath) && $handle = opendir($folderPath)) {

        while (false !== ($entry = readdir($handle))) {
            if ($entry === "." || $entry === "..") {
                continue;
            }

            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($fullPath)) {
                $filesAndFolders['folders'][] = $entry;
            } else {
                $fileSize = @filesize($fullPath);
                $filesAndFolders['files'][] = [
                    'name' => $entry,
                    'size' => formatFileSize($fileSize !== false ? $fileSize : 0),
                    // Web-sti, ikke server-sti
                    'path' => rtrim($webFolder, '/') . '/' . $entry
                ];
            }
        }
        closedir($handle);
    }

    return $filesAndFolders;
}


// --------------------------------------------------
// Media-håndtering for products (SIKKER VERSION)
// --------------------------------------------------

// ABSPATH bør være din webroot
$docRoot     = realpath(ABSPATH);
$uploadsRoot = realpath($docRoot . '/uploads');

// Fald tilbage hvis konfigurationen er forkert
if ($docRoot === false || $uploadsRoot === false) {
    die('Configuration error: Invalid ABSPATH or uploads directory.');
}

// Læs folder-parameter (kan være "uploads" eller "/uploads/2025")
$currentFolderParam = $_GET['folder'] ?? '/uploads';

// Normalisér til en web-sti der altid starter med /
$currentFolder = '/' . ltrim($currentFolderParam, '/');

// Fysisk sti til mappen
$folderPathReal = realpath($docRoot . $currentFolder);

// Hvis stien er ugyldig eller udenfor /uploads → tving til /uploads
if ($folderPathReal === false || strpos($folderPathReal, $uploadsRoot) !== 0) {
    $currentFolder  = '/uploads';
    $folderPathReal = $uploadsRoot;
}

// Hent filer og mapper
$filesAndFolders = getFilesAndFolders($folderPathReal, $currentFolder);

// Parent folder (kun indenfor /uploads)
if ($currentFolder === '/uploads') {
    $parentFolder = ''; // ingen overmappe
} else {
    $parts = explode('/', trim($currentFolder, '/')); // fx ['uploads','2025','01']
    if (count($parts) > 1) {
        array_pop($parts); // fjern sidste segment
        $parentFolder = '/' . implode('/', $parts);

        // sikkerhed: sikre at parentFolder også er under /uploads
        if (strpos($parentFolder, '/uploads') !== 0) {
            $parentFolder = '/uploads';
        }
    } else {
        $parentFolder = '/uploads';
    }
}

// Send media-data til Smarty
$smarty->assign('filesAndFolders', $filesAndFolders ?? "");
$smarty->assign('currentFolder', $currentFolder ?? "");
$smarty->assign('parentFolder', $parentFolder ?? "");
$smarty->assign('currentId', $pageId ?? "");

// er vi i uploads-roden?
$smarty->assign('isUploadsFolder', $currentFolder === '/uploads');
