<!-- Display Posts -->
<div class="main-content">
    <!-- Display Posts from Database -->
    <?php
    // The PHP code for fetching and displaying posts is here

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

    // Fetch posts from the 'posts' table in random order
    $sql = "SELECT * FROM posts ORDER BY RAND()";
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
    <title>CampusWeb - Blog</title>
    <!-- Add your stylesheets and any other necessary meta tags or links here -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha256-lv0+Kpx6zYpgbERdUGFmkMT5qFbsrv9iyrYOoEj9RS4=" crossorigin="anonymous" />
   
   
</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <div class="web-app-name">MyCampus Diary</div>
            <div class="search-container">
                <a href="Search.php" class="icon">
                    <img src="img/icons8-search-48.png" alt="Post">
                </a>
            </div>
            <div class="navbar-icons">
                <a href="post.php" class="icon">
                    <img src="img/icons8-create-48.png" alt="Post">
                </a>
                &nbsp; &nbsp;
                <a href="profile.php" class="icon">
                    <img src="img/icons8-male-user-48.png" alt="Profile">
                </a>
            </div>
        </div>
    </nav>
    
   

    
    <!-- Display Posts -->
    <div class="main-content">
        <!-- Display Posts from Database -->
         
        
        <?php
        // The PHP code for fetching and displaying posts is here
        
        ?>
    </div>

   
    <!-- JavaScript Section -->
    
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

           

            likeIcon.toggle();
            
        },
        error: function (error) {
            console.log('Error:', error);
        }
    });
}


</script>
    



</body>

</html>


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

        #searchbar {
            width: calc(100% - 50px);
            border-radius: 26px;
            border: 1px solid #000000;
            padding: 8px 16px;
            background-image: url(https://www.pngarea.com/pngm/109/1164446_searchicon-png-search-icon-png-download.png);
            background-size: contain;
            background-repeat: no-repeat;
            outline: 0;
            background-position: 12px;
            background-size: 16px;
            margin-left: -10px;
        }

        .navbar-icons {
            display: flex;
            gap: 3px;
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

        .post-button {
            background-color: #6A679E;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .main-content {
            padding: 20px;
            margin-top: 80px; /* Adjusted margin to account for fixed navbar */
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
               margin-left: 60px;
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


        footer {
            background: linear-gradient(90deg, #5e5e5f, #414048);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            clear: both;
            position: fixed;
            width: 100%;
            bottom: 0;
        }


       
    </style>