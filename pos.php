<?php
session_start();
include "config/db.php";

if(!isset($_SESSION['username'])){ header("location: index.php"); exit(); }

if(isset($_POST['final_checkout'])){
    $items = json_decode($_POST['cart_data'], true);
    if(!empty($items)){
        foreach($items as $item){
            $p_id = $item['id'];
            $qty = $item['qty'];
            $total = $item['total'];
            mysqli_query($conn, "INSERT INTO sales (product_id, quantity, total_price) VALUES ('$p_id', '$qty', '$total')");
            mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id='$p_id'");
        }
        echo "<script>alert('Transaction Saved Successfully!'); window.location.href='pos.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>InventryPro - POS Terminal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/pos_style.css">
    <style>
        @media print {
            body * { visibility: hidden; }
            #receipt-print, #receipt-print * { visibility: visible; }
            #receipt-print { position: absolute; left: 0; top: 0; width: 100%; }
        }
        #receipt-print { display: none; font-family: monospace; padding: 20px; color: black; background: white; }
        .cart-table { width: 100%; border-collapse: collapse; color: white; }
        .cart-table th, .cart-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: left; }
        .remove-btn { color: #ff7675; cursor: pointer; border: none; background: none; font-size: 18px; }
        .remove-btn:hover { color: #ff4757; }
    </style>
</head>
<body>
    <div class="bg-blur"></div>
    <div class="container">
        <nav class="sidebar">
            <div class="sidebar-header"><i class="fa-solid fa-boxes-stacked"></i> <span>InventryPro</span></div>
            <ul class="nav-links">
                <li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
                <li class="active"><a href="pos.php"><i class="fa-solid fa-cart-shopping"></i> POS</a></li>
                <li><a href="inventory.php"><i class="fa-solid fa-box"></i> Inventory</a></li>
                <li><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="logout"><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header><h1>POS Terminal</h1></header>
            <div class="pos-layout">
                <div class="pos-glass-card">
                    <h3>Add Products</h3>
                    <div class="pos-form">
                        <select id="p_select" class="pos-input-styled">
                            <option value="">Select Product...</option>
                            <?php 
                            $products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0");
                            while($p = mysqli_fetch_assoc($products)): ?>
                                <option value="<?php echo $p['id']; ?>" data-name="<?php echo $p['name']; ?>" data-price="<?php echo $p['price']; ?>">
                                    <?php echo $p['name']; ?> (PKR <?php echo $p['price']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="number" id="p_qty" value="1" min="1" style="margin-top:10px; width: 100%; padding: 10px; border-radius: 8px;">
                        <button type="button" onclick="addToCart()" class="btn-checkout" style="background:#3498db; margin-top:15px;">Add to Cart</button>
                    </div>
                </div>

                <div class="pos-glass-card" style="flex: 1.5;">
                    <h3>Cart Items</h3>
                    <table class="cart-table">
                        <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr></thead>
                        <tbody id="cart-body"></tbody>
                    </table>
                    <div class="total-box">
                        <span>Grand Total:</span>
                        <h2 id="grand-total">PKR 0.00</h2>
                    </div>
                    <form method="POST" onsubmit="preparePrint()">
                        <input type="hidden" name="cart_data" id="cart_data_input">
                        <button type="submit" name="final_checkout" class="btn-checkout">Complete & Print</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <div id="receipt-print">
        <center><h2>InventryPro</h2><p>Invoice</p></center>
        <hr>
        <table style="width:100%;">
            <thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead>
            <tbody id="receipt-body"></tbody>
        </table>
        <hr>
        <h3 id="receipt-total" style="text-align:right;"></h3>
    </div>

    <script>
        let cart = [];
        function addToCart() {
            const select = document.getElementById('p_select');
            const qtyInput = document.getElementById('p_qty');
            if(!select.value) return;
            const opt = select.options[select.selectedIndex];
            const item = {
                id: select.value,
                name: opt.getAttribute('data-name'),
                price: parseFloat(opt.getAttribute('data-price')),
                qty: parseInt(qtyInput.value),
                total: parseFloat(opt.getAttribute('data-price')) * parseInt(qtyInput.value)
            };
            cart.push(item);
            renderCart();
        }

        // REMOVE ITEM FUNCTION
        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById('cart-body');
            const rBody = document.getElementById('receipt-body');
            let total = 0;
            body.innerHTML = "";
            rBody.innerHTML = "";
            cart.forEach((item, index) => {
                total += item.total;
                body.innerHTML += `<tr>
                    <td>${item.name}</td>
                    <td>PKR ${item.price}</td>
                    <td>${item.qty}</td>
                    <td>PKR ${item.total}</td>
                    <td><button class="remove-btn" onclick="removeItem(${index})"><i class="fa-solid fa-trash-can"></i></button></td>
                </tr>`;
                rBody.innerHTML += `<tr><td>${item.name}</td><td>${item.qty}</td><td>PKR ${item.total}</td></tr>`;
            });
            document.getElementById('grand-total').innerText = 'PKR ' + total.toFixed(2);
            document.getElementById('receipt-total').innerText = 'Total: PKR ' + total.toFixed(2);
            document.getElementById('cart_data_input').value = JSON.stringify(cart);
        }

        function preparePrint() {
            document.getElementById('receipt-print').style.display = 'block';
            window.print();
        }
    </script>
</body>
</html>