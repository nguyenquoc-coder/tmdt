<!-- file ê nhan viennnn -->

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


// Fetch all "nhân viên" users
$sql = "SELECT * FROM users WHERE role = 'nhanvien'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Add head content from your HTML code above -->
     <style>
         body {
            font-family: 'Helvetica', sans-serif;
            background-color: #f9f9f9;
            color: #6d6d6d;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f1f1f1;
            height: 100%;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            padding: 15px;
            display: block;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #d1d1d1;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            position: fixed;
            width: calc(100% - 250px);
            left: 250px;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border-radius: 20px;
            border: 1px solid #ddd;
            width: 250px;
            outline: none;
        }

        /* User avatar and dropdown */
        .avatar {
            position: relative;
            display: inline-block;
        }

        .avatar img {
            width: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
            min-width: 160px;
            border-radius: 5px;
        }

        .dropdown-menu a {
            color: black;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            font-size: 14px;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        .avatar:hover .dropdown-menu {
            display: block;
        }

        /* Table */
        .table-container {
            margin-top: 80px;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
        }

        th {
            background-color: #a39074;
            color: white;
            text-align: left;
            padding: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        /* Buttons */
        .btn-primary, .btn-warning, .btn-danger {
            border-radius: 30px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #a39074;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-primary:hover {
            background-color: #8d7a5e;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Form for User Information */
        form {
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form h2 {
            color: #a39074;
            text-align: center;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input-field:focus {
            border-color: #a39074;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #a39074;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #8d7a5e;
        }
     </style>
</head>
<body>

<div id="menu" class="sidebar">
    <h2 class="text-center" style="color: #a39074;font-size: 20px;">Shop Bán Đồ Em Bé</h2>

  
</div>

<div class="header">
<div class="search-bar">
    <input type="text" placeholder="Tìm kiếm vai trò..." id="searchInput" />
    <!-- <button type="button" id="searchButton" class="search-button">Tìm kiếm</button> -->
    <div
        id="searchResults"
        style="display: none; position: absolute; background: #fff; border: 1px solid #ddd; border-radius: 5px; max-width: 400px; padding: 10px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000;">
    </div>
</div>


<script>
    document.getElementById("searchInput").addEventListener("input", function () {
    const keyword = document.getElementById("searchInput").value.trim();
    const resultsContainer = document.getElementById("searchResults");

    if (keyword === "") {
        resultsContainer.style.display = "none";
        return;
    }

    // Gửi yêu cầu tìm kiếm tới backend (tìm kiếm theo vai trò)
    fetch(`/web_new_born/new_born/timkiemnv.php?keyword=${encodeURIComponent(keyword)}&searchBy=role`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Lỗi phản hồi từ máy chủ");
            }
            return response.json();
        })
        .then(data => {
            resultsContainer.innerHTML = ""; // Xóa kết quả cũ

            if (data.length === 0) {
                resultsContainer.innerHTML = "<p>Không tìm thấy vai trò phù hợp.</p>";
                resultsContainer.style.display = "block";
                return;
            }

            // Hiển thị danh sách người dùng tìm được
            const resultList = document.createElement("ul");
            resultList.style.listStyle = "none";
            resultList.style.margin = "0";
            resultList.style.padding = "0";

            data.forEach(item => {
                const listItem = document.createElement("li");
                listItem.style.marginBottom = "10px";
                listItem.innerHTML = `
                    <div style="display: flex; align-items: center;">
                        <div>
                            <a href="/web_new_born/new_born/Frontend_web/ChiTietNguoiDung.php?id=${item.id}" 
                                style="text-decoration: none; color: #333; font-weight: bold;">
                                ${item.name} - ${item.role}
                            </a>
                            <p style="margin: 5px 0; color: #888;">
                                Vai trò: ${item.role}
                            </p>
                        </div>
                    </div>
                `;
                resultList.appendChild(listItem);
            });

            resultsContainer.appendChild(resultList);
            resultsContainer.style.display = "block";
        })
        .catch(error => {
            console.error("Lỗi tìm kiếm:", error);
            alert("Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại!");
            resultsContainer.style.display = "none";
        });
});

// Ẩn kết quả khi nhấp ra ngoài
document.addEventListener("click", function (event) {
    const resultsContainer = document.getElementById("searchResults");
    const searchInput = document.getElementById("searchInput");

    if (
        !resultsContainer.contains(event.target) &&
        event.target !== searchInput
    ) {
        resultsContainer.style.display = "none";
    }
});

</script>




    
</div>

<div class="content" style="margin-top: 30px;">
    <h2>Quản lý nhân viên</h2>

    <!-- Add Staff Form -->
    <form action="../Backend_thongtinnguoidung/add_nv.php" method="POST" onsubmit="return validatePassword()">
        <input type="text" class="input-field" name="name" placeholder="Tên Của Bạn" required>
        <input type="tel" class="input-field" name="phone" placeholder="Số Điện Thoại" required>
        <input type="email" class="input-field" name="email" placeholder="Email Của Bạn" required>
        <input type="text" class="input-field" name="address" placeholder="Địa Chỉ Của Bạn" required>
        <input type="password" class="input-field" name="password" placeholder="Mật Khẩu Của Bạn" required>
        <select class="input-field" name="role" required>
            <!-- <option value="user">Khách hàng</option> -->
            <option value="nhanvien">Nhân viên</option>
        </select>
        <button type="submit" class="btn btn-primary">Thêm nhân viên</button>
    </form>

    <h2>Danh sách nhân viên</h2>
    <div class="table-container">
        <table class="table table-bordered">
        <table class="table table-bordered">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên nhân viên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Vai trò</th> <!-- New column for role -->
            <th>Mật khẩu</th> <!-- New column for password -->
            <th>Thao tác</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $stt = 1;
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . ucfirst($row['role']) . "</td>"; // Displaying role with capitalized first letter
                echo "<td>" . $row['password'] . "</td>"; // Displaying the password
                echo "<td>
                    
                        <a href='../Backend_thongtinnguoidung/xoa_nv.php?id=" . $row['id'] . "' class='btn btn-primary' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' class='text-center'>Chưa có nhân viên nào</td></tr>";
        }
        ?>
    </tbody>
</table>
        <div class="form-group">
            <!-- <button type="submit" class="btn btn-primary">Cập nhật</button> -->
            <button class="btn btn-primary" type="button"><a href="./Menu.php">QUAY LẠI</a></button>
             </div>
    </div>
    
</div>

</body>
</html>

<?php $conn->close(); ?>
