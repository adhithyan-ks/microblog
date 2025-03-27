<?php
session_start();
include 'includes/db.php';

// Get category ID from URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch categories for the top navbar
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

// Fetch selected category name
$category_name = "All";
$category_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$category_stmt->bind_param("i", $category_id);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
if ($category_row = $category_result->fetch_assoc()) {
    $category_name = $category_row['name'];
}

// Fetch posts for the selected category
$sql = "SELECT posts.id, posts.content, posts.created_at, 
               users.username, categories.name AS category_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.category_id = ?
        ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category_name) ?> - Microblog</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <a href="index.php" <?= ($category_id == 0) ? 'class="active"' : '' ?>>All</a>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <a href="category.php?id=<?= $cat['id'] ?>" <?= ($cat['id'] == $category_id) ? 'class="active"' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Category: <?= htmlspecialchars($category_name) ?></h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <p><strong><?= htmlspecialchars($row['username']) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    <small><?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available in this category.</p>
        <?php endif; ?>
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="index.php"><i class="fas fa-home"></i></a>
        <a href="create_post.php"><i class="fas fa-plus-square"></i></a>
        <a href="profile.php"><i class="fas fa-user"></i></a>
    </div>

</body>
</html>
