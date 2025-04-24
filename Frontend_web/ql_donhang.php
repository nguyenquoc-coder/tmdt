<?php
// Start session to access session variables
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


// Check if the user is logged in (optional if this page is for admin)
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhapky.php");
    exit();
}

// Query to retrieve completed orders
$query = "
    SELECT t.id AS order_id, t.hoTen, t.email, t.soDienThoai, t.diaChi, t.ngayThanhToan, t.tongTien
    FROM thanhtoan t
    ORDER BY t.ngayThanhToan DESC
";

$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Error retrieving orders: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đơn hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #d3bca7; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .order-total { color: #d3bca7; font-weight: bold; }
        .btn-primary {
        background-color: #a39074;
        border: none;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách đơn hàng đã thanh toán</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Ngày thanh toán</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each order row
                if ($result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['hoTen']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['soDienThoai']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['diaChi']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['ngayThanhToan']) . "</td>";
                        echo "<td class='order-total'>" . number_format($order['tongTien'], 0, ',', '.') . " VND</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center; padding: 20px;'>Chưa có đơn hàng nào.</td></tr>";
                }
                
                ?>
            </tbody>
        </table>
        <hr>

        
        <a href="./Menu1.php" class="btn btn-primary">Quay lại</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
