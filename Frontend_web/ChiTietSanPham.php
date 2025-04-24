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

   
    $message = '';
    // Kiểm tra nếu `user_id` chưa được thiết lập trong session
    if (!isset($_SESSION['user_id'])) {
        // Thiết lập thông báo
        // $message = "Bạn chưa có tài khoản đăng nhập.";
        // echo "Bạn chưa có tài khoản đăng nhập."
        
        echo '<h3> Bạn chưa có tài khoản đăng nhập !!!!.</h3>';
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];

    }


    // Giả sử 'id' lưu user_id

    // require_once('C:/xampp/htdocs/web_new_born/new_born/db.php');
    // Truy vấn dữ liệu từ bảng `sanpham`
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/ChiTietSanPham1.css">
    <!-- <link rel="stylesheet" href="./css/demo.css"> -->

    <!-- Link CSS Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
    /* General styling */
    .product-details {
        font-family: Arial, sans-serif;
        margin: 20px;
        width: 100%;
        height: auto;
    }

    /* Tabs styling */
    .tabs {
        display: flex;
        border-bottom: 2px solid #ddd;
        margin-bottom: 20px;
    }

    .tab {
        padding: 15px 25px;
        cursor: pointer;
        background: #f9f9f9;
        border: none;
        font-weight: bold;
        font-size: 18px;
        color: #333;
    }

    .tab.active {
        border-bottom: 4px solid #333;
        color: #333;
    }

    .tab-content {
        padding: 30px;
        background: #f5f5f5;
        text-align: center;
        color: #888;
        font-size: 18px;
    }

    /* Rating section styling */
    .rating-section {
        margin-top: 40px;
    }

    .rating-section h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .rating-overview {
        display: flex;
        align-items: center;
        gap: 50px;
        margin-bottom: 30px;
    }

    .average-rating {
        text-align: center;
        color: #333;
    }

    .average-rating .star {
        color: #ffd700;
        font-size: 64px;
    }

    .rating-score {
        font-size: 48px;
        font-weight: bold;
    }

    .rating-breakdown {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .rating-row {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .rating-row .star {
        color: #ffd700;
        font-size: 18px;
    }

    .rating-row span {
        font-size: 18px;
    }

    .progress-bar {
        width: 200px;
        height: 12px;
        background-color: #e0e0e0;
        position: relative;
        border-radius: 6px;
        /* Tạo góc bo tròn */
    }

    .progress-bar::after {
        content: "";
        position: absolute;
        height: 12px;
        background-color: #ffd700;
        width: 0%;
        border-radius: 6px;
        /* Tạo góc bo tròn cho phần vàng */
    }

    /* Rate product section */
    .rate-product {
        text-align: center;
    }

    .rate-product p {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .stars {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .stars span {
        font-size: 36px;
        cursor: pointer;
        color: #ccc;
        transition: color 0.3s;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 8px;
        /* Bo tròn các ngôi sao trong phần đánh giá */
    }

    .stars span.selected,
    .stars span:hover,
    .stars span:hover~span {
        color: #ffd700;
        background-color: #fff6e0;
        border-color: #ffd700;
    }

    .login-prompt {
        color: gray;
        font-size: 14px;
        margin-top: 10px;
    }

    .review-link {
        color: #007bff;
        text-decoration: none;
        font-size: 16px;
    }

    .title-v {
        font-size: 12px;
    }


    .cart-sidebar {
        position: fixed;
        top: 0;
        right: -400px;
        /* Start hidden on the right */
        width: 400px;
        height: 100%;
        background-color: #f9f9f9;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
        transition: right 0.3s ease;
        z-index: 1000;
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Overlay for Cart */
    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 999;
    }

    /* Display cart when active */
    .cart-sidebar.active {
        right: 0;
    }

    .cart-overlay.active {
        visibility: visible;
        opacity: 1;
    }

    /* Cart Header */
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
    }

    .cart-header h2 {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .close-btn {
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    /* Cart Content */
    .cart-content {
        padding: 1.5rem;
        overflow-y: auto;
        /* Allows scrolling if content is too tall */
        flex-grow: 1;
    }

    .cart-content p {
        font-size: 16px;
        color: #555;
    }

    /* Checkout Button */
    .checkout-btn {
        background-color: #DB9087;
        /* Match this to the button color in the image */
        border: none;
        color: white;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 5px;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .checkout-btn:hover {
        /* background-color: #d87c72; */
        background-color: #b97a6b;
    }


    /* Product item styling */
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #ddd;
    }

    .cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 10px;
    }

    .cart-item-details {
        flex: 1;
        padding-right: 10px;
    }

    .cart-item-title {
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
    }

    .cart-item-price {
        font-size: 14px;
        color: #555;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #555;
    }

    .cart-item-quantity button {
        background-color: #f1f1f1;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        font-weight: bold;
    }

    .cart-item-quantity input {
        width: 40px;
        text-align: center;
        border: 1px solid #ddd;
        margin: 0 5px;
    }

    /* thêm */


    .thanhtoan {
        padding: 20px;
        background-color: white;
        border-top: 1px solid #ddd;
        text-align: center;
        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #777;
    }

    .thanhtoan .cart-total {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .thanhtoan .cart-total p {
        margin: 0;
        color: #999;
    }

    .thanhtoan .cart-total a {
        color: #999;
        text-decoration: none;
        font-size: 14px;
    }

    .thanhtoan .cart-total a:hover {
        color: #777;
    }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="container container_header">
        <div class="header">
            <div class="logo">
                <a href="trangchu.php"> <img src="../img/logo.webp" alt="Logo-Nous" /></a>
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Bạn cần tìm gì ..." />
                <button class="search-button">Tìm kiếm</button>
            </div>

            <div class="account">
                <?php
                    //   session_start(); // Khởi động phiên nếu chưa được khởi động
                    if (isset($_SESSION['name'])) {
                        
                        // Người dùng đã đăng nhập, hiển thị tên người dùng
                        echo '<span>Xin chào, ' . htmlspecialchars($_SESSION['name']) . '</span>';
                        //   echo '<a href="./thongtintaikhoan.php">Thông tin tài khoản</a>';
                        //   echo '<a href="http://localhost/web_new_born/new_born/dangnhapky.php">Đăng xuất</a>'; // Liên kết để đăng xuất
                    } else {
                        // Người dùng chưa đăng nhập, hiển thị liên kết đăng nhập
                        echo '<a href="./formdangnhapky.php">Tài khoản</a>';
                    }
                ?>
                <a href="#">Yêu thích</a>
                <a href="#" id="cartBtn">Giỏ hàng</a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="menu">
            <a href="#">GIỚI THIỆU NOUS</a>
            <a href="#">BÉ MẶC</a>
            <a href="#">BÉ NGỦ</a>
            <a href="#">BÉ CHƠI</a>
            <a href="#">BÉ ĂN UỐNG</a>
            <a href="#">BÉ VỆ SINH</a>
            <a href="#">BÉ RA NGOÀI</a>
        </div>
    </div>


    <!-- Giỏ Hàng Sidebar -->
    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2>Giỏ Hàng</h2>
            <span class="close-btn" onclick="toggleCart()">&times;</span>
        </div>
        <div class="cart-content">
            <?php
            // Kiểm tra xem người dùng đã đăng nhập chưa
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id']; // Lấy user_id từ session

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

                // Truy vấn lấy thông tin giỏ hàng của người dùng từ bảng gio_hang và thông tin sản phẩm từ bảng sanpham
                $stmt = $conn->prepare("
                    SELECT g.id, g.san_pham_id, g.so_luong, s.ten_san_pham, s.gia, s.anh_san_pham
                    FROM gio_hang g
                    INNER JOIN sanpham s ON g.san_pham_id = s.id
                    WHERE g.user_id = ?
                ");
                if ($stmt === false) {
                    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
                }

                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Kiểm tra nếu giỏ hàng có sản phẩm
                if ($result->num_rows > 0) {
                    $totalPrice = 0;
                    while ($row = $result->fetch_assoc()) {
                        $productTotal = $row['gia'] * $row['so_luong'];
                        $totalPrice += $productTotal;
                        ?>
            <div class="cart-item">
                <img src="/web_new_born/new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" alt="Product Image">
                <div class="cart-item-details">
                    <div class="cart-item-title"><?= htmlspecialchars($row['ten_san_pham']); ?></div>
                    <div class="cart-item-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</div>
                    <div class="cart-item-quantity">

                        <!-- <form method="post" action="./xuly_logic/update_cart.php" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?= $row['san_pham_id']; ?>">
                            <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                            <input type="text" value="<?= $row['so_luong']; ?>" readonly
                                style="width: 30px; text-align: center;">
                            <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        </form> -->

                        <form method="post" action="../Backend_giohang/update_cart.php" style="display: inline;">
                            <!-- Thêm input cho id giỏ hàng -->
                            <input type="hidden" name="id" value="<?= $row['id']; ?>"> <!-- id của giỏ hàng -->

                            <!-- Các nút tăng/giảm số lượng -->
                            <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                            <input type="text" value="<?= $row['so_luong']; ?>" readonly
                                style="width: 30px; text-align: center;">
                            <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        </form>

                    </div>
                </div>
                <form method="post" action="../Backend_giohang/xoa_gio_hang.php" style="display: inline;">
                    <input type="hidden" name="product_id" value="<?= $row['san_pham_id']; ?>">
                    <button type="submit" class="delete-btn">XÓA</button>
                </form>
            </div>
            <?php
                    }
                    ?>
            <div class="cart-total-price">
                <p><strong>Tổng cộng:</strong> <?= number_format($totalPrice, 0, ',', '.'); ?> ₫</p>
            </div>
            <?php
                } else {
                    echo "<p>Giỏ hàng của bạn đang trống.</p>";
                }
            } else {
                echo "<p>Vui lòng đăng nhập để xem giỏ hàng.</p>";
            }
            ?>
        </div>

        <div class="thanhtoan">
            <!-- <form method="post" action="dat_hang.php"> -->
            <form method="post" action="chitietgiohang.php">
                <button type="submit" class="checkout-btn">Đặt hàng</button>
            </form>
        </div>
    </div>


    <!-- Overlay -->
    <div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>

    <?php
        // Kiểm tra nếu id được truyền qua URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Truy vấn sản phẩm từ database
            $stmt = $conn->prepare("SELECT * FROM sanpham WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Kiểm tra nếu tìm thấy sản phẩm
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
            } else {
                echo "Sản phẩm không tồn tại.";
                exit;
            }
        } else {
            echo "Không tìm thấy sản phẩm.";
            exit;
        }
    ?>
    <hr>
    <div class="main">
        <div class="container container_main" style="background-color:#F5F5F5; border-radius:10px; ">
            <div class="content">
                <div class="title">
                   
                    <div class="breadcrumb" style="padding-left:20px; padding-top:20px; margin: 0; font-weight: 500;" >
                            <a href="./trangchu.php">Trang chủ </a> / <a href="#">Baby</a> /
                            <?= htmlspecialchars($product['ten_san_pham']); ?>
                        </div>
                </div>
                <div class="container">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($product['anh_san_pham']); ?>"
                            alt="<?= htmlspecialchars($product['ten_san_pham']); ?>">
                    </div>

                    <div class="product-details">
                      

                        <h1><?= htmlspecialchars($product['ten_san_pham']); ?></h1>
                        <p class="status">Tình trạng: còn hàng</p>
                        <p class="price"><?= number_format($product['gia'], 0, ',', '.'); ?> ₫</p>

                        <!-- Phần lựa chọn màu sắc -->
                        <!-- <div class="form-options color-options">
                            <label>Màu sắc:</label>
                            <img src="<?= htmlspecialchars($product['anh_san_pham']); ?>" alt="Màu sắc sản phẩm">
                        </div> -->
                        <div class="form-options color-options">
                         <label>Màu sắc:</label>
                          <div class="color-images">
                          <img src="https://tronxinh.com/wp-content/uploads/2021/07/mau-be-la-mau-gi-2.jpg" alt="Màu sắc sản phẩm 1">
                          <img src="data:image/webp;base64,UklGRk4BAABXRUJQVlA4IEIBAABwEwCdASrhAOEAPoE8m0ulIqIhoLQI8KAQCWlu4Sqi4rdbl0AMo9ZtleMsgwYi4gFh/nrAhnRSv85hKttmnA9Y5HvK45kj3vJ0kYRHuuBRW0KMwR4Ees2yRdImJu0ltqwLkVqfYK6w2AtQ/Pqvdb0EJuYefbFXhx2OeAEvncvpAe1+f6flt1EQtkiEmaXKwooANCn4TafdCsUCKIuqZRW5XyiVoAD+mn5nD8RfpeesDA6pT4O75Zuo/QEeYnpOl4Zh/RcPYFxS27iaTfNqZTnG5oOvMGYZ4lTCzbLizGnmx0uDol679Ytcc2oCarA+/YguX1SI3iV4lAKznz5LV+AQJafAUe7QFicqtDNPSOCOgHRmkWHvAuirTTwce/2kJoFFgTYCiDU8tP2GNreDVs4RRInmx1yugiR50VEzY1IQAAAA" alt="Màu sắc sản phẩm 2">
                         </div>
                      </div>


                        <!-- Phần lựa chọn kích thước -->
                        <div class="form-options size-options">
                            <label>Kích thước:</label>
                            <div class="sub-options">
                                <button>9M</button>
                                <button>12M</button>
                                <button>18M</button>
                                <button>2Y</button>
                            </div>
                        </div>

                        <!-- Phần tăng giảm số lượng -->
                        <!-- <div class="quantity">
                            <label for="quantity">Số lượng: </label>
                            <button onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" value="1" min="1">
                            <button onclick="increaseQuantity()">+</button>
                        </div> -->
                        <!-- file thgeem vao gio hàng -->
                        <form action="../Backend_giohang/giohangnho.php" method="post">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
                            <button class="add-to-cart">Thêm vào giỏ hàng</button>
                        </form>


                        <!-- Dropdown danh sách cửa hàng -->
                        <div class="store-locator">
                            <h3>Tìm tại cửa hàng:</h3>
                            <select>
                                <option>Tất cả</option>
                                <option>NOUS Hồ Chí Minh - 79 Mạc Thị Bưởi, Quận 1</option>
                                <option>NOUS Hà Nội - 34 Quang Trung, Hoàn Kiếm</option>
                                <option>NOUS Hồ Chí Minh - 422B Nguyễn Thị Minh Khai, Quận 3</option>
                                <option>NOUS Hà Nội - 170 Cầu Giấy, Cầu Giấy</option>
                            </select>
                            <div class="store">
                                <span>Còn hàng</span>
                                NOUS Hồ Chí Minh - 79 Mạc Thị Bưởi, Quận 1
                                <a href="#">Xem bản đồ</a>
                            </div>
                            <div class="store">
                                <span>Còn hàng</span>
                                NOUS Hà Nội - 34 Quang Trung, Hoàn Kiếm
                                <a href="#">Xem bản đồ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container container_main">
            <div class="product-details">
                <!-- Tabs for "Mô tả" and "Thông số kỹ thuật" -->
                <div class="tabs">
                    <button class="tab active">Mô tả</button>
                    <button class="tab">Thông số kỹ thuật</button>
                </div>
                <div class="tab-content">
                    <p>Đang cập nhật nội dung</p>
                    <?= htmlspecialchars($product['mo_ta']); ?>
                </div>

                <!-- Rating Section -->
                <div class="rating-section">
                    <h2 style="text-align: center">Đánh giá sản phẩm</h2>
                    <div style="display: flex; justify-content: space-between">
                        <!-- style cho nó lằm trên cùng 1 dòng-->
                        <div class="rating-overview">
                            <div class="average-rating">
                                <span class="star">★</span>
                                <span class="rating-score">0.0</span>
                                <p>0 đánh giá</p>
                            </div>
                            <div class="rating-breakdown">
                                <div class="rating-row">
                                    <span>★★★★★</span>
                                    <div class="progress-bar"></div>
                                    <span>0</span>
                                </div>
                                <div class="rating-row">
                                    <span>★★★★☆</span>
                                    <div class="progress-bar"></div>
                                    <span>0</span>
                                </div>
                                <div class="rating-row">
                                    <span>★★★☆☆</span>
                                    <div class="progress-bar"></div>
                                    <span>0</span>
                                </div>
                                <div class="rating-row">
                                    <span>★★☆☆☆</span>
                                    <div class="progress-bar"></div>
                                    <span>0</span>
                                </div>
                                <div class="rating-row">
                                    <span>★☆☆☆☆</span>
                                    <div class="progress-bar"></div>
                                    <span>0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Rate Product Section -->
                        <div class="rate-product">
                            <p style="text-align: start; margin-top: 0">Đánh giá sản phẩm</p>
                            <div class="stars">
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                                <span>★</span>
                            </div>
                            <p class="login-prompt title-v" style="font-size: 12px">
                                Bạn cần đăng nhập để nhận xét và đánh giá sản phẩm
                            </p>
                            <a href="#" class="review-link title-v">Hãy là người đầu tiên đánh giá sản phẩm!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function increaseQuantity() {
        let quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decreaseQuantity() {
        let quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    }


    // danh gia sao 
    const stars = document.querySelectorAll(".rate-product .stars span");
    let rating = 0;

    stars.forEach((star, index) => {
        star.addEventListener("mouseover", () => {
            stars.forEach((s, i) => {
                s.style.color = i <= index ? "#FFD700" : "#ccc";
            });
        });

        star.addEventListener("click", () => {
            rating = index + 1;
            stars.forEach((s, i) => {
                s.style.color = i < rating ? "#FFD700" : "#ccc";
            });
        });

        star.addEventListener("mouseout", () => {
            stars.forEach((s, i) => {
                s.style.color = i < rating ? "#FFD700" : "#ccc";
            });
        });
    });
    </script>

    <script>
    function toggleCart() {
        const cartSidebar = document.getElementById("cartSidebar");
        const cartOverlay = document.getElementById("cartOverlay");

        cartSidebar.classList.toggle("active");
        cartOverlay.classList.toggle("active");
    }

    // Add event listener to cart button
    document.getElementById("cartBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        toggleCart();
    });
    </script>

    <!-- của slide -->
    <script src="./js/demo.js"></script>

    <!-- giỏ hàng nhỏ -->
    <script>
    document.querySelector('.cart-icon').addEventListener('click', function() {
        document.querySelector('.cart-container').classList.toggle('active');
    });
    </script>


    <!-- Script Swiper -->
    <!-- của slide -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 4,
        /* Hiển thị 4 ảnh một lúc */
        spaceBetween: 20,
        /* Khoảng cách giữa các ảnh */
        loop: true,
        /* Lặp lại slide */
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    </script>



</body>

</html>
<?php
    // Đóng kết nối
    $stmt->close();
    $conn->close();
?>