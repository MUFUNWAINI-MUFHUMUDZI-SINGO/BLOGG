<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Moyo</title>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <div class="web-app-name">Chat with MOYO<img src="img/moyo.gif" alt="Home"></div>

            <div class="navbar-icons">
                <a href="blog.php" class="icon">
                    <img src="img/icons8-home-10.png" alt="Home">
                </a>
            </div>
        </div>
    </nav>
    <br>
    <br>
       <br>
    <br>
      
    <div class="chat-container">
        <div class="chat-box" id="chat-box">
            <?php include 'chatbot.php'; ?>
        </div>
        <form id="chat-form" method="post" action="chatbot.php">
            <input type="text" name="message" id="message-input" placeholder="Type your message...">
            <button type="submit">Send</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="script.js"></script>
</body>
</html>

<script>
$(document).ready(function() {
    // Submit the form via AJAX when it's submitted
    $('#chat-form').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the user's message from the input field
        var userMessage = $('#message-input').val();

        // Clear the input field
        $('#message-input').val('');

        // Send the user's message to the server for processing
        $.ajax({
            type: 'POST',
            url: 'chatbot.php',
            data: { message: userMessage },
            success: function(response) {
                // Append the user's message to the chat box with proper line breaks
                $('#chat-box').append('<div class="user-message">' + userMessage.replace(/\n/g, '<br>') + '</div>');

                // Append the bot's response to the chat box
                $('#chat-box').append('<div class="bot-message">' + response + '</div>');

                // Scroll the chat box to the bottom to show the latest messages
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }
        });
    });
});
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
            margin-left: 10px;
        }

        .navbar-icons {
            display: flex;
            gap: 10px;
            margin-right: 10px;
        }

        .navbar-icons a {
            color: white;
            text-decoration: none;
        }

        .icon {
            font-size: 2em;
        }

.chat-container {
    max-width: 600px;
    margin: 20px auto;
    display: flex;
    flex-direction: column;
    height: 80vh;
}

.chat-box {
    flex: 1;
    border: 1px solid #ccc;
    padding: 10px;
    overflow-y: auto;
}

.user-message {
    background-color: #4C4771;
    color: #fff;
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    align-self: flex-end;
}

.bot-message {
    background-color: #ddd;
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    align-self: flex-start;
}

#chat-form {
    display: flex;
    padding: 10px;
    background-color: #fff;
    margin-top:auto;
}

#message-input {
    flex: 1;
    padding: 8px;
    margin-right: 10px;
    border: 1px solid #ccc;
    height: 30px;
}

button {
    padding: 8px 12px;
    background-color: #4C4771;
    color: #fff;
    border: none;
    cursor: pointer;
}


</style>
