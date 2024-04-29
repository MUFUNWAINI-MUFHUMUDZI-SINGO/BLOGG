<?php
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate and sanitize user input as needed

    // Check user credentials against the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the entered password against the stored hash
        if (password_verify($password, $row['password'])) {
            // Password is correct, consider the user logged in

            // Start a session and store user information if needed
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];

            // Redirect to profile.php
            header("Location: blog.php");
            exit();
        } else {
            echo "Incorrect email or password";
        }
    } else {
        echo "User not found";
    }
}

// Close the database connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <h2>Welcome Back!</h2>
        <br>
        <br>
        <br>
        <br>
        <br>
        
        <form id="loginForm" action="login.php" method="post">
            <label for="email">
                <img src="img/icons8-letter-with-email-sign-50.png" alt="Email Icon" class="label-icon">
                Email:
            </label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">
                <img src="img/icons8-password.gif" alt="Password Icon" class="label-icon">
                Password:
            </label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
<br>
<br>
            <button type="submit">Login</button>
            <br>
            <br>
            <br>
            <br>


            <div class="links">
                <a href="signup.php">Don't have an account? Sign up here</a>
                <br>
                <a href="sendOTP.php">Forgot your Password? Click here</a>
                <br>
            <a href="https://www.linkedin.com/in/mufunwaini-mufhumudzi-542981240">Support/Help</a>

            </div>
        </form>
    </div>
</body>

</html>

<style>
    /* styles.css */
 body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('img/sinn.gif');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    width: 90%;
    max-width: 600px;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin: 0 auto; /* Center the container horizontally */
}

form {
    display: flex;
    flex-direction: column;
}

/* Add a media query for larger screens (e.g., desktop) */
@media (min-width: 768px) {
    .container {
        
    margin-left: 20px; /* Reset margin to align to the start on larger screens */
    }
}

label {
    margin-top: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
}

.label-icon {
    margin-right: 10px;
    max-width: 20px;
    max-height: 20px;
}

input {
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px;
    margin-top: 10px;
    background-color: #6A679E;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

h2 {
    color: #6A679E;
    margin-bottom: 20px;
}

button:hover {
    background-color: #121215;
}

.links {
    margin-top: 20px;
}

.links a {
    margin-right: 5px;
    color: #6A679E;
    text-decoration: none;
}

.links a:hover {
    text-decoration: underline;
    color: brown;
}

</style>