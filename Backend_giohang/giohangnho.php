<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "newborn_shop1";

// Tạo kết nối
$conn = new mysqli('127.0.0.1', 'root', '', 'newborn_shop1');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // Kiểm tra sản phẩm đã có trong giỏ hàng
    $stmt = $conn->prepare("SELECT * FROM gio_hang WHERE user_id = ? AND san_pham_id = ?");
    if ($stmt === false) {
        die("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Cập nhật số lượng nếu sản phẩm đã có
        $stmt = $conn->prepare("UPDATE gio_hang SET so_luong = so_luong + 1 WHERE user_id = ? AND san_pham_id = ?");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn UPDATE: " . $conn->error);
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    } else {
        // Thêm mới sản phẩm vào giỏ hàng
        $stmt = $conn->prepare("INSERT INTO gio_hang (user_id, san_pham_id, so_luong) VALUES (?, ?, 1)");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn INSERT: " . $conn->error);
        }
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Chuyển hướng bằng header để tránh lặp lại POST
    header("Location: ../Frontend_web/trangchu.php");
    exit();
} else {
    // Nếu chưa đăng nhập, thông báo và chuyển hướng
    exit();
}
