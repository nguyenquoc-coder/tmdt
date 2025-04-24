<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register Form - Newborn Shop</title>
    <link rel="stylesheet" href="./css/dangnhap.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Giữ nguyên CSS của bạn */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f4f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 10px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: #FFF8DC;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px 20px;
            text-align: center;
            margin: 20px;
        }

        .form-box {
            width: 100%;
        }

        .title {
            font-size: 26px;
            color: #D2B48C;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            font-size: 14px;
            color: #C3B091;
            margin-bottom: 25px;
        }

        .input-group h2 {
            color: #C3B091;
            margin-bottom: 15px;
        }

        .input-field {
            width: calc(100% - 50px);
            padding: 12px;
            margin: 10px 10px 20px 10px;
            border: 2px solid #D2B48C;
            border-radius: 25px;
            font-size: 16px;
            background: #FFF8DC;
            outline: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #C3B091;
        }

        .submit-btn {
            background-color: #D2B48C;
            color: white;
            border: none;
            padding: 12px;
            width: 95%;
            height: 45px;
            border-radius: 25px;
            font-size: 18px;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #C3B091;
        }

        .check-box {
            margin-right: 10px;
        }

        p {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            color: #D2B48C;
            cursor: pointer;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        input::placeholder {
            color: #C3B091;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #FFF8DC;
            border-radius: 15px;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content p {
            color: #C3B091;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .modal-content.success p {
            color: #4CAF50;
        }

        .modal-content.error p {
            color: #e74c3c;
        }

        .modal-content button {
            background-color: #D2B48C;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #C3B091;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-box">
            <h1 class="title">Shop bán đồ sơ sinh</h1>
            <p class="subtitle">Vui lòng đăng nhập hoặc đăng ký để nhận những sản phẩm dễ thương nhất cho bé!</p>

            <!-- Form đăng nhập -->
            <form id="loginForm" class="input-group" action="../Backend_dkdn/login.php" method="POST"
                style="display: block;">
                <h2>Đăng Nhập</h2>
                <input type="text" class="input-field" name="email" id="login-email" placeholder=" Email Của Bạn"
                    style="font-family: Arial, FontAwesome" required><br>
                <input type="password" class="input-field" name="password" placeholder=" Mật Khẩu Của Bạn"
                    style="font-family: Arial, FontAwesome" required><br>
                <input type="checkbox" class="check-box" id="remember-password"><label for="remember-password">Nhớ Mật
                    Khẩu</label><br>
                <button type="submit" class="submit-btn">Đăng Nhập</button>
                <p>Bạn chưa có tài khoản? <a href="javascript:void(0)" onclick="showRegisterForm()">Đăng Ký</a></p>
            </form>

            <!-- Form đăng ký -->
            <form id="registerForm" class="input-group" action="../Backend_dkdn/register.php" method="POST"
    style="display: none;" novalidate>
    <h2>Đăng Ký</h2>
    <input type="text" class="input-field" name="name" placeholder=" Tên Của Bạn"
        style="font-family: Arial, FontAwesome" required
        oninvalid="this.setCustomValidity('Vui lòng nhập tên của bạn!')"
        oninput="this.setCustomValidity('')"><br>
    <input type="tel" class="input-field" name="phone" placeholder=" Số Điện Thoại"
        style="font-family: Arial, FontAwesome" required
        oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại của bạn!')"
        oninput="this.setCustomValidity('')"><br>
    <input type="email" class="input-field" name="email" id="register-email" placeholder=" Email Của Bạn"
        style="font-family: Arial, FontAwesome" required
        oninvalid="this.setCustomValidity('Vui lòng nhập email')"
        oninput="this.setCustomValidity('')"><br>
    <input type="text" class="input-field" name="address" placeholder=" Địa Chỉ Của Bạn"
        style="font-family: Arial, FontAwesome" required
        oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ của bạn!')"
        oninput="this.setCustomValidity('')"><br>
    <input type="password" class="input-field" name="password" id="password"
        placeholder=" Mật Khẩu Của Bạn" style="font-family: Arial, FontAwesome"
        required minlength="8"
        oninvalid="this.setCustomValidity('Mật khẩu phải dài ít nhất 8 ký tự!')"
        oninput="this.setCustomValidity('')"><br>
    <input type="password" class="input-field" name="confirmPassword" id="confirm-password"
        placeholder=" Nhập Lại Mật Khẩu" style="font-family: Arial, FontAwesome"
        required minlength="8"
        oninvalid="this.setCustomValidity('Vui lòng xác nhận lại mật khẩu!')"
        oninput="this.setCustomValidity('')"><br>
    <input type="checkbox" class="check-box" id="terms"><label for="terms">Tôi đồng ý với các điều khoản và
        điều kiện</label><br>
    <button type="submit" class="submit-btn">Đăng Kí</button>
    <p>Đã có tài khoản? <a href="javascript:void(0)" onclick="showLoginForm()">Đăng Nhập</a></p>
</form>
        </div>
    </div>

    <!-- Modal for Messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content" id="modalContent">
            <p id="modalMessage"></p>
            <button onclick="closeModal()">Đóng</button>
        </div>
    </div>

    <!-- JavaScript sẽ được thêm ở bước tiếp theo -->

    <script>
        function showRegisterForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "block";
}

function showLoginForm() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("registerForm").style.display = "none";
}

function showModal(message, isSuccess = false, callback = null) {
    const modal = document.getElementById("messageModal");
    const modalContent = document.getElementById("modalContent");
    const modalMessage = document.getElementById("modalMessage");
    modalMessage.textContent = message;
    modalContent.className = "modal-content " + (isSuccess ? "success" : "error");
    modal.style.display = "flex";
    if (callback) {
        modalContent.querySelector("button").onclick = () => {
            closeModal();
            callback();
        };
    } else {
        modalContent.querySelector("button").onclick = closeModal;
    }
}

function closeModal() {
    document.getElementById("messageModal").style.display = "none";
}

document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('../Backend_dkdn/login.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log('Login response:', data);
            if (data.status === 'success') {
                showModal('Đăng nhập thành công!', true, () => {
                    window.location.href = data.redirect;
                });
            } else {
                showModal(data.message, false);
            }
        })
        .catch(error => {
            console.error('Lỗi fetch (login):', error);
            showModal('Lỗi kết nối!', false);
        });
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Lấy các trường
    const nameInput = document.querySelector('#registerForm input[name="name"]');
    const phoneInput = document.querySelector('#registerForm input[name="phone"]');
    const emailInput = document.querySelector('#register-email');
    const addressInput = document.querySelector('#registerForm input[name="address"]');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const termsCheckbox = document.getElementById('terms');

    // Kiểm tra xem input có tồn tại không
    if (!emailInput) {
        console.error('Lỗi: Không tìm thấy input#register-email');
        showModal('Lỗi hệ thống: Không tìm thấy trường email!', false);
        return;
    }

    // Lấy giá trị
    const name = nameInput ? nameInput.value.trim() : '';
    const phoneNumber = phoneInput ? phoneInput.value.trim() : '';
    const email = emailInput.value.trim();
    const address = addressInput ? addressInput.value.trim() : '';
    const password = passwordInput ? passwordInput.value.trim() : '';
    const confirmPassword = confirmPasswordInput ? confirmPasswordInput.value.trim() : '';

    // Debug: Log tất cả giá trị
    console.log('Form values:', {
        name,
        phoneNumber,
        email,
        address,
        password,
        confirmPassword
    });

    // Kiểm tra nếu tất cả các trường đều trống
    if (name === '' && phoneNumber === '' && email === '' && address === '' && password === '' && confirmPassword === '') {
        showModal('Không được để trống tất cả các trường!', false);
        return;
    }

    // 1. Kiểm tra tên
    if (name === '' || /^\s+$/.test(name)) {
        showModal('Vui lòng nhập tên của bạn!', false);
        return;
    }
    if (name.length < 5) {
        showModal('Tên quá ngắn, phải từ 5 ký tự trở lên!', false);
        return;
    }
    if (name.length > 50) {
        showModal('Tên quá dài, tối đa 50 ký tự!', false);
        return;
    }

    // 2. Kiểm tra số điện thoại
    if (phoneNumber === '') {
        showModal('Vui lòng nhập số điện thoại!', false);
        return;
    }
    if (phoneNumber.length !== 10) {
        showModal('Số điện thoại phải gồm đúng 10 chữ số!', false);
        return;
    }
    if (!/^\d+$/.test(phoneNumber)) {
        showModal('Số điện thoại không được chứa ký tự đặc biệt!', false);
        return;
    }
    if (/(\d)\1{5,}/.test(phoneNumber)) {
        showModal('Số điện thoại không được chứa 6 số giống nhau liên tiếp trở lên!', false);
        return;
    }

    // 3. Kiểm tra email
    if (email === '') {
        showModal('Vui lòng nhập email!', false);
        return;
    }
    if (email.startsWith('@')) {
        showModal('Email không hợp lệ, phải có tên người dùng trước @!', false);
        return;
    }
    if (!email.includes('@')) {
        showModal('Email phải chứa ký tự "@"!', false);
        return;
    }
    if (email.match(/@/g)?.length > 1) {
        showModal('Email không hợp lệ!', false);
        return;
    }
    if (/\s/.test(email)) {
        showModal('Email không được chứa khoảng trắng!', false);
        return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showModal('Email không hợp lệ!', false);
        return;
    }

    // 4. Kiểm tra địa chỉ
    if (address === '' || /^\s+$/.test(address)) {
        showModal('Vui lòng nhập địa chỉ!', false);
        return;
    }
    if (address.length < 5) {
        showModal('Địa chỉ quá ngắn, phải từ 5 ký tự trở lên!', false);
        return;
    }
    if (address.length > 70) {
        showModal('Địa chỉ không được dài hơn 70 ký tự!', false);
        return;
    }
    if (/[^a-zA-Z0-9\s,.\-À-ỹà-ỹ]/.test(address)) {
        showModal('Địa chỉ không được chứa ký tự đặc biệt!', false);
        return;
    }

    // 5. Kiểm tra mật khẩu
    if (password === '') {
        showModal('Vui lòng nhập mật khẩu!', false);
        return;
    }
    if (password.length < 8) {
        showModal('Mật khẩu phải dài ít nhất 8 ký tự!', false);
        return;
    }
    if (confirmPassword === '') {
    showModal('Vui lòng xác nhận lại mật khẩu!', false);
    return;
    }
    if (password !== confirmPassword) {
        showModal('Mật khẩu và mật khẩu xác nhận không khớp!', false);
        return;
    }

  

    // Kiểm tra điều khoản
    if (!termsCheckbox.checked) {
        showModal('Bạn phải đồng ý với các điều khoản và điều kiện!', false);
        return;
    }

    // Debug: Log FormData trước khi gửi
    const formData = new FormData(this);
    console.log('FormData entries:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    // Gửi dữ liệu
    fetch('../Backend_dkdn/register.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data);
            if (data.success) {
                showModal('Đăng ký thành công!', true, showLoginForm);
            } else {
                showModal(data.message, false);
            }
        })
        .catch(error => {
            console.error('Lỗi fetch (register):', error);
            showModal('Lỗi kết nối!', false);
        });
});
    </script>
</body>
</html>