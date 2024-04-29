<?php
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

        // Retrieve the username based on the user_id
        $usernameQuery = "SELECT username FROM users WHERE id = '$userId'";
        $result = $conn->query($usernameQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];

            // Retrieve the post content from the request
            $postContent = $_POST['postContent']; // Adjust the field name based on your form

            // TODO: Add validation for post content if needed

            // Insert the post into the 'posts' table
            $sql = "INSERT INTO posts (user_id, username, timestamp, post_content) VALUES ('$userId', '$username', NOW(), '$postContent')";

            if ($conn->query($sql) === TRUE) {
                // Post inserted successfully
                echo "Post submitted successfully.";

                // Redirect to blog.php
                header("Location: blog.php");
                exit(); // Ensure that no further code is executed after the redirection
            } else {
                // Error in inserting the post
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error retrieving username.";
        }
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
?>

