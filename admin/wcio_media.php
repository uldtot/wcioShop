<?php
$smartyTemplateFile = "media.tpl";

// Load index for smarty functions and login validation
include(dirname(__FILE__) . '/index.php');

// Load functions for this file...
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

// Funktion til at hente både mapper og filer fra en given mappe
function getFilesAndFolders($folderPath) {
    $filesAndFolders = [
        'folders' => [],
        'files' => []
    ];

    // Check om mappen eksisterer
    if (is_dir($folderPath)) {
        // Åbn mappen og læs indholdet
        if ($handle = opendir($folderPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $fullPath = $folderPath . DIRECTORY_SEPARATOR . $entry;

                    if (is_dir($fullPath)) {
                        // Hvis det er en mappe, tilføj til folder-listen
                        $filesAndFolders['folders'][] = $entry;
                    } else {
                        // Hvis det er en fil, tilføj til fil-listen
                        $fileSize = filesize($fullPath);
                        $filesAndFolders['files'][] = [
                            'name' => $entry,
                            'size' => formatFileSize($fileSize),
                            'path' => $fullPath // Send filstien til Smarty
                        ];
                    }
                }
            }
            closedir($handle);
        }
    }
    return $filesAndFolders;
}


// Hent den valgte mappe fra URL (hvis der er en)
$currentFolder = $_GET['folder'] ?? '';

// Funktion til at hente mapper og filer i den valgte mappe
$folderPath = dirname(__FILE__) . '/../' . $currentFolder;
$filesAndFolders = getFilesAndFolders($folderPath);

// Hvis der ikke er valgt mappe, vis "Root"
if (empty($currentFolder)) {
    $currentFolder = '';
    $parentFolder = ''; // Root har ikke en overordnet mappe
} else {
    // Hvis en mappe er valgt, brug explode til at få den forrige mappe
    $parentFolder = implode('/', array_slice(explode('/', $currentFolder), 0, -1));
}

// Send data til Smarty
$smarty->assign('filesAndFolders', $filesAndFolders);
$smarty->assign('currentFolder', $currentFolder);
$smarty->assign('parentFolder', $parentFolder);  // Send parentFolder til Smarty

// Send data til Smarty for at vise om vi er i 'uploads' mappen
$smarty->assign('isUploadsFolder', $currentFolder === 'uploads');

// Load template functions
include(dirname(__FILE__) . '/inc/wcio_templateFunctions.php');

// 
$smarty->registerPlugin('modifier', 'startswith', function($string, $substring) {
    return strpos($string, $substring) === 0;
});



// Display the page and all its functions
$smarty->display($smartyTemplateFile);
