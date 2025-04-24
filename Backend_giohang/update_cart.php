<?php
// Kết nối cơ sở dữ liệu

$servername = "localhost"; // Địa chỉ server (thường là localhost)
$username = "root"; // Tên người dùng MySQL
$password = ""; // Mật khẩu MySQL
$database = "newborn_shop1"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli('127.0.0.1', 'root', '', 'newborn_shop1');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} 



session_start();


if (isset($_POST['id'], $_POST['action'])) {

    $cart_id = $_POST['id'];  
    $action = $_POST['action'];  
    $redirect = $_POST['redirect'];

    
    try {
    
        $sql = "SELECT * FROM gio_hang WHERE id = $cart_id";
        $result = $conn->query($sql);

        
        if ($result->num_rows > 0) {
            $cart_item = $result->fetch_assoc(); 

            $current_quantity = $cart_item['so_luong'];

            
            if ($action == 'increase') {
                $new_quantity = $current_quantity + 1; 
            } elseif ($action == 'decrease' && $current_quantity > 1) {
                $new_quantity = $current_quantity - 1; 
            } else {
                $new_quantity = $current_quantity; 
            }

        
            $update_sql = "UPDATE gio_hang SET so_luong = $new_quantity WHERE id = $cart_id";
            $conn->query($update_sql); 


            $_SESSION['message'] = "Cập nhật giỏ hàng thành công!";
        } else {
            
            $_SESSION['error'] = "Sản phẩm không tồn tại trong giỏ hàng!";
        }

    } catch (Exception $e) {
    
        $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }
} else {

    $_SESSION['error'] = "Dữ liệu không hợp lệ!";
}


    if ($redirect == 'chitietgiohang') {
       header('Location: ../Frontend_web/chitietgiohang.php');
    } else {
        header('Location: ../Frontend_web/trangchu.php'); 
    }
    exit();
?>