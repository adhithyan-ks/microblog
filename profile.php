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
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Profile Header -->
    <div class="profile-header">
        <h2><?= htmlspecialchars($user['username']) ?></h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Your Posts</h3>
        <?php if ($posts->num_rows > 0): ?>
            <?php while ($row = $posts->fetch_assoc()): ?>
                <div class="post">
                    <p><strong><?= htmlspecialchars($user['username']) ?></strong> in <em><?= htmlspecialchars($row['category_name']) ?></em></p>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    <small><?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You haven't posted anything yet.</p>
        <?php endif; ?>
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="index.php"><i class="fas fa-home"></i></a>
        <a href="create_post.php" class="create-post"><i class="fas fa-plus"></i></a>
        <a href="profile.php" class="active"><i class="fas fa-user"></i></a>
    </div>

</body>
</html>
