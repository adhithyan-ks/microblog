<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION["user_id"])): ?>
            <li><a href="create_post.php">Create Post</a></li>
            <li><a href="profile.php">Welcome, <?= htmlspecialchars($_SESSION["username"]) ?></a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>