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


// Truy vấn dữ liệu doanh thu theo ngày
$query = "
    SELECT t.ngayThanhToan, SUM(t.tongTien) AS tongDoanhThu
    FROM thanhtoan t
    GROUP BY t.ngayThanhToan
    ORDER BY t.ngayThanhToan DESC
";

// Thực thi truy vấn và lấy kết quả
$result = $conn->query($query);

// Mảng để chứa dữ liệu cho biểu đồ
$dates = [];
$sales = [];

// Lấy dữ liệu từ kết quả truy vấn
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['ngayThanhToan'];
    $sales[] = (float) $row['tongDoanhThu'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Bán Đồ Em Bé - Thống Kê Đơn Hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            width: 100%;;
          
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        /* Chart */
        .chart-container {
            margin-top: 20px;
            width: 70%;
            height: 300px;
         
        }
        /* màu trc primary xanh css vào #a39074 */
        .btn-primary {
        background-color: #a39074;
        border: none;
    }
    </style>
</head>

<body>

    <!-- Sidebar -->
   

     <div class="header">
        <h3 style="color: #a39074;">Thống Kê Đơn Hàng</h3>
    </div>

  



    <!-- Main content -->
    <div class="content">
        <div class="table-container">
            <h2 style="color: #a39074;">Thống kê</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày thanh toán</th>
                        <th>Tổng tiền (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <?php
                    if ($result->num_rows > 0) {
                        $stt = 1;
                        // Xuất dữ liệu từng hàng
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $stt++ . "</td>";
                            echo "<td>" . $row['hoTen'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['soDienThoai'] . "</td>";
                            echo "<td>" . $row['diaChi'] . "</td>";
                            echo "<td>" . $row['ngayThanhToan'] . "</td>";
                            echo "<td>" . number_format($row['tongTien'], 0, ',', '.') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Không có đơn hàng nào.</td></tr>";
                    }
                    ?> -->
                </tbody>
            </table>
        </div>
        <form action="" class="form-group"  style="margin-top: 30px;">
            <a href="./Menu.php" class="btn btn-primary">Quay lại</a>
            <button id="printButton" onclick="window.print(); "  class="btn btn-primary" >In Thống Kê</button>
        </form>
        <button class="btn btn-secondary" onclick="toggleChart('lineChartContainer')">Bật/Tắt Biểu Đồ Đường</button>
        <button class="btn btn-secondary" onclick="toggleChart('pieChartContainer')">Bật/Tắt Biểu Đồ Tròn</button>
        <!-- Biểu đồ đường -->
        <div class="chart-container">
  
    <!-- <button class="btn btn-secondary" onclick="toggleChart('lineChartContainer')">Bật/Tắt Biểu Đồ Đường</button> -->
    <div id="lineChartContainer">
    <h4 style="color: #a39074;">Biểu đồ đường</h4>
        <canvas id="lineChart"></canvas>
    </div>
</div>

     
<div class="chart-container" style="margin-top: 250px;">
   
    <!-- <button class="btn btn-secondary" onclick="toggleChart('pieChartContainer')">Bật/Tắt Biểu Đồ Tròn</button> -->
    <div id="pieChartContainer">
    <h4 style="color: #a39074;">Biểu đồ tròn</h4>
        <canvas id="pieChart"></canvas>
    </div>
</div>
        
     
    </div>

<script>


   // Hàm ẩn/hiện biểu đồ
   function toggleChart(chartId) {
        var chartContainer = document.getElementById(chartId);
        if (chartContainer.style.display === 'none' || chartContainer.style.display === '') {
            chartContainer.style.display = 'block'; // Hiển thị biểu đồ
        } else {
            chartContainer.style.display = 'none'; // Ẩn biểu đồ
        }
    }
    //Xuat thong keeeeee 


    function printReport() {
        var reportContent = document.querySelector('.content').innerHTML;

        var originalContent = document.body.innerHTML;
        document.body.innerHTML = reportContent;

        window.print();

        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>

<!-- CSS để in nội dung chính -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .content, .content * {
            visibility: visible;
        }
        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        .btn, .sidebar, .header {
            display: none;
        }
    }
</style>

    <!-- Script to render chart -->
 <script>
   // Biểu đồ đường
var ctx = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>, // Dữ liệu ngày
        datasets: [{
            label: 'Doanh thu theo ngày (VNĐ)',
            data: <?php echo json_encode($sales); ?>, // Dữ liệu doanh thu
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' VNĐ';
                    }
                }
            }
        }
    }
});

// Biểu đồ tròn
var pieCtx = document.getElementById('pieChart').getContext('2d');
var pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($dates); ?>, // Dữ liệu nhãn ngày
        datasets: [{
            label: 'Tỷ lệ doanh thu',
            data: <?php echo json_encode($sales); ?>, // Dữ liệu doanh thu
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)'
            ],
            hoverOffset: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString() + ' VNĐ';
                    }
                }
            }
        }
    }
});

</script>

</body>

</html>
