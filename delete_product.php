<?php
session_start();
include "config/db.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    $res = mysqli_query($conn, "SELECT image FROM products WHERE id='$id'");
    $row = mysqli_fetch_assoc($res);
    $old_image = $row['image'];

    $delete = mysqli_query($conn, "DELETE FROM products WHERE id='$id'");

    if($delete){

    if($old_image != 'default_prod.png' && file_exists("imegs/products/".$old_image)){
            unlink("imegs/products/".$old_image);
        }
        header("location: inventory.php");
    }
}
?>