<?php


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



if (isset($_GET['id'])) {
    $user_id = $_GET['id'];


    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "Không tìm thấy người dùng.";
        exit;
    }

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Cập nhật dữ liệu người dùng
        $update_query = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssi", $name, $email, $role, $user_id);

        if ($update_stmt->execute()) {
            echo "<p style='color: green;'>Thông tin người dùng đã được cập nhật thành công!</p>";
        } else {
            echo "<p style='color: red;'>Lỗi khi cập nhật thông tin người dùng.</p>";
        }
    }
} else {
    echo "ID người dùng không hợp lệ.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa người dùng</title>
</head>
<body>
    <h2>Chỉnh sửa thông tin người dùng</h2>
    <form method="POST" action="">
        <label for="name">Tên người dùng:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
        
        <label for="role">Vai trò:</label>
        <select id="role" name="role" required>
            <option value="khachhang" <?php echo $user['role'] === '' ? 'selected' : ''; ?>>Khách hàng</option>
            <option value="nhanvien" <?php echo $user['role'] === 'nhanvien' ? 'selected' : ''; ?>>Nhân viên</option>
        </select><br><br>

        <button type="submit">Cập nhật</button>
        <a href="http://localhost/QL_web_new_born/Frontend_web/thongtinnguoidung.php">Quay lại</a>
    </form>
</body>
</html>
