<?php
session_start();
include("connect.php");

// Function to get the currently signed-in user's username
function getCurrentUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : '';
}

// Sample SQL query to retrieve user posts with user information
$sql = "SELECT
            p.post_id,
            p.post_content,
            COUNT(l.id) as likes_count,
            p.comments_count,
            p.timestamp,
            u.username,
            u.profile_picture
        FROM
            posts p
        LEFT JOIN
            likes l ON p.post_id = l.post_id
        JOIN
            users u ON p.user_id = u.id
        WHERE
            p.user_id = {$_SESSION['user_id']}
        GROUP BY
            p.timestamp DESC";

// Function to get the currently signed-in user's profile picture path
function getUserProfilePicture() {
    // Assuming you have a database connection established
    $host = 'localhost';
    $username = 'root';
    $password = ''; // Replace with your database password
    $database = 'MYCAMPD'; // Replace with your database name

    // Establish a connection to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the profile picture path from the database based on the user ID or email
    $userId = $_SESSION['user_id']; // Adjust based on your session structure

    $sql = "SELECT profile_picture FROM users WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['profile_picture'];
    } else {
        // Default profile picture path if not found
        return 'default_profile_picture.jpg'; // Adjust to your default image path
    }
}

// Function to get the currently signed-in user's username
function getUserUsername() {
    // Assuming you have a database connection established
    $host = 'localhost';
    $username = 'root';
    $password = ''; // Replace with your database password
    $database = 'MYCAMPD'; // Replace with your database name

    // Establish a connection to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the username from the database based on the user ID or email
    $userId = $_SESSION['user_id']; // Adjust based on your session structure

    $sql = "SELECT username FROM users WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['username'];
    } else {
        // Default username if not found
        return 'Guest'; // Adjust to your default username
    }
}

$result = $conn->query($sql);

// Check for query execution errors
if (!$result) {
    die("Error executing the query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (your head section) ... -->
</head>
<body>

<!-- Header section -->
<nav>
    <div class="navbar-container">
        <div class="web-app-name">MyCampus Diary</div>
        <div class="navbar-icons">
            <a href="blog.php" class="icon">
                <img src="img/icons8-home.gif" alt="Home">
            </a>
            &nbsp; &nbsp;
            <a href="post.php" class="icon">
                <img src="img/icons8-create-48.png" alt="Post">
            </a>
            &nbsp; &nbsp;
        </div>
    </div>
</nav>

<!-- User Profile section -->
<div class="profile-section">
    <!-- Logout Button -->
    <div class="logout-container">
        <a href="login.php" class="icon"> <!-- Assuming logout.php handles logout logic -->
            <img src="img/icons8-logout-48.png" alt="Logout">
        </a>
    </div>
    <!-- User Assistance Icon and Name -->
    <div class="user-assistant">
        <a href="moyoo.php">
            <img src="img/moyo.gif" alt="User Assistant">
        </a>
    </div>

    <!-- Profile Picture -->
    <label for="profile-picture-input">
        <img id="profile-picture" src="<?php echo getUserProfilePicture(); ?>" alt="Profile Picture" class="profile-picture"
             onclick="changeProfilePicture()">
    </label>

    <!-- Hidden input for uploading profile picture -->
    <input type="file" id="profile-picture-input" style="display:none" onchange="previewProfilePicture(this)"
           accept="image/*">

    <!-- Username -->
    <div class="username"><?php echo getUserUsername(); ?></div>
    <p>Click the profile picture to change it.</p>

    <!-- Your main content goes here -->
    <center>
        <div><h2>Manage your posts</h2></div>
    </center>

    <!-- Sample of Posts -->
    <div class="post-section">
        <?php
        // Display user posts
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="post">';
                echo '<div class="post-header">';
                echo '<div class="author-info">';
                echo '<img src="' . $row['profile_picture'] . '" alt="Author" class="avatar">';
                echo '<div class="username">' . $row['username'] . '</div>';
                echo '</div>';
                echo '<div class="timestamp">' . date('d M g:ia', strtotime($row['timestamp'])) . '</div>';

                echo '</div>';
                echo '<div class="post-content">' . $row['post_content'] . '</div>';
                echo '<div class="post-actions">';

                // Check if 'likes' and 'comments' keys exist in the current $row array
                $likes = isset($row['likes']) ? $row['likes'] : 0;
                $comments = isset($row['comments']) ? $row['comments'] : 0;

                // Display in your HTML
                echo '<img src="img/icons8-likered.png" alt="Like" class="like-icon">';
                echo '<span class="like-count">' . $row['likes_count'] . ' likes</span>';
                echo '<a href="#" class="comment-count">' . $row['comments_count'] . ' comments</a>';

                // Delete button
                if (isset($row['post_id'])) {
                    echo '<a href="delete_post.php?post_id=' . $row['post_id'] . '" class="delete-button">Delete</a>';
                } else {
                    echo '<span class="delete-button">Delete</span>';
                }

                echo '</div>';

                // Move the comment box here
                echo "<div class='comment-container'>";
                echo "<input id='commentbox' type='text' placeholder='Add comment...'>";
                echo "<img src='img/icons8-send.png' alt='Submit Comment' class='submit-icon' onclick='submitComment()'>";
                echo "</div>";
                echo "</div>";

                echo '</div>';
            }
        } else {
            echo '<p>No posts found.</p>';
        }
        ?>
    </div>

    <!-- Your main content goes here -->

</body>
</html>







<style>

    
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            margin-top: 60px; /* Adjust the margin based on the height of your navbar */
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
            z-index: 1000; /* Ensure the nav bar appears above other elements */
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
        }

        /* White section under the navbar */
        .profile-section {
            background-color: white;
            padding: 10px;
            text-align: center;
        }

        /* Styling for the profile picture */
        .profile-picture {
            width: 100px; /* Adjust the size as needed */
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Styling for the username */
        .username {
            margin-top: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }

        /* Styling for the logout and user-assistant icons */
        .logout-container,
        .user-assistant {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .logout-container a,
        .user-assistant a {
            margin: 0 10px;
        }

        /* Add padding to the body when the navbar is fixed */
        body.navbar-fixed {
            padding-top: 60px; /* Adjust based on navbar height */
        }
         /* Style for each post */
        .post {
            background-color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .author-info {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .username {
            font-weight: bold;
            color: #6A679E;
        }

        .timestamp {
            color: #888;
            margin-left: 60px;
        }

     .post-content {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    white-space: pre-wrap;
    max-width: 100%; /* Limit the maximum width */
}

   @media only screen and (max-width: 600px) {
    .post-content {
        font-size: 14px; /* Adjust the font size for smaller screens */
    }
}

.post-content {
    max-height: 200px; /* Set a maximum height */
    overflow-y: auto; /* Add vertical scrollbar if needed */
}



        .post-actions {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Adjust this line */
            gap: 20px;
        }

        .like-icon,
        .comment-icon,
        .edit-icon,
        .delete-icon,
        .send-icon {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        #commentbox {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 10px; /* Adjust margin as needed */
        }

        .comment-container {
            display: flex;
            align-items: center;
            margin-top: 10px; /* Adjust margin as needed */
        }
           .delete-button {
        color: red;       /* Set color to red */
        text-decoration: none;  /* Remove underline */
        font-family: 'YourChosenFont', sans-serif;  /* Change the font */
        /* Add any other styling you want */
    }
    .submit-icon {
            width: 30px;
            height: 30px;
            margin-left: 8px;
        }
    
    </style>