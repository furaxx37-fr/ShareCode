<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Paste ID is required']);
    exit;
}

$pasteId = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM pastes WHERE paste_id = ?");
    $stmt->execute([$pasteId]);
    $paste = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$paste) {
        http_response_code(404);
        echo json_encode(['error' => 'Paste not found']);
        exit;
    }
    
    // Increment view count
    $updateStmt = $pdo->prepare("UPDATE pastes SET views = views + 1 WHERE paste_id = ?");
    $updateStmt->execute([$pasteId]);
    $paste['views']++;
    
    echo json_encode([
        'success' => true,
        'paste' => $paste
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to retrieve paste']);
}
?>
