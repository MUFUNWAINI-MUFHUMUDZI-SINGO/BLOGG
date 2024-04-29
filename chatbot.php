<?php
// Start or resume the session
session_start();

// Establish a connection to the database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'MYCAMPD';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the threshold for deleting messages (e.g., messages older than 7 days)
$threshold = date('Y-m-d H:i:s', strtotime('-1 days'));

// Delete old messages
$deleteQuery = "DELETE FROM chat_history WHERE timestamp < '$threshold'";
mysqli_query($conn, $deleteQuery);

// Function to get a bot response based on user input
function getBotResponse($userMessage, $conn) {
    // Sanitize user input to prevent SQL injection
    $sanitizedUserMessage = mysqli_real_escape_string($conn, strtolower($userMessage));

    // Check for an exact match using =
    $query = "SELECT response FROM chat_responses WHERE question = '$sanitizedUserMessage' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['response'];
    } else {
        // If no exact match is found, use the original method with LIKE for partial matches
        $query = "SELECT response FROM chat_responses WHERE question LIKE '%$sanitizedUserMessage%' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['response'];
        } else {
            // If no specific response is found, provide a default response
            return "I received your message: '$userMessage'. I am a simple bot!";
        }
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = mysqli_real_escape_string($conn, $_POST['message']);

    // Get user ID from the session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    // Use prepared statements to safely insert user message into the 'chat_history' table
    $userInsertQuery = $conn->prepare("INSERT INTO chat_history (user_id, content, is_user_message) VALUES (?, ?, true)");
    $userInsertQuery->bind_param("is", $userId, $userMessage);
    $userInsertQuery->execute();

    // Process the user message and get the chatbot's response
    $botResponse = getBotResponse($userMessage, $conn);

    // Use prepared statements to safely insert bot response into the 'chat_history' table
    $botInsertQuery = $conn->prepare("INSERT INTO chat_history (user_id, content, is_user_message) VALUES (?, ?, false)");
    $botInsertQuery->bind_param("is", $userId, $botResponse);
    $botInsertQuery->execute();
}

// Display a default welcome message
$linkedinProfile = 'https://www.linkedin.com/in/mufunwaini-mufhumudzi-542981240';

echo "<div class='bot-message'>Welcome! My name is Moyo, a simple chatbot. For more info, 
click <a href='$linkedinProfile'>HERE</a>.
</div>";
echo "<div class='bot-message'>What do you need assistant with?<br>
1 reset wifi password<br>
2 what what.<br>
3 what waht.<br>
4 what what.<br>
5 what what.<br>
6 what what.<br>
7 what what.<br>
8 more...  </div>";

// Fetch and display the user's chat history
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$chatHistoryQuery = "SELECT content, is_user_message FROM chat_history WHERE user_id = $userId ORDER BY timestamp";
$result = mysqli_query($conn, $chatHistoryQuery);

while ($row = mysqli_fetch_assoc($result)) {
    $messageClass = $row['is_user_message'] ? 'user-message' : 'bot-message';
    echo "<div class='$messageClass'>{$row['content']}</div>";
}

// Close the database connection
mysqli_close($conn);
?>



