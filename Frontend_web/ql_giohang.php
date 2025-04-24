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


// Truy vấn dữ liệu từ bảng `gio_hang`, `users`, và `sanpham`
$sql = "
    SELECT 
        gh.id AS gio_hang_id,
        u.id AS user_id,
        u.name AS ten_nguoi_dung,
        u.email AS email_nguoi_dung,
        s.id AS san_pham_id,
        s.ten_san_pham,
        gh.so_luong,
        s.gia,
        s.anh_san_pham
    FROM gio_hang gh
    INNER JOIN users u ON gh.user_id = u.id
    INNER JOIN sanpham s ON gh.san_pham_id = s.id
    ORDER BY u.name, gh.id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng và giỏ hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            background-color: #f9f9f9;
        }

        .content {
            margin: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #a39074;
            color: white;
        }

        .btn-primary, .btn-danger {
            border-radius: 30px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #8d7a5e;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .product-image {
            width: 50px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="content">
        <h2>Quản lý giỏ hàng của người dùng</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá (VNĐ)</th>
                        <th>Tổng giá (VNĐ)</th>
                        <th>Ảnh</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $stt = 1;
                        while ($row = $result->fetch_assoc()) {
                            $tongGia = $row['gia'] * $row['so_luong'];
                            echo "<tr>";
                            echo "<td>" . $stt++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['ten_nguoi_dung']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email_nguoi_dung']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ten_san_pham']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['so_luong']) . "</td>";
                            echo "<td>" . number_format($row['gia'], 0, ',', '.') . "</td>";
                            echo "<td>" . number_format($tongGia, 0, ',', '.') . "</td>";
                            echo "<td>";
                            if (!empty($row['anh_san_pham'])) {
                                echo "<img src='/QL_web_new_born/Frontend_web/" . htmlspecialchars($row['anh_san_pham']) . "' class='product-image'>";
                            } else {
                                echo "Không có ảnh";
                            }
                            echo "</td>";
                            echo "<td><a href='xoa_giohang.php?id=" . $row['gio_hang_id'] . "' class='btn btn-primary' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?\")'>Xóa</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
