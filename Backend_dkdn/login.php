<?php
session_start();
header('Content-Type: application/json'); // Đặt header JSON ngay đầu tiên


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


// Chỉ xử lý khi là phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ"]);
    exit();
}

// Lấy và kiểm tra dữ liệu đầu vào
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


// Kiểm tra email có đúng định dạng không
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Email không hợp lệ"]);
    exit();

}

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ email và mật khẩu"]);
    exit();
}

// Kiểm tra kết nối và chuẩn bị câu lệnh truy vấn
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Lỗi kết nối cơ sở dữ liệu: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Kiểm tra xem email có tồn tại không


// Lấy thông tin người dùng
$stmt->bind_result($id, $name, $hashedPassword, $role);
$stmt->fetch();

// Kiểm tra mật khẩu
if (!password_verify($password, $hashedPassword)) {
    echo json_encode(["status" => "error", "message" => "Mật khẩu không đúng"]);
    $stmt->close();
    exit();
}

// Lưu thông tin người dùng vào session
$_SESSION['user_id'] = $id;
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;

// Xác định trang chuyển hướng dựa trên vai trò
$redirectPage = match ($role) {
    'admin'    => "../Frontend_web/giaodienql.php",
    'nhanvien' => "../Frontend_web/giaodienql1.php",
    default    => "../Frontend_web/trangchu.php",
};

// Phản hồi JSON
echo json_encode([
    "status" => "success",
    "message" => "Đăng nhập thành công",
    "redirect" => $redirectPage
]);

// Đóng statement và kết nối
$stmt->close();
$conn->close();
