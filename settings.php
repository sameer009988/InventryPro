<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['username'])){
    header("location: index.php");
    exit();
}

if(isset($_POST['upload_pic'])){
    $target_dir = "imegs/"; 
    $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $target_file = $target_dir . $file_name;

    if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)){
        $user_id = $_SESSION['user_id'];
        $update_sql = "UPDATE users SET profile_pic='$file_name' WHERE id='$user_id'";
        mysqli_query($conn, $update_sql);
        echo "<script>alert('Profile Picture Updated!'); window.location.href='settings.php';</script>";
    }
}

$u_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id='$u_id'");
$user_data = mysqli_fetch_assoc($res);
$pic = $user_data['profile_pic'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventryPro - Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>InventryPro</span>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li><a href="pos.php"><i class="fa-solid fa-cart-shopping"></i> POS</a></li>
                <li><a href="inventory.php"><i class="fa-solid fa-box"></i> Inventory</a></li>
                <li class="active"><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="logout"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>User Settings</h1>
                <div class="user-profile">
                    <!-- Dynamic Avatar in Header -->
                    <div class="avatar">
                        <?php if($pic && $pic != 'default.png' && file_exists("imegs/".$pic)): ?>
                            <img src="imegs/<?php echo $pic; ?>" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="content-card">
                <h3>Profile Information</h3>
                <br>
                <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
                <p><strong>User ID:</strong> #<?php echo $_SESSION['user_id']; ?></p>
                
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                <!-- Image Upload Form (Now inside the card) -->
                <form method="POST" enctype="multipart/form-data" style="margin-bottom: 25px; background: #f9f9f9; padding: 15px; border-radius: 8px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Update Profile Picture:</label>
                    <input type="file" name="profile_image" accept="image/*" required style="margin-bottom: 10px;">
                    <button type="submit" name="upload_pic" style="padding: 8px 15px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        <i class="fa-solid fa-upload"></i> Upload Now
                    </button>
                </form>
                
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <h3>Change Password</h3>
                <form style="margin-top: 15px;">
                    <div style="margin-bottom: 10px;">
                        <input type="password" placeholder="Current Password" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                    <div style="margin-bottom: 10px;">
                        <input type="password" placeholder="New Password" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                    <button type="button" style="padding: 10px 20px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer;">Update Password</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>