<?php
require 'config.php';  // Ensure your database connection is set up

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $blogId = $_POST['id'];

    // Prepare and execute the DELETE query
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$blogId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>