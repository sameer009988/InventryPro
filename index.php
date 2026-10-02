<?php
session_start();
include 'config/db.php';

if(isset($_POST['done'])){
    $username =  $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `users` WHERE  username='$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username']; 

            echo "<script>alert('Login Successfully!'); window.location.href='dashboard.php';</script>";
        }else{
            echo "<script>alert('Incorrect Password!');</script>";
        }
    }else{
        echo "<script>alert('User not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventryPro Pos Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <form method="post">
        
        <div class="card">

            <div class="heading">
                <h2>InventryPro Pos System</h2>
            </div>
<!--inputs-->
            <div class="inputs">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-container">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="username" id="username" placeholder="Enter Username" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                    </div>
                </div>
            </div>
<!--login button-->
             <div class="btn">
                <button type="submit" name="done">Login</button>
            </div>

            <div class="signup-link">
                <p>Don't have an account? <a href="singup.php">Sign Up here</a></p>
            </div>

            <div class="frgt-link">
               <a href="">Forgot Password?</a>
            </div>

        </div>
    </form>
</body>
</html>