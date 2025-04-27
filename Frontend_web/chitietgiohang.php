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
    
    // $userId = $_SESSION['user_id'];


    
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/giohang.css">
    <!-- <link rel="stylesheet" href="./css/demo.css" /> -->
    <style>
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
                        echo '<a href="./fromdangnhapky.php">Tài khoản</a>';
                    }
                ?>
                <a href="#">Yêu thích</a>
                <a href="#" id="cartBtn">Giỏ hàng</a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="menu">
            <a href="http://localhost/web_new_born/new_born/Frontend_web/trangchu.php">GIỚI THIỆU NOUS</a>
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
            <form method="post" action="dat_hang.php">
                <button type="submit" class="checkout-btn">Đặt hàng</button>
            </form>
        </div>
    </div>


    <!-- Overlay -->
    <div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>


    <div class="container">
        <div class="cart">
            <div class="cart-left">
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
                    <!-- Hiển thị ảnh sản phẩm -->
                    <<img src="<?= htmlspecialchars($row['anh_san_pham']); ?>" alt="Product Image">
                    <div class="details">
                        <h3><?= htmlspecialchars($row['ten_san_pham']); ?></h3>
                        <p>SKU: <?= htmlspecialchars($row['san_pham_id']); ?></p>
                        <p>Số lượng: <?= htmlspecialchars($row['so_luong']); ?></p>
                    </div>

                    <div class="quantity">
                        <!-- Form cập nhật số lượng -->
                        <form action="../Backend_giohang/update_cart.php" method="post" style="display: inline;">
                            <!-- id của giỏ hàng -->
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <!-- Trường xác định trang chuyển hướng -->
                            <input type="hidden" name="redirect" value="chitietgiohang">


                            <button type="submit" name="action" value="decrease" class="decrease">-</button>
                            <input type="number" name="quantity" value="<?= htmlspecialchars($row['so_luong']); ?>"
                                min="1" style="width: 50px; text-align: center;">
                            <button type="submit" name="action" value="increase" class="increase">+</button>
                        </form>
                    </div>


                    <!-- Hiển thị giá tiền của từng sản phẩm -->
                    <div class="price"><?= number_format($row['gia'], 0, ',', '.'); ?>₫</div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>Giỏ hàng của bạn đang trống.</p>";
                }
            } else {
                echo "<p>Vui lòng đăng nhập để xem giỏ hàng.</p>";
            }
            ?>
            </div>

            <div class="cart-right">
                <div class="order-info">
                    <p class="abc">Phí vận chuyển sẽ được tính ở trang thanh toán. Bạn cũng có thể nhập mã giảm giá ở
                        trang thanh toán</p>
                    <div class="total">
                        Tạm tính (<?= isset($totalPrice) ? number_format($totalPrice, 0, ',', '.') : 0; ?> ₫)
                    </div>
                    <label><input type="checkbox"> Xuất Hóa Đơn</label>
                    <!-- Form đặt hàng -->
                    <form action="Thanhtoan.php" method="post">
                        <button type="submit" class="order-button">Xác nhận đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Ghi chú của khách hàng -->
        <h3 style="margin-top: 10px;" class="abc">Ghi chú</h3>
        <textarea placeholder="Vui lòng nhập ghi chú của bạn..."></textarea>
    </div>




    <script>
    document.querySelectorAll('.quantity input').forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
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
<?php
// Đóng kết nối
$stmt->close();
$conn->close();
?>

</html>