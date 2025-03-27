<?php
session_start();
include 'includes/db.php';

// Fetch categories
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

// Fetch latest posts
$sql = "SELECT posts.id, posts.content, posts.created_at, users.username, 
               posts.category_id, categories.name AS category_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";
$posts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MicroBlog</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/2e5e758ab7.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>MicroBlog</h1>
        <nav>
            <a href="./" class="active">Home</a>
            <a href="createpost.php">Create</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="content-wrapper">
        <!-- Left Side: Posts -->
        <section class="recent-posts">
            <h2>Recent Posts</h2>
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="post">
                        <p><strong><?= htmlspecialchars($post['username']) ?></strong></p>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <a href="category.php?id=<?= $post['category_id'] ?>" class="post-category">#<?= htmlspecialchars($post['category_name']) ?></a>
                        <small><?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </section>

        <!-- Right Side: Categories -->
        <aside class="categories">
            <h2>Categories</h2>
            <a href="./" class="category-item">All</a>
            <?php if ($categories->num_rows > 0): ?>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <a href="category.php?id=<?= $cat['id'] ?>" class="category-item"><?= htmlspecialchars($cat['name']) ?></a>
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