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


   //thong báo
$message = '';

// Kiểm tra nếu `user_id` chưa được thiết lập trong session
if (!isset($_SESSION['user_id'])) {
    // Thiết lập thông báo
    // $message = "Bạn chưa có tài khoản đăng nhập.";
    // echo "Bạn chưa có tài khoản đăng nhập.";
    echo '<h5> Vui lòng đăng nhập hoặc đăng ký! </h5>';
}

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];

    }


    // Giả sử 'id' lưu user_id

    // require_once('C:/xampp/htdocs/web_new_born/new_born/db.php');
    // Truy vấn dữ liệu từ bảng `sanpham`
    $sql = "SELECT * FROM sanpham";
    $result = $conn->query($sql);

    $featured_sql = "SELECT * FROM sanpham WHERE san_pham_noi_bat = 1";
    $featured_result = $conn->query($featured_sql);


?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nous</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/demo.css" />



    <style>
    a {
        text-decoration: none;
        color: rgb(88, 89, 91);
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

    /* from đnagw xuất */
  
/* CSS cho menu thả xuống */
.dropdown {
    position: relative;
    display: inline-block;
    
}

.user-name {
    cursor: pointer;
    padding: 8px;
    background-color: #f0f0f0;
    border-radius: 4px;
}

.dropdown-content {
    display: none; /* Ẩn menu thả xuống mặc định */
    position: absolute;
    background-color: #ffffff;
    min-width: 150px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    margin-top: 8px;
    padding: 8px;
    border-radius: 15px;
    top: 20px;
    
}

.dropdown-content a {
    color: black;
    text-decoration: none;
    display: block;
    padding: 8px 12px;
  
}

.dropdown-content a:hover {
    background-color: #ddd;
}

/* Hiển thị menu thả xuống khi di chuột vào */
.dropdown:hover .dropdown-content {
    display: block;
}

/* tim kiem */

#searchResults {
 
  justify-content: space-between;
  align-items: center;
    
    position: absolute;
    top: 40px; /* Khoảng cách từ thanh tìm kiếm */
    /* left: 0; */
  
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    transform: translateY(-20px);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
    z-index: 1000;
    display: none; 
    /* Ẩn mặc định */
}

/* Kết quả hiển thị */
#searchResults.active {
    display: block;
    transform: translateY(0);
    opacity: 1;
}

</style>
  
    <!-- Link CSS Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>

<body>
    <!-- Header -->
    <div class="container container_header">
        
        <div class="header">
            <div class="logo">
                <a href="trangchu.php"> <img src="../img/logo.webp" alt="Logo-Nous" /></a>
            </div>

            <div class="search-bar">
               <input type="text" id="searchInput" name="keyword" placeholder="Bạn cần tìm gì ..." required />
                <button type="button" id="searchButton" class="search-button">Tìm kiếm</button>

                <div id="searchResults"></div>
            </div>
        

<script>
   document.getElementById("searchButton").addEventListener("click", function () {
    const keyword = document.getElementById("searchInput").value.trim();
    const resultsContainer = document.getElementById("searchResults");

    if (keyword === "") {
        alert("Vui lòng nhập từ khóa tìm kiếm!");
        resultsContainer.classList.remove("active");
        return;
    }

    // Gửi yêu cầu tìm kiếm tới backend
    fetch(`/QL_web_new_born/timkiem.php?keyword=${encodeURIComponent(keyword)}`)
        .then(response => response.json())
        .then(data => {
            resultsContainer.innerHTML = ""; // Xóa kết quả cũ

            fetch(`/QL_web_new_born/timkiem.php?keyword=${encodeURIComponent(keyword)}`)
    .then(response => {
        if (!response.ok) throw new Error("HTTP status " + response.status);
        return response.json();
    })

    

            if (data.length === 0) {
                resultsContainer.innerHTML = "<p>Không tìm thấy sản phẩm phù hợp.</p>";
                resultsContainer.classList.add("active"); // Hiển thị khung trống
                return;
            }

            // Hiển thị danh sách sản phẩm tìm được
            const resultList = document.createElement("ul");
            resultList.style.listStyle = "none";
            data.forEach(item => {
                const listItem = document.createElement("li");
                listItem.innerHTML = `
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <img src="${item.anh_san_pham}" alt="${item.ten_san_pham}" style="width: 50px; height: 50px; margin-right: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        <div>
                            <a href="/QL_web_new_born/Frontend_web/ChiTietSanPham.php?id=${item.id}"><strong>${item.ten_san_pham}</strong></a>
                            <p>${item.gia.toLocaleString()} VNĐ</p>
                        </div>
                    </div>
                `;
                resultList.appendChild(listItem);
            });
            resultsContainer.appendChild(resultList);
            resultsContainer.classList.add("active"); // Hiển thị với hiệu ứng trượt
        })
        .catch(error => {
            console.error("Lỗi tìm kiếm:", error);
            alert("Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại!");
            resultsContainer.classList.remove("active");
        });
});

