<!-- Search.php -->

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchKeyword = $_POST["search"];

    // Fetch posts from the 'posts' table based on the search keyword
    $sql = "SELECT * FROM posts WHERE post_content LIKE '%$searchKeyword%' OR username LIKE '%$searchKeyword%' ORDER BY timestamp DESC";
    $result = $conn->query($sql);

      // Check if there are posts
// Check if there are posts
if ($result && $result->num_rows > 0) {
    // Output data for each row
    // Inside your while loop for fetching and displaying posts
    while ($row = $result->fetch_assoc()) {
        $postId = $row['post_id'];
        $userId = $row['user_id'];
        $username = $row['username'];
        $timestamp = $row['timestamp'];
        $postContent = $row['post_content'];

        // Fetch user details including profile picture
        $userDetails = getUserDetails($conn, $userId);
        $userProfilePicture = $userDetails['profile_picture'];

        // Check if the profile picture path is empty, use the default profile picture
        if (empty($userProfilePicture)) {
            $userProfilePicture = 'img/ppp.png'; // Use the default profile picture path
        }

        // Format timestamp to display date and time
        $formattedDateTime = date('d-m-Y H:i:s', strtotime($timestamp));

        // Display the post details
        echo "<div class='post'>";
        echo "<div class='post-header'>";
        echo "<div class='author-info'>";
        echo "<img src='$userProfilePicture' alt='User Avatar' class='avatar'>";
        echo "<div class='username'>$username</div>";
        echo "</div>";
            echo '<div class="timestamp">' . date('d M g:ia', strtotime($row['timestamp'])) . '</div>';

        echo "</div>";
        // Add the horizontal line
        echo "<hr>";
        echo "<div class='post-content'>";
        echo "<p>" . nl2br($postContent) . "</p>";

        // Add the like button and count
        echo "<div class='post-actions'>";
        // Add the default (white) like icon
        echo "<img src='img/icons8-likered.png' alt='Like' class='like-icon' onclick='toggleLike($postId, \"$username\")' />";
        echo "<span class='like-count' id='like-count-$postId'>" . getLikeCount($conn, $postId) . " likes</span>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<span class='comments-link' onclick='viewComments($postId)'>0 Comments</span>";
        echo "</div>";

        echo "</div>";
        echo "<div class='comment-container'>";
        echo "<input id='commentbox' type='text' placeholder='Add comment...'>";
        echo "<img src='img/icons8-send.png' alt='Submit Comment' class='submit-icon' onclick='submitComment()'>";
        echo "</div>";
        echo "</div>";
    }
}
} else {
    // Display a message if there are no posts
    echo "No posts found.";
}



    // Close the database connection
    $conn->close();

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
    // Function to get user details including profile picture
function getUserDetails($conn, $userId) {
    $userDetailsQuery = "SELECT username, profile_picture FROM users WHERE id = ?";
    $userDetailsStmt = $conn->prepare($userDetailsQuery);
    $userDetailsStmt->bind_param('i', $userId);
    $userDetailsStmt->execute();
    $userDetailsResult = $userDetailsStmt->get_result();

    if ($userDetailsResult && $userDetailsResult->num_rows > 0) {
        return $userDetailsResult->fetch_assoc();
    }

    return null;
}
    ?>
</div>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Search Page</title>
</head>
<body>




<nav class="navbar">
    <div class="search-container">
         <form method="POST" action="Search.php">
    <input type="text" id="searchInput" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>
  <a href="blog.php" class="icon desktop-icon">
        <img src="img/icons8-home.gif" alt="Home">

    </div>
   
</nav>
<hr>
<br>
<br>
<br>
<br>
<br>
<br>
<div id="searchResults">
    <!-- Search results will be displayed here -->
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


</body>
</html>

 <script>
        function viewComments(postId) {
            alert('Viewing comments for post ' + postId);
        }

        function toggleLike(postId, username) {
            // Use AJAX to send a request to likes.php
            $.ajax({
                type: 'POST',
                url: 'likes.php',
                data: {
                    postId: postId,
                    username: username
                },
                success: function (response) {
                    // Assuming likes.php returns the updated like count
                    var likeCountElement = $('#like-count-' + postId);
                    likeCountElement.text(response + ' likes');
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }
    </script>
    
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8;
    padding-top: 100px; /* Adjusted padding to account for fixed navbar */
}

    .navbar {
       background: linear-gradient(to right, #4C4771, #FEFEFE);
        padding: 10px;
        text-align: center;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }
/* CSS for the home icon */
.desktop-icon {
    display: none; /* Hide the icon by default */
}

/* Media query for laptops/desktops (adjust the max-width as needed) */
@media only screen and (min-width: 769px) {
    .desktop-icon {
        display: inline-block; /* Show the icon for laptops/desktops */
    }
}

    .search-container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    #searchInput {
        padding: 10px;
        font-size: 16px;
        margin-right: 10px;
    }

    button {
        padding: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    .icon img {
        height: 30px;
        margin-left: 10px;
    }

    
        .search-container {
            justify-content: center;
        }

        #searchInput {
            margin-right: 0;
        }

        .main-content {
            padding: 20px;
            margin-top: 0px; /* Adjusted margin to account for fixed navbar */
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
            color:#6A679E;;
        }

        .timestamp {
            color: #888;
               margin-left: 110px;
        }

        .post-content {
            margin-bottom: 10px;
           
    max-width: 700px; /* Adjust the value according to your design */
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    white-space: pre-wrap;
}
        

        .post-actions {
    display: flex;
    align-items: center;
    justify-content:flex; /* Add this line */
    gap: 5px;
}


        .like-icon {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

       

        .submit-icon {
            width: 30px;
            height: 30px;
            margin-left: 8px;
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

    
</style>
