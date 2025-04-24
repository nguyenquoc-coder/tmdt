<?php
header('Content-Type: application/json');

// Thông tin kết nối
$conn = new mysqli('127.0.0.1', 'root', '', 'newborn_shop1');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Kết nối thất bại: ' . $conn->connect_error]);
    exit();
}

// Xử lý POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log toàn bộ dữ liệu POST
    error_log('POST data: ' . print_r($_POST, true));

    // Kiểm tra xem email có trong $_POST không
    if (!isset($_POST['email'])) {
        error_log('Lỗi: $_POST[\'email\'] không tồn tại');
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: Trường email không được gửi!']);
        exit();
    }

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = 'khachhang';

    // Debug: Log giá trị email
    error_log('Email value: ' . $email);

    // Kiểm tra nếu tất cả các trường đều trống
    if ($name === '' && $phone === '' && $email === '' && $address === '' && $password === '') {
        echo json_encode(['success' => false, 'message' => 'Không được để trống tất cả các trường!']);
        exit();
    }

    // 1. Tên
    if ($name === '' || preg_match('/^\\s+$/', $name)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên của bạn!']);
        exit();
    }
    if (mb_strlen($name, 'UTF-8') < 5) {
        echo json_encode(['success' => false, 'message' => 'Tên quá ngắn, phải từ 5 ký tự trở lên!']);
        exit();
    }
    if (mb_strlen($name, 'UTF-8') > 50) {
        echo json_encode(['success' => false, 'message' => 'Tên quá dài, tối đa 50 ký tự!']);
        exit();
    }

    // 2. Số điện thoại
    if ($phone === '') {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập số điện thoại!']);
        exit();
    }
    if (strlen($phone) !== 10) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại phải gồm đúng 10 chữ số!']);
        exit();
    }
    if (!preg_match('/^\d+$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại không được chứa ký tự đặc biệt!']);
        exit();
    }
    if (preg_match('/(\d)\1{5,}/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại không được chứa 6 số giống nhau liên tiếp trở lên!']);
        exit();
    }

    // 3. Email
    if ($email === '') {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập email!']);
        exit();
    }
    if (strpos($email, '@') === 0) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ, phải có tên người dùng trước @!']);
        exit();
    }
    if (strpos($email, '@') === false) {
        echo json_encode(['success' => false, 'message' => 'Email phải chứa ký tự "@"!']);
        exit();
    }
    if (substr_count($email, '@') > 1) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
        exit();
    }
    if (preg_match('/\s/', $email)) {
        echo json_encode(['success' => false, 'message' => 'Email không được chứa khoảng trắng!']);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
        exit();
    }

    // 4. Địa chỉ
    if ($address === '' || preg_match('/^\\s+$/', $address)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập địa chỉ!']);
        exit();
    }
    if (mb_strlen($address, 'UTF-8') < 5) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ quá ngắn, phải từ 5 ký tự trở lên!']);
        exit();
    }
    if (mb_strlen($address, 'UTF-8') > 70) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không được dài hơn 70 ký tự!']);
        exit();
    }
    if (preg_match('/[^a-zA-Z0-9\s,.\-À-ỹà-ỹ]/u', $address)) {
        echo json_encode(['success' => false, 'message' => 'Địa chỉ không được chứa ký tự đặc biệt!']);
        exit();
    }

    // 5. Mật khẩu
    if ($password === '') {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mật khẩu!']);
        exit();
    }
    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải dài ít nhất 8 ký tự.']);
        exit();
    }
    if ($password === '' && $confirmPassword === '') {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đủ cả hai mật khẩu!']);
        exit();
    }

    // Kiểm tra email đã tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email đã tồn tại!']);
        exit();
    }
    $stmt->close();

    // Insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, phone, email, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $address, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi đăng ký: ' . $conn->error]);
    }
    $stmt->close();
}

$conn->close();
?>