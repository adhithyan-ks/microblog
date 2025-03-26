<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION["user_id"])): ?>
            <li><a href="create_post.php">Create Post</a></li>
            <li><a href="profile.php">Profile</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>