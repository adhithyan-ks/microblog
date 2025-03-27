<?php
session_start();
include 'includes/db.php';

// Get category ID from URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch categories for the sidebar
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

// Fetch category name
$category_name = "Unknown";
$category_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$category_stmt->bind_param("i", $category_id);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
if ($category_row = $category_result->fetch_assoc()) {
    $category_name = $category_row['name'];
}

// Fetch posts for the selected category
$sql = "SELECT posts.id, posts.content, posts.created_at, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.category_id = ?
        ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category_name) ?> - MicroBlog</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>MicroBlog</h1>
        <nav>
            <a href="./">Home</a>
            <a href="createpost.php">Create</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="content-wrapper">
        <!-- Left Side: Posts -->
        <section class="recent-posts">
            <h2>Category: <?= htmlspecialchars($category_name) ?></h2>
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <p><strong><?= htmlspecialchars($post['username']) ?></strong></p>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <small><?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts available in this category.</p>
            <?php endif; ?>
        </section>

        <!-- Right Side: Categories -->
        <aside class="categories">
            <h2>Categories</h2>
            <a href="./" class="category-item">All</a>
            <?php if ($categories->num_rows > 0): ?>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <a href="category.php?id=<?= $cat['id'] ?>" class="category-item <?= ($cat['id'] == $category_id) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No categories available.</p>
            <?php endif; ?>
        </aside>
    </main>

    <footer>
        <p>💙</p>
    </footer>

</body>
</html>
