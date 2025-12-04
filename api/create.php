<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['content']) || empty(trim($input['content']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Content is required']);
    exit;
}

$title = $input['title'] ?? 'Untitled';
$content = $input['content'];
$language = $input['language'] ?? 'text';

// Generate unique paste ID
do {
    $pasteId = generatePasteId();
    $stmt = $pdo->prepare("SELECT id FROM pastes WHERE paste_id = ?");
    $stmt->execute([$pasteId]);
} while ($stmt->fetch());

try {
    $stmt = $pdo->prepare("INSERT INTO pastes (paste_id, title, content, language) VALUES (?, ?, ?, ?)");
    $stmt->execute([$pasteId, $title, $content, $language]);
    
    echo json_encode([
        'success' => true,
        'paste_id' => $pasteId,
        'url' => "http://" . $_SERVER['HTTP_HOST'] . "/sharecode/view.php?id=" . $pasteId
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create paste']);
}
?>
