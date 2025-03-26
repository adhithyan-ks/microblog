<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch user posts
$post_stmt = $conn->prepare("SELECT posts.id, posts.content, posts.created_at, categories.name AS category_name 
                            FROM posts
                            JOIN categories ON posts.category_id = categories.id
                            WHERE posts.user_id = ?
                            ORDER BY posts.created_at DESC");
$post_stmt->bind_param("i", $user_id);
$post_stmt->execute();
$posts = $post_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="acc.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <h2>Profile</h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><a href="logout.php">Logout</a></p>

        <h3>Your Posts</h3>
        <div class="user-posts">
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($row = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <p><strong>Category:</strong> <?= htmlspecialchars($row['category_name']) ?></p>
                        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                        <small>Posted on: <?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't posted anything yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
