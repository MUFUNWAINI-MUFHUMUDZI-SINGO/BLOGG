<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>

<body>
    <div class="form-container">
        <h2>Reset Password.</h2>
        <br>
        <br>
        
        <form action="signup.php" method="POST" onsubmit="return validateSignupForm()">
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="icon">
                    <img src="img/icons8-letter-with-email-sign-50.png" alt="Email Icon">
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <span id="emailError" class="error"></span>
            </div>

            <div class="form-group">
                <label for="pin">One Time Pin:</label>
                <div class="icon">
                    <img src="img/icons8-password.gif" alt="Password Icon">
                    <input type="pin" id="pin" name="OTP" placeholder="Check email for OTP" required>
                </div>
                <span id="Incorrect OTP" class="error"></span>
            </div>

           

                 <div class="form-group">
                <label for="password">New Password:</label>
                <div class="icon">
                    <img src="img/icons8-password.gif" alt="Password Icon">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <div class="icon">
                    <img src="img/icons8-password.gif" alt="Password Icon">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <span id="passwordMatchError" class="error"></span>
            </div>

            <div class="form-group">
                <button type="submit">Save</button>
            </div>

            <div class="form-group">
                <a href="login.php">Already have an account? Login here.</a>
            </div>
        </form>
    </div>

    <script>
    function validateSignupForm() {
        var username = document.getElementById('username').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate username
        if (username.trim() === '') {
            document.getElementById('usernameError').innerText = 'Username is required';
            return false;
        } else {
            document.getElementById('usernameError').innerText = '';
        }

        // Validate email format
        if (!emailRegex.test(email)) {
            document.getElementById('emailError').innerText = 'Invalid email format';
            return false;
        } else {
            document.getElementById('emailError').innerText = '';
        }

        // Validate password matching
        if (password !== confirmPassword) {
            document.getElementById('passwordMatchError').innerText = 'Passwords do not match';
            return false;
        } else {
            document.getElementById('passwordMatchError').innerText = '';
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



