<?php

// Include your database connection code or any other necessary includes

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a database connection established
    $host = 'localhost';
    $username = 'root';
    $password = ''; // Replace with your database password
    $database = 'MYCAMPD'; // Replace with your database name

    $conn = new mysqli($host, $username, $password, $database);

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user information from the session (assuming you have a session)
    session_start();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Retrieve the post ID and owner's username from the POST request
        $postId = $_POST['postId'];

        // Check if the user has already liked the post
        $checkLikeQuery = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
        $checkLikeStmt = $conn->prepare($checkLikeQuery);
        $checkLikeStmt->bind_param('ii', $postId, $userId);
        $checkLikeStmt->execute();
        $checkLikeResult = $checkLikeStmt->get_result();

        if ($checkLikeResult->num_rows > 0) {
            // User has already liked the post, so unlike it
            $deleteLikeQuery = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
            $deleteLikeStmt = $conn->prepare($deleteLikeQuery);
            $deleteLikeStmt->bind_param('ii', $postId, $userId);
            $deleteLikeStmt->execute();
        } else {
            // User has not liked the post, so like it
            $insertLikeQuery = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
            $insertLikeStmt = $conn->prepare($insertLikeQuery);
            $insertLikeStmt->bind_param('ii', $postId, $userId);
            $insertLikeStmt->execute();
        }

        // TODO: Update the likes_count in the 'posts' table
        // You may need a separate query to retrieve and update the likes_count for the post
        $likeCount = getLikeCount($conn, $postId);
        echo $likeCount;
    } else {
        // Handle the case where the user is not logged in
        echo "User not logged in.";
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle non-POST requests
    echo "Invalid request method.";
}

// Function to get the like count for a post
function getLikeCount($conn, $postId) {
    $likeCountQuery = "SELECT COUNT(*) AS count FROM likes WHERE post_id = ?";
    $likeCountStmt = $conn->prepare($likeCountQuery);
    $likeCountStmt->bind_param('i', $postId);
    $likeCountStmt->execute();
    $likeCountResult = $likeCountStmt->get_result();

    if ($likeCountResult && $likeCountResult->num_rows > 0) {
        $likeCount = $likeCountResult->fetch_assoc()['count'];
        return $likeCount;
    }

    return 0;
}
?>

