<?php
session_start();
include "config/db.php";
if(!isset($_SESSION['username'])){ header("location: index.php"); exit(); }

$u_id = $_SESSION['user_id'];
$user_res = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id='$u_id'");
$user_data = mysqli_fetch_assoc($user_res);
$pic = $user_data['profile_pic'];

$sql = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
$products = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InventryPro - Inventory</title>
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
                <li><a href="pos.php"><i class="fa-solid fa-cart-shopping"></i> POS</a></li>
                <li class="active"><a href="inventory.php"><i class="fa-solid fa-box"></i> Inventory</a></li>
                <li><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="logout"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </nav>
        <main class="main-content">
            <header>
                <h1>Inventory Management</h1>
                <div class="user-profile">
                    <div class="avatar">
                        <?php if(!empty($pic) && $pic != 'default.png'): ?>
                            <img src="imegs/<?php echo $pic; ?>" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>
            <div class="content-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>All Products (<?php echo mysqli_num_rows($products); ?>)</h3>
                    <a href="add_product.php" style="background: #2ecc71; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">+ Add Item</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th> <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><img src="imegs/products/<?php echo $row['image']; ?>" width="50" style="border-radius: 5px;"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['cat_name']; ?></td>
                            <td>PKR<?php echo $row['price']; ?></td>
                            <td><strong><?php echo $row['stock']; ?></strong></td> <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" style="color: #3498db;"><i class="fa-solid fa-pen"></i></a> | 
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>