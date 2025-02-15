<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_email'])) {
    header("Location: blogs.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_email'] = $email;
        header("Location: blogs.php");
        exit();
    }

    header("Location: login.php?error=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheraCouncel | Login</title>
    <link rel="stylesheet" href="assets/styles/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Fauna+One&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" media="all">
    <meta name="robots" content="noindex, nofollow">
</head>

<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>TheraCouncel</h2>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>

</html>