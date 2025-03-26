<?php
session_start();
include 'includes/db.php'; // Database connection
// include 'includes/functions.php'; // Helper functions

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
    <title>Microblogging</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="nav.css">
</head>
<body>
<body>
    <?php include 'header.php'; ?>
    <div class="main-content">
        <h1>Welcome to Microblog</h1>
        <p>Here you can see posts and explore categories.</p>
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
</body>

</body>
</html>
