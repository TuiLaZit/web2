<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/trang-chu.css" />
</head>

<?php
require_once("./utils.php");
// Start the session to access error messages
session_start();

if (isset($_SESSION['user'])) {
  redirect('./index.php');
}

// Get errors from session if they exist
$errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : [];
$formData = isset($_SESSION['login_data']) ? $_SESSION['login_data'] : [];

if (isset($_SESSION['login_errors'])) {
  unset($_SESSION['login_errors']);
}

if (isset($_SESSION['login_data'])) {
  unset($_SESSION['login_data']);
}
?>

<body>
  <div class="container">
    <!-- Phần Hình Minh Họa -->
    <div class="left-panel"></div>
    <!-- Phần Form Đăng Nhập -->
    <div class="right-panel">
      <div class="login-form">
        <div class="logo">
          <img src="/assets/Image/logo.png" alt="SGU Click Logo" />
          <h2>CHÀO MỪNG ĐẾN VỚI</h2>
          <p>SGU Click - Chất phím, chất click!</p>
        </div>

        <!-- Form đăng nhập -->
        <form id="formLogin" action="./controller/login-customer.php" method="post">
          <input name="login" hidden />
          <input name="username" type="text" placeholder="Tên đăng nhập" required
            value="<?php if (isset($formData['username'])) {
                      echo $formData['username'];
                    } ?>" />
          <input name="password" type="password" placeholder="Mật khẩu" required />
          <div class="extra-options">
            <a href="#" type="button" onclick="forgotPassword()">Quên mật khẩu?</a>
          </div>
          <?php if (isset($errors) && sizeof($errors) > 0): ?>
            <div class="alert alert-danger" style="color: red; margin: 10px 20px;">
              <?php print_r($errors); ?>
            </div>
          <?php endif; ?>
          <button type="submit">Đăng nhập</button>
          <p class="register-link">
            Bạn chưa có tài khoản? <a href="./register.php">Đăng ký ngay</a>
          </p>
        </form>

        <!-- Form quên mật khẩu -->
        <form style="display: none" id="formForgotPassword">
          <input
            id="phone"
            type="text"
            placeholder="Số điện thoại đã đăng ký"
            required />
          <input
            id="newPassword"
            type="password"
            placeholder="Mật khẩu mới"
            required />
          <input
            id="confirmNewPassword"
            type="password"
            placeholder="Xác nhân mật khẩu mới"
            required />
          <button type="submit" onclick="createNewPassword(event)">
            Tạo mật khẩu mới
          </button>
          <p class="register-link">
            Bạn đã có tài khoản? <a href="/dangnhap.html">Đăng nhập ngay</a>
          </p>
        </form>
      </div>
    </div>
  </div>
  <!-- Nếu để script ở đầu thì cần dùng DOMContentLoaded -->
  </script>
</body>

</html>