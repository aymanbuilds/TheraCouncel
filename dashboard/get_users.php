<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or Update User Logic (same as before)
    // ...
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch users from the database
    fetchUsers();
}

function fetchUsers() {
    global $pdo;

    // SQL to fetch all users
    $sql = "SELECT id, name, email FROM users";

    try {
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the users as JSON
        echo json_encode($users);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>