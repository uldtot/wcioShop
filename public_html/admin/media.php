<?php

$smartyTemplateFile = "media.tpl";

// Load index for smarty functions and login validation
include_once(dirname(__FILE__) . '/index.php');

// Evt. action/id (hvis du bruger dem andetsteds)
$action = $_REQUEST["action"] ?? null;
$pageId = $_REQUEST["id"] ?? null;

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Funktion til at hente både mapper og filer fra en given fysisk mappe
// $folderPath = fysisk sti, $webFolder = web-sti (fx /uploads/2025)
function getFilesAndFolders($folderPath, $webFolder) {
    $filesAndFolders = [
        'folders' => [],
        'files' => []
    ];

    if (is_dir($folderPath)) {
        if ($handle = opendir($folderPath)) {
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
    }

    return $filesAndFolders;
}

// --------------------------------------------------
// SIKKER HÅNDTERING AF currentFolder
// --------------------------------------------------

// ABSPATH bør pege på webroot (som du allerede bruger i andre filer)
$docRoot     = realpath(ABSPATH);
$uploadsRoot = realpath($docRoot . '/uploads');

// Standard: start altid i /uploads
$currentFolderParam = $_GET['folder'] ?? '/uploads';

// Normalisér til en "web path" der starter med /
$currentFolder = '/' . ltrim($currentFolderParam, '/');

// Fysisk sti til den valgte mappe
$folderPathReal = realpath($docRoot . $currentFolder);

// Hvis stien er ugyldig eller ikke under /uploads, så fallback til /uploads
if ($folderPathReal === false || $uploadsRoot === false || strpos($folderPathReal, $uploadsRoot) !== 0) {
    $currentFolder = '/uploads';
    $folderPathReal = $uploadsRoot;
}

// Hent filer og mapper for den aktuelle mappe
$filesAndFolders = getFilesAndFolders($folderPathReal, $currentFolder);

// Parent folder (kun inden for /uploads)
if ($currentFolder === '/uploads') {
    // Root for mediehåndtering – ingen parent
    $parentFolder = '';
} else {
    // Split path: /uploads/år/måned → ['uploads','år','måned']
    $parts = explode('/', trim($currentFolder, '/'));
    if (count($parts) > 1) {
        array_pop($parts); // fjern sidste led
        $parentFolder = '/' . implode('/', $parts);

        // Hvis vi på en eller anden måde røg ud af uploads, nulstil
        if (strpos($parentFolder, '/uploads') !== 0) {
            $parentFolder = '/uploads';
        }
    } else {
        $parentFolder = '/uploads';
    }
}

// Smarty-assigns
$smarty->assign('filesAndFolders', $filesAndFolders);
$smarty->assign('currentFolder', $currentFolder);
$smarty->assign('parentFolder', $parentFolder);

// Er vi i uploads-roden?
$smarty->assign('isUploadsFolder', $currentFolder === '/uploads');

// Load template functions
include(dirname(__FILE__) . '/inc/templateFunctions.php');

// Modifier til startswith
$smarty->registerPlugin('modifier', 'startswith', function($string, $substring) {
    return strpos($string, $substring) === 0;
});

// Display template
$smarty->display($smartyTemplateFile);