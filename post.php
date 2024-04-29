<?php
// Start the session at the beginning
session_start();

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
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Retrieve the username based on the user_id
        $usernameQuery = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($usernameQuery);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $username = $row['username'];

           // Retrieve the post content from the request
$postContent = nl2br($_POST['post-content']); // Replace newlines with <br>

               // Validate post content
    if (empty($postContent)) {
    header("Location: post.php?error=empty_content");
    exit(); // Stop further execution

    }

            // TODO: Add validation for post content if needed

            // Insert the post into the 'posts' table using prepared statement
            $sql = "INSERT INTO posts (user_id, username, timestamp, post_content) VALUES (?, ?, NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iss', $userId, $username, $postContent);

            // Check for errors in query execution
            if (!$stmt->execute()) {
                echo "Error in inserting the post: " . $stmt->error;
            } else {
                // Post inserted successfully
                // Redirect to blog.php
                header("Location: Posted.php");
                exit(); // Ensure that no further code is executed after the redirection
            }
        } else {
            echo "Error retrieving username.";
        }

        // Close the statement
        $stmt->close();
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
   <a href="blog.php" class="icon">
                    <img src="img/icons8-home.gif" alt="Post">
                </a>
                &nbsp; &nbsp;
             
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusWeb - Post Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha256-lv0+Kpx6zYpgbERdUGFmkMT5qFbsrv9iyrYOoEj9RS4=" crossorigin="anonymous" />
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <div class="web-app-name">MyCampus Diary</div>
            <div class="navbar-icons">
                   <a href="blog.php" class="icon">
                    <img src="img/icons8-home.gif" alt="Home">
                </a>
                <a href="profile.php" class="icon">
                    <img src="img/icons8-male-user-48.png" alt="Profile">
                </a>
                
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <!-- Main Content -->
    <div class="main-content">
        <h1>What's on your mind?</h1>
        <form action="" method="POST">
            <textarea id="post-textarea" name="post-content" placeholder="Write your post here..."></textarea>
            <button id="post-button" type="submit">
                Post
            </button>
        </form>
    </div>
    <!-- Footer -->
    <footer class="footer">
        &copy; SINGOCYPHER
    </footer>
</body>

</html>

<script>
    // Check for error query parameter and display an alert
    const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');

    

    function validateForm() {
        var postContent = document.getElementById('post-textarea').value.trim();

        if (postContent === '') {
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
</script>




 <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f8f8;
            color: #333;
        }

        nav {
            background: linear-gradient(to right, #4C4771, #FEFEFE);
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }

        .web-app-name {
            font-size: 1.5em;
            font-weight: bold;
            margin-right: auto;
        }

        .navbar-icons {
            display: flex;
            gap: 7px;
            margin-left: 2px;
        }

        .navbar-icons a {
            color: white;
            text-decoration: none;
            margin-right: 10px; /* Adjust the margin-right as needed */
            margin-left: 5px; /* Add this line to set left margin */
        }

        .icon {
            font-size: 2em;
        }

        .main-content {
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        #post-textarea {
            width: 100%;
            height: 150px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        /* Adjust styles for smaller screens */
@media only screen and (max-width: 600px) {
    #post-textarea {
        font-size: 14px;
        line-height: 1.5;
        /* Add any other adjustments as needed */
    }
}

#post-textarea {
    font-size: 1em;
    /* Add any other styles */
}


        #post-button {
            background-color: #6A679E;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(90deg, #5e5e5f, #414048);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            clear: both;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
