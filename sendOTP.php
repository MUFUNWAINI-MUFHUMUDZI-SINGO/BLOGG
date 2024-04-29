<!--
php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Generate a random OTP (You may use a more secure method)
    $otp = mt_rand(100000, 999999);

    // Save the OTP to a session or a database for later verification
    session_start();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    try {
        $mail = new PHPMailer(true);

        // Enable verbose debug output
        $mail->SMTPDebug = 2;

        // Set mailer to use SMTP
        $mail->isSMTP();
        $mail->Host = 'your_network_solutions_smtp_host';  // Replace with Network Solutions SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_network_solutions_smtp_username';  // Replace with your username
        $mail->Password = 'your_network_solutions_smtp_password';  // Replace with your password
        $mail->SMTPSecure = 'tls';  // Enable TLS encryption
        $mail->Port = 587;  // Replace with the appropriate port

        // Set additional headers, including the 'From' header
        $mail->setFrom('OTP@unicampdiary.com', 'Your Name');
        $mail->addAddress($email);  // Add a recipient

        // Set email format to HTML
        $mail->isHTML(true);

        // Set the subject and body
        $mail->Subject = 'Your OTP for verification';
        $mail->Body = "Your OTP is: $otp";

        // Send the email
        $mail->send();

        // Redirect to blog.php after successfully sending OTP
        header('Location: Forgotpass.php');
        exit();
    } catch (Exception $e) {
        echo "Error sending OTP. Please try again. Error: {$mail->ErrorInfo}";
    }
}


            // Conditionally redirect to blog.php if OTP was sent
            if ($otpSent) {
                echo '<script>window.location.href = "Forgotpass.php";</script>';
            }
            

-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>

<body>
    <div class="form-container">
        <h2>Get OTP to reset password</h2>
        <br>
        <br>

        <form action="sendOTP.php" method="POST" onsubmit="return validateSignupForm()">
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="icon">
                    <img src="img/icons8-letter-with-email-sign-50.png" alt="Email Icon">
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <span id="emailError" class="error"></span>
            </div>

            <div class="form-group">
                <button type="submit">Receive OTP</button>
            </div>

            <div class="form-group">
                <a href="login.php">Already have an account? Login here.</a>
            </div>

            
        </form>
    </div>

    <script>
        function validateSignupForm() {
            var email = document.getElementById('email').value;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Validate email format
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').innerText = 'Invalid email format';
                return false;
            } else {
                document.getElementById('emailError').innerText = '';
            }

            // Additional validation or actions can be added here if needed

            return true; // Form will be submitted if all validations pass
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
            width: calc(100% - 30px); /* Adjusted the width and added a calculation */
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 40px; /* Added margin-left */
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
        transition: background-color 0.3s; /* Added transition for smooth hover effect */
    }

    .form-group button:hover {
        background-color: #242331; /* Change the background color on hover */
    }

    .form-group a {
        display: block;
        text-align: center;
        margin-top: 10px;
        color: #6A679E;
        text-decoration: none;
    }
    .form-group a:hover {
        color: #DF5349; /* Change the color on hover */
    }
    </style>

