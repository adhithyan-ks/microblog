<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if post_id is provided
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["post_id"])) {
    $post_id = intval($_POST["post_id"]);
    $user_id = $_SESSION["user_id"];

    // Ensure the user owns the post before deleting
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: profile.php?message=Post deleted successfully");
    } else {
        header("Location: profile.php?error=Failed to delete post");
    }
    exit();
} else {
    header("Location: profile.php");
    exit();
}
?>
