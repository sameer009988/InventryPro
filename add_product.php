<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['username'])){
    header("location: index.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$user_res = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id='$u_id'");
$user_data = mysqli_fetch_assoc($user_res);
$pic = $user_data['profile_pic'];

if(isset($_POST['save_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $stock = mysqli_real_escape_string($conn, $_POST['p_stock']); // Naya field
    $cat_name = mysqli_real_escape_string($conn, $_POST['p_cat']);
    
    $check_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = '$cat_name'");
    if(mysqli_num_rows($check_cat) > 0){
        $cat_id = mysqli_fetch_assoc($check_cat)['id'];
    } else {
        mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$cat_name')");
        $cat_id = mysqli_insert_id($conn);
    }

    $img_name = "default_prod.png";
    if(!empty($_FILES['p_img']['name'])){
        $img_name = time() . "_" . $_FILES['p_img']['name'];
        if(!is_dir("imegs/products/")){ mkdir("imegs/products/", 0777, true); }
        move_uploaded_file($_FILES['p_img']['tmp_name'], "imegs/products/" . $img_name);
    }

    $insert_sql = "INSERT INTO products (name, category_id, price, stock, image) VALUES ('$name', '$cat_id', '$price', '$stock', '$img_name')";
    
    if(mysqli_query($conn, $insert_sql)){
        echo "<script>alert('Product Saved with Stock!'); window.location.href='inventory.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InventryPro - Add Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="container">
        <nav class="sidebar">
            <div class="sidebar-header"><i class="fa-solid fa-boxes-stacked"></i> <span>InventryPro</span></div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li class="active"><a href="inventory.php"><i class="fa-solid fa-box"></i> Inventory</a></li>
                <li><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="logout"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header><h1>Add New Product</h1></header>
            <div class="content-card">
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom:15px;">
                        <label>Product Name</label>
                        <input type="text" name="p_name" class="form-control" style="width:100%; padding:10px;" required>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label>Category</label>
                        <input type="text" name="p_cat" class="form-control" style="width:100%; padding:10px;" required>
                    </div>
                    <div style="margin-bottom:15px; display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label>Price (PKR)</label>
                            <input type="number" step="0.01" name="p_price" class="form-control" style="width:100%; padding:10px;" required>
                        </div>
                        <div style="flex: 1;">
                            <label>Quantity (Stock)</label>
                            <input type="number" name="p_stock" class="form-control" style="width:100%; padding:10px;" required>
                        </div>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label>Product Image</label>
                        <input type="file" name="p_img" class="form-control" style="width:100%;">
                    </div>
                    <button type="submit" name="save_product" class="btn-save" style="background:#2ecc71; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Save Product</button>
                    <a href="inventory.php" style="margin-left:10px; text-decoration:none; color:gray;">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>