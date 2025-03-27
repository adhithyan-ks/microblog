<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$errors = [];

// Fetch categories for dropdown
$categories = [];
$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($result->num_rows > 0) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $category_id = $_POST["category"];
    $content = trim($_POST["content"]);

    if (empty($content)) {
        $errors[] = "Post content cannot be empty.";
    } elseif (empty($category_id)) {
        $errors[] = "Please select a category.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $category_id, $content);

        if ($stmt->execute()) {
            header("Location: index.php"); // Redirect to homepage
            exit();
        } else {
            $errors[] = "Failed to create post. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <!-- Main Content -->
    <div class="create-post-container">
        <h2>Create a New Post</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="create_post.php" method="post">
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="content">Post Content:</label>
            <textarea name="content" id="content" rows="4" required></textarea>

            <button type="submit"><i class="fas fa-paper-plane"></i> Post</button>
        </form>
    </div>

    <!-- Bottom Navigation Bar -->

    <div class="bottom-nav">
        <a href="index.php"><i class="fas fa-home"></i></a>
        <a href="create_post.php" class="create-post active"><i class="fas fa-plus"></i></a>
        <a href="profile.php"><i class="fas fa-user"></i></a>
    </div>
</body>
</html>
