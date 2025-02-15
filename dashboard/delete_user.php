<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-user'])) {
    $userId = isset($_POST['user-id']) ? (int)$_POST['user-id'] : null;

    if ($userId !== null) {
        // SQL to delete the user
        $sql = "DELETE FROM users WHERE id = :id";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully!']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred while deleting the user.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User ID is missing or invalid.']);
    }
}
?>