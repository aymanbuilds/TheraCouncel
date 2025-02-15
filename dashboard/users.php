<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['user-name']);
    $email = trim($_POST['user-email']);
    $currentPassword = trim($_POST['user-password']);
    $userId = isset($_POST['user-id']) ? (int)$_POST['user-id'] : null;

    if (isset($_POST['add-user'])) {
        $hashed_password = password_hash($currentPassword, PASSWORD_DEFAULT);  
        addUser($name, $email, $hashed_password);
    } elseif (isset($_POST['update-user'])) {
        $newPassword = trim($_POST['new-password']);  
        if ($userId !== null) {
            updateUser($userId, $name, $email, $currentPassword, $newPassword);
        }
    } elseif (isset($_POST['logout-btn'])) {
        session_unset();
        session_destroy();
    
        header("Location: login.php");
        exit();  
    }
}

function addUser($name, $email, $password) {
    global $pdo;

    $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $emailExists = $stmt->fetchColumn();

    if ($emailExists > 0) {
        // echo json_encode(['status' => 'error', 'message' => 'Email already exists!']);
        return; 
    }

    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        
        $stmt->execute();

    } catch (PDOException $e) {
        // echo json_encode(['status' => 'error', 'message' => 'An error occurred while adding the user.']);
    }
}

function updateUser($userId, $name, $email, $currentPassword, $newPassword) {
    global $pdo;

    // Step 1: Fetch the user by ID to get the current password
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Step 2: Verify if the current password matches the one in the database
        if (password_verify($currentPassword, $user['password'])) {
            // Step 3: Hash the new password if provided
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Step 4: Update the user's details
            $updateSql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->bindParam(':name', $name);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':id', $userId);

            // Execute the update statement
            $updateStmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheraCouncel | cPanel</title>
    <link rel="stylesheet" href="assets/styles/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Fauna+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" media="all">
    <meta name="robots" content="noindex, nofollow">
</head>

<body>
    <section class="dashboard-wrapper">
        <header class="top-bar">
            <img class="logo" src="assets/images/logo.webp" alt="TheraCouncel, Inc.">
            <h1>cPanel</h1>
        </header>

        <main>
            <aside>
                <div class="inner">
                    <nav>
                        <ul>
                            <li>
                                <a href="blogs.php">
                                    <div>
                                        <img src="assets/icons/blog-icon.png" alt="TheraCouncel Blogs">
                                        Blogs
                                    </div>
                                </a>
                            </li>

                            <li class="active">
                                <a href="users.php">
                                    <div>
                                        <img src="assets/icons/blog-icon.png" alt="TheraCouncel Blogs">
                                        Users
                                    </div>
                                </a>
                            </li>

                            <li class="logout">
                                <form method="POST">
                                    <button type="submit" name="logout-btn">
                                        <img src="assets/icons/logout.png" alt="TheraCouncel Blogs">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <section class="main-section">
                <div class="inner">
                    <header>
                        <h2>Users</h2>
                        <button id="add-user" class="primary">Add User</button>
                    </header>

                    <div class="table-container">
                        <table id="users-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- User rows will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </section>

    <!-- Add User Popup -->
    <div id="add-user-popup" class="popup-overlay">
        <div class="popup-content">
            <h2 id="add-user-popup-title">Add User</h2>
            <form id="add-user-form" method="POST">
                <input type="hidden" id="user-id" name="user-id" value="">
                <div class="input-group">
                    <label for="user-name">Name</label>
                    <input type="text" id="user-name" name="user-name" placeholder="Enter user name" required />
                </div>
                <div class="input-group">
                    <label for="user-email">Email</label>
                    <input type="email" id="user-email" name="user-email" placeholder="Enter user email" required />
                </div>
                <div class="input-group">
                    <label for="user-password">Password</label>
                    <input type="password" id="user-password" name="user-password" placeholder="Enter user password" required />
                </div>
                <div class="row">
                    <button type="submit" class="primary" name="add-user">Submit</button>
                    <button type="button" class="close-btn">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update User Popup -->
    <div id="update-user-popup" class="popup-overlay">
        <div class="popup-content">
            <h2 id="update-user-popup-title">Update User</h2>
            <form id="update-user-form" method="POST">
                <input type="hidden" id="update-user-id" name="user-id" value="">
                <div class="input-group">
                    <label for="update-user-name">Name</label>
                    <input type="text" id="update-user-name" name="user-name" placeholder="Enter user name" required />
                </div>
                <div class="input-group">
                    <label for="update-user-email">Email</label>
                    <input type="email" id="update-user-email" name="user-email" placeholder="Enter user email" required />
                </div>
                <div class="input-group">
                    <label for="update-user-password">Current Password</label>
                    <input type="password" id="update-user-password" name="user-password" placeholder="Enter user password" required />
                </div>
                <div class="input-group">
                    <label for="update-user-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required />
                </div>
                <div class="row">
                    <button type="submit" class="primary" name="update-user">Update</button>
                    <button type="button" class="close-btn">Close</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-popup" class="popup-overlay">
        <div class="popup-content">
            <h2>Are you sure you want to delete this user?</h2>
            <div class="confirmation-buttons">
                <button id="confirm-delete" class="primary">Yes, Delete</button>
                <button id="cancel-delete" class="close-btn">No, Cancel</button>
            </div>
        </div>
    </div>

    <script src="assets/scripts/tinymce/tinymce.min.js"></script>
    <script src="assets/scripts/main.js"></script>
</body>

</html>