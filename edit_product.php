<?php
session_start();
include "config/db.php";

$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id='$id'");
$product = mysqli_fetch_assoc($res);

if(isset($_POST['update_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $price = mysqli_real_escape_string($conn, $_POST['p_price']);
    $stock = mysqli_real_escape_string($conn, $_POST['p_stock']); 
    $cat_name = mysqli_real_escape_string($conn, $_POST['p_cat']);
    
    $check_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = '$cat_name'");
    if(mysqli_num_rows($check_cat) > 0){
        $cat_id = mysqli_fetch_assoc($check_cat)['id'];
    } else {
        mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$cat_name')");
        $cat_id = mysqli_insert_id($conn);
    }

    $img_query = "";
    if(!empty($_FILES['p_img']['name'])){
        $img_name = time() . "_" . $_FILES['p_img']['name'];
        move_uploaded_file($_FILES['p_img']['tmp_name'], "imegs/products/" . $img_name);
        $img_query = ", image='$img_name'";
    }

    $update = "UPDATE products SET name='$name', category_id='$cat_id', price='$price', stock='$stock' $img_query WHERE id='$id'";
    
    if(mysqli_query($conn, $update)){
        echo "<script>alert('Product Updated!'); window.location.href='inventory.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product - InventryPro</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="bg-blur"></div>
    <div class="container">
        <main class="main-content" style="margin-left: 0; width: 100%; display: flex; justify-content: center;">
            <div class="content-card" style="width: 500px; margin-top: 50px;">
                <h3>Edit Product</h3>
                <br>
                <form method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom: 15px;">
                        <label>Product Name</label>
                        <input type="text" name="p_name" value="<?php echo $product['name']; ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Category</label>
                        <input type="text" name="p_cat" value="<?php echo $product['cat_name']; ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;" required>
                    </div>
                    <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                        <div style="flex:1;">
                             <label>Price</label>
                             <input type="number" step="0.01" name="p_price" value="<?php echo $product['price']; ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;" required>
                        </div>
                        <div style="flex:1;">
                             <label>Stock</label>
                             <input type="number" name="p_stock" value="<?php echo $product['stock']; ?>" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ddd;" required>
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Change Image (Optional)</label><br>
                        <img src="imegs/products/<?php echo $product['image']; ?>" width="60" style="margin-bottom: 10px; border-radius: 5px;"><br>
                        <input type="file" name="p_img">
                    </div>
                    <button type="submit" name="update_product" style="background:#3498db; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">Update Product</button>
                    <a href="inventory.php" style="margin-left:10px; text-decoration:none; color:#666;">Cancel</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>