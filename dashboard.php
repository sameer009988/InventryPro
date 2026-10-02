<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['username'])){ header("location: index.php"); exit(); }

$u_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT profile_pic FROM users WHERE id='$u_id'");
$user_data = mysqli_fetch_assoc($res);
$pic = $user_data['profile_pic'];

// 1. Real-time Total Sales Calculate 
$sales_res = mysqli_query($conn, "SELECT SUM(total_price) as total_revenue FROM sales");
$sales_data = mysqli_fetch_assoc($sales_res);
$total_sales = $sales_data['total_revenue'] ?? 0;

// 2. Total Items Count
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_count = mysqli_fetch_assoc($total_res)['total'];

// 3. Low Stock Count
$low_res = mysqli_query($conn, "SELECT COUNT(*) as low FROM products WHERE stock < 10");
$low_count = mysqli_fetch_assoc($low_res)['low'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InventryPro - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="bg-blur"></div>

    <div class="container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>InventryPro</span>
            </div>
            <ul class="nav-links">
                <li class="active"><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li><a href="pos.php"><i class="fa-solid fa-cart-shopping"></i> POS</a></li>
                <li><a href="inventory.php"><i class="fa-solid fa-box"></i> Inventory</a></li>
                <li><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="logout"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header>
                <h1>Welcome Back, <?php echo $_SESSION['username']; ?>!</h1>
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

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fa-solid fa-money-bill-trend-up" style="color:#2ecc71;"></i>
                    <div>
                        <h3>Total Sales</h3>
                        <p>PKR <?php echo number_format($total_sales, 2); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fa-solid fa-boxes-packing"></i>
                    <div>
                        <h3>Total Items</h3>
                        <p><?php echo $total_count; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#e67e22;"></i>
                    <div>
                        <h3>Low Stock</h3>
                        <p class="warning"><?php echo $low_count; ?> Items</p>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <h3>Recent Transactions</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $recent_sales = mysqli_query($conn, "SELECT s.*, p.name FROM sales s JOIN products p ON s.product_id = p.id ORDER BY s.id DESC LIMIT 5");
                        
                        if(mysqli_num_rows($recent_sales) > 0) {
                            while($sale = mysqli_fetch_assoc($recent_sales)): ?>
                            <tr>
                                <td>#<?php echo $sale['id']; ?></td>
                                <td><?php echo $sale['name']; ?></td>
                                <td><?php echo date("M d, Y", strtotime($sale['sale_date'])); ?></td>
                                <td>PKR <?php echo number_format($sale['total_price'], 2); ?></td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                            <?php endwhile; 
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>No transaction yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>