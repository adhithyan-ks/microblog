<?php
session_start();
include 'includes/db.php';

// Fetch categories for the top navbar
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

// Fetch latest posts
$sql = "SELECT posts.id, posts.content, posts.created_at, 
               users.username, categories.name AS category_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microblog</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <a href="index.php" class="active">All</a>
        <?php while ($cat = $categories->fetch_assoc()): ?>
            <a href="category.php?id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a>
        <?php endwhile; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <p><strong><?= htmlspecialchars($row['username']) ?></strong> in <em><?= htmlspecialchars($row['category_name']) ?></em></p>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    <small><?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="index.php"><i class="fas fa-home"></i></a>
        <a href="create_post.php" class="create-post"><i class="fas fa-plus"></i></a>
        <a href="profile.php"><i class="fas fa-user"></i></a>
    </div>


</body>
</html>
