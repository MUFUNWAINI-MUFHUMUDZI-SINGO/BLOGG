<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have user authentication in place to get user information
    $userId = 1; // Replace with the actual user ID
    $postId = $_POST['post_id'];
    $commentContent = $_POST['comment_content'];

    // Insert the comment into the 'comments' table
    $sql = "INSERT INTO comments (post_id, user_id, comment_content) VALUES ('$postId', '$userId', '$commentContent')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Comment submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
