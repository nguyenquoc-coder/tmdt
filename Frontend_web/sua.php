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


// Lấy thông tin sản phẩm
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($product_id > 0) {
    $sql = "SELECT * FROM sanpham WHERE id = $product_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Không tìm thấy sản phẩm.");
    }
} else {
    die("ID sản phẩm không hợp lệ.");
}

// Xử lý cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_san_pham = mysqli_real_escape_string($conn, $_POST['ten_san_pham']);
    $gia = floatval($_POST['gia']);
    $loai_san_pham = mysqli_real_escape_string($conn, $_POST['loai_san_pham']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $so_luong = intval($_POST['so_luong']);
    $san_pham_noi_bat = isset($_POST['san_pham_noi_bat']) ? 1 : 0;

    $anh_san_pham = $product['anh_san_pham']; // Giữ ảnh cũ nếu không cập nhật
    if (isset($_FILES["anh_san_pham"]) && $_FILES["anh_san_pham"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["anh_san_pham"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["anh_san_pham"]["tmp_name"]);
        if ($check === false) {
            die("File không phải là ảnh.");
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
            die("Chỉ chấp nhận các định dạng JPG, JPEG, PNG, GIF, WEBP.");
        }

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["anh_san_pham"]["tmp_name"], $target_file)) {
            $anh_san_pham = $target_file; // Cập nhật đường dẫn ảnh mới
        } else {
            die("Lỗi khi tải ảnh lên.");
        }
    }

    $sql = "UPDATE sanpham SET 
                ten_san_pham = '$ten_san_pham', 
                gia = $gia, 
                loai_san_pham = '$loai_san_pham', 
                mo_ta = '$mo_ta', 
                so_luong = $so_luong, 
                anh_san_pham = '$anh_san_pham', 
                san_pham_noi_bat = $san_pham_noi_bat 
            WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật sản phẩm thành công!";
        header("Location: admin2.php"); // Chuyển hướng về trang quản lý
        exit;
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <style>
        /* CSS tương tự phần thêm sản phẩm */
        /* Your styles remain the same */
body {
font-family: 'Helvetica', sans-serif;
background-color: #f9f9f9;
color: #333;
}

.container {
width: 50%;
margin: 50px auto;
background-color: #fff;
padding: 30px;
border-radius: 15px;
box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

h2 {
font-size: 24px;
font-weight: 600;
text-align: center;
margin-bottom: 20px;
color: #a39074;
}


.form-group {
margin-bottom: 20px;
}

.form-group label {
font-size: 16px;
color: #6d6d6d;
margin-bottom: 10px;
display: block;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
width: 100%;
padding: 12px;
border: 1px solid #ddd;
border-radius: 10px;
font-size: 16px;
background-color: #f9f9f9;
transition: border-color 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
.form-group textarea:focus {
border-color: #a39074;
outline: none;
}

.form-group button {
background-color: #a39074;
color: white;
padding: 12px 20px;
border: none;
border-radius: 10px;
font-size: 16px;
cursor: pointer;
transition: background-color 0.3s ease;
}

.form-group button:hover {
background-color: #8d7a5e;
}

.form-group img {

max-width: 100px;
margin-bottom: 30px;
}

#product_image{
  font-size: inherit;
    margin: 0;
    line-height: inherit;
    font-family: inherit;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Sửa Sản Phẩm</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Tên Sản Phẩm</label>
                <input type="text" id="product_name" name="ten_san_pham" value="<?= htmlspecialchars($product['ten_san_pham']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Giá (VNĐ)</label>
                <input type="number" id="price" name="gia" value="<?= $product['gia'] ?>" required>
            </div>
            <div class="form-group">
                <label for="loai_san_pham">Loại sản phẩm</label>
                <input type="text" class="form-control" id="loai_san_pham" name="loai_san_pham"
                    value="<?php echo htmlspecialchars($product['loai_san_pham']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Mô Tả</label>
                <textarea id="description" name="mo_ta" rows="4" required><?= htmlspecialchars($product['mo_ta']) ?></textarea>
            </div>
           
            <div class="form-group">
                <label for="quantity">Số Lượng</label>
                <input type="number" id="quantity" name="so_luong" value="<?= $product['so_luong'] ?>" required>
            </div>
        
            <div class="form-group">
                <label for="featured">Sản Phẩm Nổi Bật</label>
                <input type="checkbox" id="featured" name="san_pham_noi_bat" value="1" <?= $product['san_pham_noi_bat'] ? "checked" : "" ?>>
            </div>
            <div class="form-group">
                <label for="product_image">Ảnh Sản Phẩm</label>
             <div style="display: flex; ">
             <img src="<?= $product['anh_san_pham'] ?>" alt="Ảnh sản phẩm" width="150">
             <input type="file" style="padding: 30px ;"   id="product_image" name="anh_san_pham" accept="image/*">
             </div>
            </div>
            <div class="form-group">
                <button type="submit">Cập Nhật</button>
                <button type="button"><a href="./admin2.php">HỦY</a></button>
            </div>
        </form>
    </div>
</body>
</html>