// Ẩn kết quả khi nhấp ra ngoài
document.addEventListener("click", function (event) {
    const resultsContainer = document.getElementById("searchResults");
    const searchInput = document.getElementById("searchInput");
    if (
        !resultsContainer.contains(event.target) &&
        !searchInput.contains(event.target)
    ) {
        resultsContainer.classList.remove("active");
    }
});


</script>
            <div class="account">
                <?php
             if (session_status() == PHP_SESSION_NONE) {
              session_start();
              }

              if (isset($_SESSION['name'])) {
 
              echo '<div class="dropdown">';
              echo '<span class="user-name">Xin chào, ' . htmlspecialchars($_SESSION['name']) . '</span>';
              echo '<div class="dropdown-content">';
              echo '<a href="../Backend_dkdn/dangxuat.php">Đăng xuất</a>'; 
              echo '</div>';
              echo '</div>';
            } else {
    
            //  echo '<a href="./formdangnhapky.php">Tài khoản</a>';
           }

                ?>
            
                <a href="#" id="cartBtn">Giỏ hàng</a>
  
            </div>
        </div>

        <div class="menu">
            <a href="#">GIỚI THIỆU NOUS</a>
            <!-- 
            <a href="#">BÉ MẶC</a>
            <a href="#">BÉ NGỦ</a>
            <a href="#">BÉ CHƠI</a>
            <a href="#">BÉ ĂN UỐNG</a>
            <a href="#">BÉ VỆ SINH</a>-->
        </div>
    </div>


    <!-- Giỏ Hàng Sidebar -->
   <!-- Giỏ Hàng Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-header">
        <h2>Giỏ Hàng</h2>
        <span class="close-btn" onclick="toggleCart()">×</span>
    </div>
    <div class="cart-content">
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id']; // Lấy user_id từ session

            // Kết nối cơ sở dữ liệu
            $servername = "localhost";
            $username = "root";
            $password = "newborn_shop1";
            $conn = new mysqli('127.0.0.1', 'root', '', 'newborn_shop1');

            // Kiểm tra kết nối
            if ($conn->connect_error) {
                die("Kết nối thất bại: " . $conn->connect_error);
            }

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
                        <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" alt="Product Image">
                        <div class="cart-item-details">
                            <div class="cart-item-title"><?= htmlspecialchars($row['ten_san_pham']); ?></div>
                            <div class="cart-item-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</div>
                            <div class="cart-item-quantity">
                                <form method="post" action="../Backend_giohang/update_cart.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                    <input type="text" value="<?= $row['so_luong']; ?>" readonly style="width: 30px; text-align: center;">
                                    <button type="submit" name="action" value="increase Quần yếm bé trai sơ sinh cao cấp" class="quantity-btn">+</button>
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
        <?php
        // Kiểm tra nếu người dùng đã đăng nhập và giỏ hàng có sản phẩm
        if (isset($_SESSION['user_id']) && isset($result) && $result->num_rows > 0) {
        ?>
            <form method="post" action="chitietgiohang.php" onsubmit="return validateCheckout()">
                <button type="submit" class="checkout-btn">Đặt hàng</button>
            </form>
        <?php
        } else {
            // Hiển thị nút "Đặt hàng" nhưng kèm thông báo khi nhấn
            ?>
            <button class="checkout-btn" onclick="showCheckoutAlert()">Đặt hàng</button>
            <?php
        }
        ?>
    </div>
