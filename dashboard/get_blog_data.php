<?php
require 'config.php';

if (isset($_GET['id'])) {
    $blogId = $_GET['id'];

    // Prepare and execute the query to get the blog data
    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->execute([$blogId]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the blog data as JSON
    if ($blog) {
        echo json_encode(['success' => true, 'blog' => $blog]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Blog not found']);
    }
}
?>