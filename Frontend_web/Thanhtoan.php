<?php
session_start(); // Start the session to access $_SESSION['user_id']

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


if (empty($_SESSION['user_id'])) {
    echo "Vui lòng đăng nhập để tiếp tục thanh toán.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your custom CSS styles here */
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

header h1 {
    font-size: 2em;
    color: #d3bca7;
    text-align: center;
}

header nav {
    text-align: center;
    margin-bottom: 20px;
    font-size: 14px;
    color: #888;
}

header nav a {
    color: #d3bca7;
    text-decoration: none;
}

.checkout {
    display: flex;
    gap: 20px;
}

.shipping-form,
.order-summary {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.shipping-form {
    flex: 3;
}

.shipping-form h2 {
    font-size: 1.2em;
    margin-bottom: 15px;
    color: #333;
}

.shipping-form label {
    display: block;
    margin-bottom: 10px;
    color: #555;
}

.shipping-form input[type="text"],
.shipping-form input[type="email"],
.shipping-form input[type="tel"],
.shipping-form select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.shipping-form fieldset {
    border: 1px solid #ccc;
    padding: 15px;
    margin-top: 15px;
    border-radius: 4px;
}

.shipping-form legend {
    font-size: 1em;
    color: #555;
}

.shipping-form button {
    width: 100%;
    padding: 10px;
    background-color: #d3bca7;
    border: none;
    color: #fff;
    font-size: 16px;
    border-radius: 4px;
    margin-top: 20px;
}

.order-summary {
    flex: 1;
}

.order-summary h3 {
    font-size: 1.2em;
    color: #333;
    margin-bottom: 15px;
}

.cart-items li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 14px;
    color: #555;
}

.total h4,
.total h3 {
    margin-top: 15px;
    font-size: 16px;
    color: #333;
}

.total p {
    font-size: 16px;
    color: #d3bca7;
    text-align: right;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #d3bca7;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
}
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>nous</h1>
            <nav>
                <a href="#">Giỏ hàng</a> &gt;
                <a href="#">Thông tin giao hàng</a> &gt;
                <a href="#">Phương thức thanh toán</a>
            </nav>
        </header>

        <section class="checkout">
            <!-- Shipping Information Form -->
            <form class="shipping-form" id="shippingForm11" action="../Backend_thanhtoan/logic_thanhtoan.php" method="POST">
                <h2>Thông tin giao hàng</h2>
                <label for="hoTen">Họ Tên:</label>
                <input type="text" name="hoTen" required>

                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="soDienThoai">Số Điện Thoại:</label>
                <input type="text" name="soDienThoai" required>

                <label for="diaChi">Địa Chỉ:</label>
                <input type="text" name="diaChi" required value="<?= $_SESSION['diaChi'] ?? '' ?>">

                <fieldset>
                    <legend>Phương thức giao hàng</legend>
            <label>
                <input type="radio" name="payment_method" value="delivery"  value="delivery" checked> Thanh khi nhận hàng
            </label>

            <label>
                <input type="radio" name="payment_method" value="online_payment"  value="online_payment" required> Thanh toán online
            </label>
    

              
                    <select name="city">
                        <option value="">Tỉnh / thành</option>
                        <option value="hanoi">Hà Nội</option>
                        <option value="hochiminh">TP.HCM</option>
                        <option value="danang">Đà Nẵng</option>
                    </select>

                    <select name="district">
                        <option value="">Quận / huyện</option>
                        <option value="hoankiem">Hoàn Kiếm</option>
                        <option value="tanbinh">Tân Bình</option>
                        <option value="sontra">Sơn Trà</option>
                    </select>

                    <select name="ward">
                        <option value="">Phường / xã</option>
                        <option value="hangbong">Hàng Bông</option>
                        <option value="phuocmy">Phước Mỹ</option>
                    </select>

                   
                </fieldset>

                <button type="submit" id="confirmPayment" >Xác nhận thanh toán</button>
            </form>

            <script>
   document.getElementById("shippingForm11").addEventListener("submit", function (e) {
    e.preventDefault(); // Ngăn form gửi mặc định

    // Lấy phương thức thanh toán
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

    if (paymentMethod === "online_payment") {
        // Gửi dữ liệu đến file PHP để xử lý thanh toán VNPay
        this.action = "vnpay_payment.php";
        this.submit();
    } else {
        // Gửi form bình thường cho phương thức 'Giao tận nơi'
        this.action = "../Backend_thanhtoan/logic_thanhtoan.php";
        this.submit();
    }
});

</script>

            <!-- Order Summary Section -->
            <aside class="order-summary">
                <h3>Giỏ hàng</h3>
                <ul class="cart-items">
                    <?php
                    if (!empty($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];

                        $stmt = $conn->prepare("
                            SELECT g.san_pham_id, g.so_luong, s.ten_san_pham, s.gia, s.anh_san_pham
                            FROM gio_hang g
                            INNER JOIN sanpham s ON g.san_pham_id = s.id
                            WHERE g.user_id = ?
                        ");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $total = 0;
                        if ($result->num_rows > 0) {
                            while ($item = $result->fetch_assoc()) {
                                $itemTotal = $item['gia'] * $item['so_luong'];
                                $total += $itemTotal;
                    ?>
                                <li class="cart-item">
                                    <!--<img src="/web_new_born/new_born/Frontend_web/<?= htmlspecialchars($item['anh_san_pham']); ?>" alt="Product Image" style="width: 60px; height: 60px;">-->
                                    <div class="item-details" style="text-align:start;">
                                        <p class="item-name"><?= htmlspecialchars($item['ten_san_pham']); ?></p>
                                        <p class="item-quantity"><?= $item['so_luong']; ?> x <?= number_format($item['gia'], 0, ',', '.'); ?> ₫</p>
                                    </div>
                                    <span class="item-total"><?= number_format($itemTotal, 0, ',', '.'); ?> ₫</span>
                                </li>
                    <?php
                            }
                        } else {
                            echo "<p>Giỏ hàng của bạn hiện tại trống.</p>";
                        }
                    }
                    ?>
                </ul>

                <div class="total">
                    <h4>Tổng cộng:</h4>
                    <h3><?= number_format($total, 0, ',', '.'); ?> ₫</h3>
                </div>
            </aside>
        </section>
    </div>
</body>

</html>