</div>
    <div id="cartOverlay" class="cart-overlay" onclick="toggleCart()"></div>


    <!-- Slide chạy   -->
    <div class="slider-container">
        <div class="slider">
            <div class="slides">
                <img class="hoo" src="../img/slide5.webp" alt="Image 1" />
                <img class="hoo" src="../img/slide2.webp" alt="Image 2" />
                <img class="hoo" src="../img/slide3.webp" alt="Image 3" />
                <img class="hoo" src="../img/slide1.webp" alt="Image 5" />
            </div>
        </div>

        <!-- git  buttons -->
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>

        <!-- Dots -->
        <div class="dots" id="dots-container"></div>
    </div>

    <!-- con trẻ là tuyệt vời nhất -->
    <div class="container container_introduce">
        <div class="img-baby">
            <img src="../img/con_tre_tuyet_voi_nhat.webp" alt="img" />
        </div>

        <div class="content_introduce">
            <h3>Con trẻ tuyệt nhất <br />khi thoải mái là chính mình <br /></h3>
            <div class="p">
                Mỗi thiết kế của Nous đều tuân thủ triết lý "COMFYNISTA - Thoải mái
                chính là thời trang", trong đó sự thoải mái của các bé được ưu tiên
                trong mỗi chi tiết nhỏ nhưng vẫn chứa đựng sự tinh tế và khác biệt. Vì
                vậy, Nous luôn được hàng triệu bà mẹ Việt Nam tin chọn nâng niu hành
                trình lớn khôn của bé.
                <div class="home_about_icon left">“</div>
                <div class="home_about_icon right">“</div>
            </div>

            <div class="see_more">
                <a class="see_more-link" href="#">
                    <img src="../img/xem_them.webp" alt="xemthem" />
                    <div class="img-text">XEM THÊM</div>
                </a>
            </div>
        </div>
    </div>

    <!-- sản phẩm nổi bật , dùng container_outermost để đổ màu nền-->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2 style="font-size: 33px;">SẢN PHẨM NỔI BẬT</h2>
                <div class="products">
                    <?php
                if ($featured_result->num_rows > 0) {
                    while ($row = $featured_result->fetch_assoc()) {
                        ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="/QL_web_new_born/Frontend_web/<?php echo htmlspecialchars($row['anh_san_pham']); ?>"
                                alt="<?php echo htmlspecialchars($row['ten_san_pham']); ?>" />
                            <div class="new-tag">NEW</div>
                            <div class="cart-icon">
                                <button><img src="../img/cart2.svg" alt="cart" /></button>
                            </div>
                        </div>
                        <div class="product-info">
                            <p class="product-name"><?php echo htmlspecialchars($row['ten_san_pham']); ?></p>
                            <p class="product-price"><?php echo number_format($row['gia'], 0, ',', '.') . '₫'; ?></p>
                        </div>
                    </div>
                    <?php
                    }
                } else {
                    echo "<p>Không có sản phẩm nổi bật nào.</p>";
                }
                ?>
                </div>
            </section>
        </div>
    </div>







    <?php
        // Truy vấn lấy tất cả sản phẩm có loại "Bé mặc"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé mặc'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <!-- bé mặc -->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ MẶC</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="ChiTietSanPham.php?id=<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php
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


        // Truy vấn lấy tất cả sản phẩm có loại "Bé ngủ"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé ngủ'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <!-- bé ngủ -->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ NGỦ</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="ChiTietSanPham.php?id=<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php
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


        // Truy vấn lấy tất cả sản phẩm có loại "Bé chơi"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé chơi'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <!-- bé chơi -->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ CHƠI</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="ChiTietSanPham.php?id=<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php
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


        // Truy vấn lấy tất cả sản phẩm có loại "Bé uống"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé ăn uống'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <!-- bé uống -->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ ĂN UỐNG</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="ChiTietSanPham.php?id=<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php
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


        // Truy vấn lấy tất cả sản phẩm có loại "Bé vệ sinh"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé vệ sinh'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <!-- bé vệ sinh -->
    <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ VỆ SINH</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="ChiTietSanPham.php?id=<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
 <!-- bé ăn ngoài -->
 <div class="container_outermost">
        <div class="container container_products">
            <section class="featured-products">
                <h2>BÉ RA NGOÀI</h2>
                <div class="products">
                    <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="product-card">
                        <a href="<?= $row['id']; ?>">
                            <div class="product-image">
                                <img src="/QL_web_new_born/Frontend_web/<?= htmlspecialchars($row['anh_san_pham']); ?>" />
                                <div class="new-tag">NEW</div>
                                <div class="cart-icon">
                                    <form action="../Backend_giohang/giohangnho.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                        <button type="submit"><img src="../img/cart2.svg" alt="Add to cart" /></button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($row['ten_san_pham']); ?></p>
                                <p class="product-price"><?= number_format($row['gia'], 0, ',', '.'); ?> ₫</p>
                            </div>
                        </a>
                    </div>

                    <?php endwhile; ?>
                    <?php else : ?>
                    <p>Không có sản phẩm nào trong danh mục này.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <?php
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


        // Truy vấn lấy tất cả sản phẩm có loại "Bé ăn ngoài"
        $stmt = $conn->prepare("
            SELECT * FROM sanpham WHERE loai_san_pham = 'Bé ra ngoài'
        ");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
    ?>




    <?php 
        // Đặt lại con trỏ kết quả về đầu
        $result->data_seek(0); 
    ?>

    <!-- style="background-image: url('https://theme.hstatic.net/1000353507/1000449703/14/body_bg.png?v=3570');" -->
    <!-- pt 4 anhhhh -->
    <div>
        <div class="features-container">
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="../icon/iccon1.webp" alt="Giao hàng nhanh">
                </div>
                <h3>Giao hàng nhanh, miễn phí</h3>
                <p>Cho đơn hàng từ 399k trở lên hoặc đăng ký thành viên để hưởng nhiều ưu đãi</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="../icon/iccon2.webp" alt="Trả hàng, Bảo hành">
                </div>
                <h3>Trả hàng, bảo hành</h3>
                <p>Đổi trả/bảo hành lên đến 30 ngày</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="../icon/iccon3.webp" alt="Thành viên">
                </div>
                <h3>Thành viên</h3>
                <p>Đăng ký thành viên để nhận được nhiều ưu đãi độc quyền</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="../icon/iccon4.webp" alt="Chính hãng">
                </div>
                <h3>Chính hãng</h3>
                <p>Sản phẩm nguồn gốc xuất xứ rõ ràng - an toàn - thoải mái</p>
            </div>
        </div>

        <!-- phần edddd sản phẩn    -->
        <!-- end_SanPham -->
        <div class="container">
            <div class="row g-0 row-cols-4">
                <div class="col-lg-4 col-4 mb-4">
                    <div class="sub-mid">
                        <h4>Giới Thiệu</h4>
                        <hr>
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Chính đổi trả</a></li>
                        <li><a href="#"></a>Chính sách bảo mật</li>
                        <li><a href=""></a>Chính Sách vận chuyển </li>
                        <li><a href=""></a>Điều khoản dịch vụ</li>
                        <li><a href=""></a>Hướng dẫn mua hàng</li>
                        <li><a href=""></a>Hướng dẫn thanh toán</li>
                    </div>
                </div>
                <div class="col-lg-4 col-4 mb-4">
                    <div class="sub-mid">
                        <h4>Thông tin liên hệ</h4>
                        <hr>
                        <li><a href="https://www.nous.vn/?srsltid=AfmBOorhMIKVSjVcdQSTxICkAWN-qi3wCCP2oVB4ADIJjdQlg4raoUF5">Website: www.embeoi.com.vn</a></li>
                        <li><a href="">Email:</a></li>
                        <li><a href=""></a>Hotline: 123456789</li>
                        <li><a href="">  CÔNG TY CỔ PHẦN NU VIỆT NAM </a></li>
                        <li><a href="">  Mã số thuế: 0107126252 </a></li>
                        <li><a href="https://www.google.com/maps/place/79+M%E1%BA%A1c+Th%E1%BB%8B+B%C6%B0%E1%BB%9Fi,+B%E1%BA%BFn+Ngh%C3%A9,+Qu%E1%BA%ADn+1,+H%E1%BB%93+Ch%C3%AD+Minh/@10.7744879,106.7041682,1108m/data=!3m2!1e3!4b1!4m6!3m5!1s0x31752f46ed033b17:0x30e06a8cb3223dba!8m2!3d10.7744879!4d106.7041682!16s%2Fg%2F11q2n7xq41?entry=ttu&g_ep=EgoyMDI1MDQyMy4wIKXMDSoASAFQAw%3D%3D">Địa chỉ</a></li>

                    </div>
                </div>

                <div class="col-lg-4 col-4 mb-4">
                    <div class="sub-mid">
                        <h4>Fanpage</h4>
                        <hr>
                        <li><a href="http://localhost/QL_web_new_born/Frontend_web/ql_donhang.php">Đơn hàng</a></li>
                        <li><a href="http://localhost/QL_web_new_born/Frontend_web/trangchu.php">Trang chủ</a></li>
                        <li><a href="http://localhost/QL_web_new_born/Frontend_web/Thongke.php">Thống kê</a></li>
                         
                    </div>
                </div>

                
            </div>
        </div>
    </div>






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
    <script src="../js/demo.js"></script>

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
<script>
function validateCheckout() {
    // Nếu đã có sản phẩm và đã đăng nhập, cho phép submit form
    return true;
}

function showCheckoutAlert() {
    <?php if (!isset($_SESSION['user_id'])) { ?>
        alert("Vui lòng đăng nhập để đặt hàng!");
    <?php } elseif (isset($result) && $result->num_rows == 0) { ?>
        alert("Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi đặt hàng!");
    <?php } ?>
}
</script>
</body>

</html>
<?php
// Đóng kết nối
$stmt->close();
$conn->close();
?>