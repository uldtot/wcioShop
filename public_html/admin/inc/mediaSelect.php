<?php
/** Absolute path to the store directory. */
if (!defined('ABSPATH')) {
      define('ABSPATH', __DIR__ . '/../../');
}

// Load index for smarty functions and login valitation
include(dirname(__FILE__) . '/../../inc/db.php');
include(dirname(__FILE__) . '/validateLogin.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$fileName = $_POST['fileName'] ?? '';
$currentFolder = $_POST['currentFolder'] ?? '';
$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

if (!$fileName || !$currentFolder || !$id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing file data']);
    exit;
}

// Find ud af, hvilken attachmentType vi skal bruge
$typesMap = [
    'makePrimary' => 'primary',
    'removePrimary' => 'primary',
    'makeGallery' => 'gallery',
    'removeGallery' => 'gallery',
    'makeAdditional' => 'additional',
    'removeAdditional' => 'additional',
];

// Tjek om action er gyldig
if (!isset($typesMap[$action])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    exit;
}

$attachmentType = $typesMap[$action];

// Find det tilknyttede post ID ud fra folder-navnet
// Fx. hvis folder er /uploads/23, så er 23 = post ID
$attachmentPostId = $id;

try {
    if (str_starts_with($action, 'make')) {
        // Først, slet gammel hvis den er af typen 'primary' (unik)
        if ($attachmentType === 'primary') {
            $deleteStmt = $dbh->prepare("DELETE FROM {$dbprefix}attachments WHERE attachmentPostId = :postId AND attachmentType = 'primary'");
            $deleteStmt->execute([':postId' => $attachmentPostId]);
        }

        // Undgå duplicates: slet eksisterende samme fil/type
        $deleteDupStmt = $dbh->prepare("DELETE FROM {$dbprefix}attachments WHERE attachmentPostId = :postId AND attachmentType = :type AND attachmentValue = :value");
        $deleteDupStmt->execute([
            ':postId' => $attachmentPostId,
            ':type' => $attachmentType,
            ':value' => $fileName,
        ]);

        // Indsæt ny attachment
        $insertStmt = $dbh->prepare("INSERT INTO {$dbprefix}attachments (attachmentType, attachmentPostId, attachmentValue, attachmentOrder) VALUES (:type, :postId, :value, 0)");
        $insertStmt->execute([
            ':type' => $attachmentType,
            ':postId' => $attachmentPostId,
            ':value' => $fileName,
        ]);

        echo json_encode(['status' => 'success', 'message' => "File added as $attachmentType"]);
    } else {
        // Slet filen af given type
        $deleteStmt = $dbh->prepare("DELETE FROM {$dbprefix}attachments WHERE attachmentPostId = :postId AND attachmentType = :type AND attachmentValue = :value");
        $deleteStmt->execute([
            ':postId' => $attachmentPostId,
            ':type' => $attachmentType,
            ':value' => $fileName,
        ]);

        echo json_encode(['status' => 'success', 'message' => "File removed from $attachmentType"]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error', 'details' => $e->getMessage()]);
}
