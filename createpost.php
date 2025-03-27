<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$errors = [];

// Fetch categories for the dropdown
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
    <title>Create Post - MicroBlog</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/create.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>MicroBlog</h1>
        <nav>
            <a href="./">Home</a>
            <a href="createpost.php" class="active">Create</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="content-wrapper">
        <!-- Left Side: Create Post Form -->
        <section class="create-post-container">
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

                <button type="submit">Post</button>
            </form>
        </section>
    </main>

    <footer>
        <p>💙</p>
    </footer>

</body>
</html>
