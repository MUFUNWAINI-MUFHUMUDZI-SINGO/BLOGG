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
            header("Location: profile.php");
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
    <title>Login</title>
</head>

<body>
    <div class="form-container">
        <br>
        <br>
        <h2>User registered successfully<br>Use your credentials to log into your account.</h2>
        <br>
        <br>
        <br>
        <br>
        <form action="Sucessignup.php" method="POST" onsubmit="return validateLoginForm()">
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="icon">
                    <img src="img/icons8-letter-with-email-sign-50.png" alt="Email Icon">
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <span id="emailError" class="error"></span>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <div class="icon">
                    <img src="img/icons8-password.gif" alt="Password Icon">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="form-group">
                <button type="submit">Login</button>
            </div>

            
        </form>
    </div>

    <script>
        function validateLoginForm() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Validate email format
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').innerText = 'Invalid email format';
                return false;
            } else {
                document.getElementById('emailError').innerText = '';
            }

            return true;
        }
    </script>
    </body>

</html>



    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('img/sinn.gif');
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            height: 600px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 800px; 
            
        }

         @media (max-width: 768px) {
    /* Add styles for smaller screens (e.g., mobile devices) */
    .form-container {
        width: 100%;
        margin-right: 0; /* Adjust margins for smaller screens */
    }
   }

        .form-container h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input {
            width: calc(100% - 30px);
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 40px;
        }

        .form-group .icon {
            position: relative;
        }

        .form-group .icon img {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
        }

        .form-group button {
            background-color: #6A679E;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .form-group button:hover {
            background-color: #242331;
        }

        .form-group a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #6A679E;
            text-decoration: none;
        }

        .form-group a:hover {
            color: #DF5349;
        }
       
    </style>












