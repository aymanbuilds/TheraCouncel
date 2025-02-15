<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout-btn'])) {
        session_unset();
        session_destroy();
    
        header("Location: login.php");
        exit();  
    } elseif (isset($_POST['add-blog-btn'])) {
        // Get the title and content from the form
        $title = $_POST['title'];
        $content = $_POST['content'];
    
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Get the file's temporary name and original file name
            $imageTmp = $_FILES['image']['tmp_name'];
            $imageName = $_FILES['image']['name'];
    
            // Get the file's extension (e.g., .jpg, .png)
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
    
            // Generate a unique name for the image file using uniqid() and append the extension
            $uniqueImageName = uniqid('img_', true) . '.' . $imageExtension;
    
            // Set the upload path (uploads directory in the current folder)
            $imagePath = 'uploads/' . $uniqueImageName;
    
            // Move the uploaded image to the 'uploads' directory
            if (move_uploaded_file($imageTmp, $imagePath)) {
                // Insert the data into the database
                $stmt = $pdo->prepare("INSERT INTO blogs (image, title, content) VALUES (?, ?, ?)");
                $stmt->execute([$imagePath, $title, $content]);

                header("Location: blogs.php");
                exit();
            } else {
                // echo "Failed to upload image.";
            }
        } else {
            // echo "Image upload error.";
        }
    } elseif (isset($_POST['update-blog-id'])) {
        $blogId = $_POST['update-blog-id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        
        // Check if an image was uploaded
        $image = $_FILES['image'] ?? null;
        $imagePath = $currentBlog['image'];  // Default to the current image

        // If a new image is uploaded, process the new image
        if ($image && $image['error'] === 0) {
            $imageTmp = $image['tmp_name'];
            $imageName = $image['name'];
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $imagePath = 'uploads/' . uniqid('img_', true) . '.' . $imageExtension;
            move_uploaded_file($imageTmp, $imagePath);
        }

        // Now, update the blog in the database
        if ($imagePath) {
            // If there is an image path (either new or current), update the blog with the image
            $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $content, $imagePath, $blogId]);
        } else {
            // If no new image was uploaded, update without changing the image
            $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $content, $blogId]);
        }
            header("Location: blogs.php");
            exit();
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
                            <li class="active">
                                <a href="blogs.php">
                                    <div>
                                        <img src="assets/icons/blog-icon.png" alt="TheraCouncel Blogs">
                                        Blogs
                                    </div>
                                </a>
                            </li>

                            <li>
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
                        <h2>Blogs</h2>
                        <button id="add-blog" class="primary">Add Blog</button>
                    </header>

                    <ul class="blogs-grid">
                        <?php foreach ($blogs as $blog): ?>
                            <li class="blog-card">
                                <article>
                                    <!-- Display image, use a placeholder if image is not available -->
                                    <img src="<?= !empty($blog['image']) ? $blog['image'] : 'assets/images/placeholder-image.jpg'; ?>" alt="Blog Image">
                                    <div class="label">article</div>
                                    <div class="datetime"><?= date('m-d-Y', strtotime($blog['created_at'])); ?></div>
                                    <h3><?= htmlspecialchars($blog['title']); ?></h3>
                                    <p><?= strip_tags(substr($blog['content'], 0, 150)) . '...'; ?></p>
                                    <div class="row">
                                        <!-- Update Button -->
                                        <button class="primary" data-blog-id="<?= $blog['id']; ?>">
                                            <img src="assets/icons/edit.png" alt="Update Blog">
                                        </button>
                                        <!-- Delete Button -->
                                        <button class="cancel" data-blog-id="<?= $blog['id']; ?>">
                                            <img src="assets/icons/delete.png" alt="Delete Blog">
                                        </button>
                                    </div>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        </main>
    </section>

    <div id="blog-popup" class="popup-overlay">
        <div class="popup-content">
            <h2>Add Blog</h2>
            <form id="blog-form" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="blog-image">Image</label>
                    <input type="file" name="image" id="blog-image" accept="image/*" required />
                </div>
                <div class="input-group">
                    <label for="blog-title">Blog Title</label>
                    <input type="text" id="blog-title" name="title" placeholder="Enter blog title" required />
                </div>
                <div class="input-group">
                    <label for="blog-text">Blog Text</label>
                    <textarea id="blog-text" class="rich-textbox" name="content" placeholder="Enter blog content"></textarea>
                    <textarea id="blog-text-hidden" name="content" placeholder="Enter updated blog content" required></textarea>
                </div>
                <div class="row">
                    <button type="submit" class="primary" name="add-blog-btn">Submit</button>
                    <button type="button" class="close-btn">Close</button>
                </div>
            </form>
        </div>
    </div>

    <div id="update-popup" class="popup-overlay">
        <div class="popup-content">
            <h2>Update Blog</h2>
            <form id="update-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="update-blog-id" name="update-blog-id" accept="image/*" />
                <div class="input-group">
                    <label for="update-image">Image</label>
                    <input type="file" id="update-image" name="image" accept="image/*" />
                </div>
                <div class="input-group">
                    <label for="update-title">Blog Title</label>
                    <input type="text" id="update-title" name="title" placeholder="Enter updated blog title" required />
                </div>
                <div class="input-group">
                    <label for="update-text">Blog Text</label>
                    <textarea class="rich-textbox" id="update-text" placeholder="Enter updated blog content"></textarea>
                    <textarea id="update-text-hidden" name="content" placeholder="Enter updated blog content" required></textarea>
                </div>
                <div class="row">
                    <button type="submit" class="primary" name="update-blog-btn">Update</button>
                    <button type="button" class="close-btn">Close</button>
                </div>
            </form>
        </div>
    </div>

    <div id="delete-popup" class="popup-overlay">
        <div class="popup-content">
            <h2>Are you sure you want to delete this blog?</h2>
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