<?php
include 'config/db.php';

if(isset($_POST['signup'])){
    
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
    echo "<script>alert('Passwords do not match!');</script>";
    }else{

    $check_email = "SELECT * FROM `users` WHERE email='$email' ";
    $result = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($result) > 0){
        
        echo "<script>alert('Email already registered!');</script>";
    }else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Registration Successful!'); window.location='index.php';</script>";
        }else{
            echo "Error:" .mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventryPro - Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <form id="signupForm" method="post">
        <div class="card">
            <div class="heading">
                <h2>Create Account</h2>
            </div>

            <div class="inputs">
                <!-- Gmail -->
                <div class="input-group">
                    <label>Email</label>
                    <div class="input-container">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" placeholder="example@gmail.com" name="email" required>
                    </div>
                </div>

                <!-- Username -->
                <div class="input-group">
                    <label>Username</label>
                    <div class="input-container">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" placeholder="Choose a username" name="username" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label>Password</label>
                    <div class="input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create password" required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <label>Confirm Password</label>
                    <div class="input-container">
                        <i class="fa-solid fa-shield-check"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                    </div>
                </div>

                <!-- Captcha Section -->
                <div class="captcha-area">
                    <div class="captcha-img">
                        <span id="captcha-code"></span>
                        <button type="button" id="reload-captcha"><i class="fa-solid fa-rotate-right"></i></button>
                    </div>
                    <div class="input-container">
                        <input type="text" id="captcha-input" placeholder="Enter Captcha" required>
                    </div>
                </div>
            </div>

            <div class="btn">
                <button type="submit" name="signup">Sign Up</button>
            </div>

            <div class="signup-link">
                <p>Already have an account? <a href="index.php">Login here</a></p>
            </div>
        </div>
    </form>

    <script src="js/signup.js"></script>
</body>
</html>