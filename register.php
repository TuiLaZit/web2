<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/trang-chu.css?v=<?php echo time(); ?>" />
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
    crossorigin="anonymous" />
</head>

<body style="background-color: #d1e2f4; position: relative">
  <script>
    <?php
    // Start the session to access error messages
    session_start();
    include('./admincp/config/config.php');
    ?>
  </script>


  <img
    src="./img/register-banner-1.png"
    alt="hinh-dang-ky"
    style="position: absolute; top: 20px; left: 100px; width: 250px" />
  <img
    src="./img/register-banner-2.png"
    alt="hinh-dang-ky"
    style="position: absolute; top: 20px; right: 100px; width: 250px" />
  <img
    src="./img/register-banner-3.png"
    alt="hinh-dang-ky"
    style="position: absolute; bottom: 20px; left: 100px; width: 250px" />
  <img
    src="./img/register-banner-4.png"
    alt="hinh-dang-ky"
    style="position: absolute; bottom: 20px; right: 100px; width: 250px" />
  <section>
    <div class="container">
      <div class="registration-form" style="width: 100%">
        <div class="logo">
          <img src="<?php echo $baseUrl ?>/img/logo.png?v=<?php echo time() ?>" alt="KConner-logo" style="padding: 10px; border-radius: 35%" />
          <h2>Đăng Ký Khách Hàng</h2>
        </div>
        <form>
          <input name="register" value="true" hidden />
          <div style="display: flex; padding: 50px">
            <div style="margin-right: 50px; flex: 1;">
              <input name="account" type="text" placeholder="Tên đăng nhập (*)" />
              <p id="account-alert" class="alert" style="color: red;">
              </p>
              <input name="name" type="text" placeholder="Họ và tên (*)" />
              <p id="name-alert" class="alert" style="color: red;">
              </p>
              <input name="email" type="email" placeholder="Email (*)" />
              <p id="email-alert" class="alert" style="color: red;">
              </p>
              <input name="pNumber" type="text" placeholder="Số điện thoại (*)" />
              <p id="pNumber-alert" class="alert" style="color: red;">
              </p>
            </div>
            <div style="flex: 1;">
              <select name="province" id="province-input">
                <option value="">Tỉnh / Thành phố (*)</option>
              </select>
              <p id="province-alert" class="alert" style="color: red;">
              </p>
              <select name="district" id="district-input" disabled>
                <option value="">Quận / Huyện (*)</option>
              </select>
              <p id="district-alert" class="alert" style="color: red;">
              </p>
              <select name="ward" id="ward-input" disabled>
                <option value="">Xã / Phường / Thị trấn (*)</option>
              </select>
              <p id="ward-alert" class="alert" style="color: red;">
              </p>
              <input name="address" type="text" id="address" placeholder="Địa chỉ (*)" disabled />
              <p id="address-alert" class="alert" style="color: red;">
              </p>
              <input name="password" type="password" placeholder="Mật khẩu (*)" />
              <p id="password-alert" class="alert" style="color: red;">
              </p>
              <input
                name="re-password"
                type="password"
                placeholder="Xác nhận mật khẩu (*)" />
              <p id="re-password-alert" class="alert" style="color: red;">
              </p>
            </div>
          </div>

          <div class="alert alert-danger" style="color: red; margin: 10px 20px;">

          </div>
          <div
            style="
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
              ">
            <button type="submit" style="width: 200px">Đăng ký</button>
          </div>
          <p class="register-link">
            Bạn đã có tài khoản? <a href="./login.php">Đăng nhập ngay</a>
          </p>
        </form>
      </div>
    </div>
  </section>

  <script src="./admincp/js/vietnamese-provinces-data.js"></script>
  <script src="./js/register.js?v=<?php echo time(); ?>"></script>
</body>

</html>