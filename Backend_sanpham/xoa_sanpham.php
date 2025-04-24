<?php
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

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Sanitize input

    // Lấy đường dẫn ảnh sản phẩm trước khi xóa
    $sql = "SELECT anh_san_pham FROM sanpham WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['anh_san_pham'];

        // Xóa sản phẩm khỏi database
        $sql = "DELETE FROM sanpham WHERE id = $productId";
        if ($conn->query($sql) === TRUE) {
            // Xóa file ảnh nếu tồn tại
            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    echo "<script>
                            alert('Xóa sản phẩm thành công nhưng lỗi khi xóa file ảnh');
                            window.location.href = 'http://localhost/QL_web_new_born/Frontend_web/admin2.php';
                          </script>";
                }
            }
            echo "<script>
                    alert('Xóa sản phẩm thành công');
                    window.location.href = 'http://localhost/QL_web_new_born/Frontend_web/admin2.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Lỗi khi xóa sản phẩm: " . addslashes($conn->error) . "');
                    window.location.href = 'http://localhost/QL_web_new_born/Frontend_web/admin2.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Sản phẩm không tồn tại');
                window.location.href = 'http://localhost/QL_web_new_born/Frontend_web/admin2.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID sản phẩm không được cung cấp');
            window.location.href = 'http://localhost/QL_web_new_born/Frontend_web/admin2.php';
          </script>";
}

$conn->close();
