<?php
session_start();


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



$userId = $_SESSION['user_id'];
$productId = $_POST['product_id'];


$query = "DELETE FROM gio_hang WHERE user_id = ? AND san_pham_id = ?";
$stmt = $conn->prepare($query);  
$stmt->bind_param("ii", $userId, $productId); // 

if ($stmt->execute()) {
    
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

    if ($redirect == 'chitietgiohang') {
        
        header('Location: ../Frontend_web/chitietgiohang.php');
    } else {
      
        header('Location: ../Frontend_web/trangchu.php');
    }
    exit();
} else {
    
    echo "Có lỗi xảy ra khi xóa sản phẩm khỏi giỏ hàng.";
}
?>
