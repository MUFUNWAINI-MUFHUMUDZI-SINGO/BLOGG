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
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Process profile picture upload
    $profilePicturePath = ''; // Default value

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Specify your upload directory
        $uploadFile = $uploadDir . basename($_FILES['profilePicture']['name']);

        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = $uploadFile;
        } else {
            echo "Error uploading profile picture.";
            // Handle the error appropriately
        }
    }

    // Insert user information into the database
    $sql = "INSERT INTO users (email, username, password, profile_picture) 
            VALUES ('$email', '$username', '$password', '$profilePicturePath')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to login.php after successful signup
        header("Location: Sucessignup.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<!-- ... (head and styling) -->

<body>
    <div class="container">
        <h2>Getting Started</h2>
        <form id="signupForm" action="signup.php" method="post" enctype="multipart/form-data">
            <div class="profile-container">
                <div id="profilePreview" class="profile-preview"></div>
            </div>

            <label for="email">
                <img src="img/icons8-letter-with-email-sign-50.png" alt="Email Icon" class="label-icon">
                Email:
            </label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="username">
                <img src="img/icons8-username-24.png" alt="Username Icon" class="label-icon">
                Username:
            </label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required>

            <label for="profilePicture">
                <img src="img/icons8-male-user-48.png" alt="Profile Icon" class="label-icon">
                Choose Profile Picture:
            </label>
            <input type="file" name="profilePicture" id="profilePicture" accept="image/*" onchange="previewImage(this)" required>

             <label for="password">
                <img src="img/icons8-password.gif" alt="Password Icon" class="label-icon">
                Password:
            </label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <label for="confirmPassword">
                <img src="img/icons8-password.gif" alt="Password Icon" class="label-icon">
                Confirm Password:
            </label>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm your password"
                required>
            <button type="submit">Sign Up</button>

            <div class="links">
                <a href="login.php">Already have an account? Login here</a>
                <br>
                <a href="sendOTP.php">Forgot your Password? Click here</a>
                <br>
                <a href="https://www.linkedin.com/in/mufunwaini-mufhumudzi-542981240">Support/Help</a>
            </div>
        </form>
    </div>
</body>

<script>
    function previewImage(input) {
        var profilePreview = document.getElementById('profilePreview');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                profilePreview.style.backgroundImage = 'url(' + e.target.result + ')';
                profilePreview.style.backgroundSize = 'cover';
                profilePreview.style.backgroundPosition = 'center';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

     function validatePassword() {
                    var password = document.getElementById("password").value;
                    var confirmPassword = document.getElementById("confirmPassword").value;

                    if (password !== confirmPassword) {
                        alert("Passwords do not match!");
                        return false;
                    }

                    return true;
                }

                // Attach the validation function to the form's onsubmit event
                document.getElementById("signupForm").onsubmit = function () {
                    return validatePassword();
                };
</script>

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

   

    .profile-container {
        position: relative;
        margin-bottom: 20px;
        text-align: center;
    }

    .profile-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #6A679E;
        background-size: cover;
        background-position: center;
        margin: 0 auto;
        margin-bottom: 10px;
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