<?php
session_start();
include("connect.php");

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Delete likes associated with the post
    $deleteLikesQuery = "DELETE FROM likes WHERE post_id = $post_id";
    $conn->query($deleteLikesQuery);

    // Delete the post from the database
    $deletePostQuery = "DELETE FROM posts WHERE post_id = $post_id";
    $deletePostResult = $conn->query($deletePostQuery);

    if ($deletePostResult) {
        // Redirect back to the page after deletion
        header("Location: profile.php");
        exit();
    } else {
        echo "Error deleting post: " . $conn->error;
    }
} else {
    echo "Post ID not specified.";
}
?>





