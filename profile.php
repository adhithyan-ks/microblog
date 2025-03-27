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

// Fetch categories for the sidebar
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - MicroBlog</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>MicroBlog</h1>
        <nav>
            <a href="./">Home</a>
            <a href="createpost.php">Create</a>
            <a href="profile.php" class="active">Profile</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="content-wrapper">
        <!-- Left Side: Profile Info & Posts -->
        <section class="profile-container">
            <h2>Profile</h2>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <a href="logout.php" class="logout-btn">Logout</a>

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
        </section>
    </main>

    <footer>
        <p>💙</p>
    </footer>

</body>
</html>
