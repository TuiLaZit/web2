<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/trang-chu.css" />
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

    // Get errors from session if they exist
    $errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
    $formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
    ?>

    function getFormData() {
      // You need to output this data from PHP to JavaScript
      // This can be done by adding a script tag with the data in your PHP file
      // For example:
      /*
        <?php if (isset($_SESSION['form_data'])): ?>
        const formData = <?php echo json_encode($_SESSION['form_data']); ?>;
        <?php else: ?>
        const formData = null;
        <?php endif; ?>
      */

      // For now, let's assume the data is available in a global variable
      // Return null if no data is available
      return typeof phpFormData !== 'undefined' ? phpFormData : null;
    }

    let phpFormData = null;

    // Pass PHP session data to JavaScript
    <?php if (isset($_SESSION['form_data'])): ?>
      phpFormData = <?php echo json_encode($_SESSION['form_data']); ?>;

      // Clear session data after retrieving it
      if (isset($_SESSION['form_errors'])) {
        unset($_SESSION['form_errors']);
      }
      if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
      }
    <?php endif; ?>
  </script>


  <img
    src="/assets/Image/trang-chu/keyboard-sticker-1.png"
    alt="hinh-dang-ky"
    style="position: absolute; top: 20px; left: 100px; width: 250px" />
  <img
    src="/assets/Image/trang-chu/keyboard-sticker-3.png"
    alt="hinh-dang-ky"
    style="position: absolute; top: 20px; right: 100px; width: 250px" />
  <img
    src="/assets/Image/trang-chu/keyboard-sticker-2.png"
    alt="hinh-dang-ky"
    style="position: absolute; bottom: 20px; left: 100px; width: 250px" />
  <img
    src="/assets/Image/trang-chu/keyboard-sticker-4.png"
    alt="hinh-dang-ky"
    style="position: absolute; bottom: 20px; right: 100px; width: 250px" />
  <section>
    <div class="container">
      <div class="registration-form">
        <div class="logo">
          <img src="/assets/Image/logo.png" alt="SGU Click Logo" />
          <h2>Đăng Ký Khách Hàng</h2>
        </div>
        <form method="post" action="./admincp/modules/customers/them-sua.php">
          <input name="addKH" hidden />
          <input name="register" value="true" hidden />
          <div style="display: flex; padding: 50px">
            <div style="margin-right: 50px">
              <input name="account" type="text" placeholder="Tên đăng nhập" required
                value="<?php echo isset($formData['account']) ? htmlspecialchars($formData['account']) : ''; ?>"
              />
              <input name="name" type="text" placeholder="Họ và tên (*)" required
                value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>"
              />
              <input name="email" type="email" placeholder="Email"
                value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
              />
              <input name="pNumber" type="text" placeholder="Số điện thoại (*)" required
                value="<?php echo isset($formData['pNumber']) ? htmlspecialchars($formData['pNumber']) : ''; ?>"
              />
            </div>
            <div>
              <select name="province" id="province-input">
                <option value="">Tỉnh / Thành phố</option>
              </select>
              <select name="district" id="district-input" disabled>
                <option value="">Quận / Huyện</option>
              </select>
              <select name="ward" id="ward-input" disabled>
                <option value="">Xã / Phường / Thị trấn</option>
              </select>
              <input name="address" type="text" id="address" placeholder="Địa chỉ" disabled
                value="<?php echo isset($formData['address']) ? htmlspecialchars($formData['address']) : ''; ?>"
              />

              <input name="password" type="password" placeholder="Mật khẩu (*)" required />
              <input
                type="password"
                placeholder="Xác nhận mật khẩu (*)"
                required />
            </div>
          </div>

          <?php if (isset($errors['db'])): ?>
            <div class="alert alert-danger" style="color: red; margin: 10px 20px;">
              <?php echo $errors['db']; ?>
            </div>
          <?php endif; ?>
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
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Retrieve stored form data from PHP session (if available)

      const formData = getFormData();

      // Chèn dữ liệu thành phố
      populateProvinces();

      // If there's stored province value, select it and populate districts
      if (formData && formData.province) {
        const provinceSelect = document.getElementById("province-input");
        provinceSelect.value = formData.province;

        // Trigger province change to populate districts
        if (formData.province) {
          populateDistricts(formData.province);

          // If there's stored district value, select it and populate wards
          if (formData.district) {
            const districtSelect = document.getElementById("district-input");
            districtSelect.value = formData.district;

            // Trigger district change to populate wards
            if (formData.district) {
              populateWards(formData.district);

              // If there's stored ward value, select it
              if (formData.ward) {
                const wardSelect = document.getElementById("ward-input");
                wardSelect.value = formData.ward;
              }
            }
          }
        }
      }

      // Chèn dữ liệu quận vào form chọn quận khi chọn xong thành phố
      document
        .getElementById("province-input")
        .addEventListener("change", function() {
          const selectedProvinceCode = this.value;
          if (
            selectedProvinceCode === "" ||
            selectedProvinceCode === null ||
            selectedProvinceCode === undefined
          ) {
            document.getElementById("district-input").required = false;
            document.getElementById("district-input").disabled = true;
            document.getElementById("ward-input").required = false;
            document.getElementById("ward-input").disabled = true;
            document.getElementById("address").required = false;
            document.getElementById("address").disabled = true;
            return;
          }

          populateDistricts(selectedProvinceCode);
        });



      document
        .getElementById("district-input")
        .addEventListener("change", function() {
          const selectedDistrictCode = this.value;
          if (
            selectedDistrictCode === "" ||
            selectedDistrictCode === null ||
            selectedDistrictCode === undefined
          ) {
            document.getElementById("ward-input").required = false;
            document.getElementById("ward-input").disabled = true;
            document.getElementById("address").required = false;
            document.getElementById("address").disabled = true;
            return;
          }
          populateWards(selectedDistrictCode);
        });


    });

    // --------------------------------------------------------------------------------- //

    function populateProvinces(selectId = "province-input") {
      const provinceSelect = document.getElementById(selectId);
      provinceSelect.innerHTML = `<option value="">Chọn tỉnh/thành</option>`;
      vietnameseProvinces.forEach((province) => {
        provinceSelect.innerHTML += `<option value="${province.FullName}">${province.FullName}</option>`;
      });
    }

    function populateDistricts(
      provinceCode,
      selectDistrictId = "district-input",
      selectWardId = "ward-input"
    ) {
      const districtSelect = document.getElementById(selectDistrictId);
      const wardSelect = document.getElementById(selectWardId);
      districtSelect.innerHTML = `<option value="">Chọn quận/huyện</option>`;
      districtSelect.disabled = !provinceCode;
      districtSelect.required = true;
      wardSelect.innerHTML = `<option value="">Chọn phường/xã</option>`;
      wardSelect.disabled = true;

      const province = vietnameseProvinces.find(
        (province) => province.FullName === provinceCode
      );
      province.District.forEach((district) => {
        districtSelect.innerHTML += `<option value="${district.FullName}">${district.FullName}</option>`;
      });
    }

    function populateWards(
      districtCode,
      selectProvinceId = "province-input",
      selectWardId = "ward-input",
      inputAddressId = "address"
    ) {
      const wardSelect = document.getElementById(selectWardId);
      wardSelect.innerHTML = `<option value="">Chọn phường/xã</option>`;
      wardSelect.disabled = !districtCode;
      wardSelect.required = true;

      const provinceCode = document.getElementById(selectProvinceId).value;
      const province = vietnameseProvinces.find(
        (province) => province.FullName === provinceCode
      );

      console.log(province);

      const district = province.District.find(
        (district) => district.FullName === districtCode
      );
      district.Ward.forEach((ward) => {
        wardSelect.innerHTML += `<option value="${ward.FullName}">${ward.FullName}</option>`;
      });

      document.getElementById(inputAddressId).disabled = false;
      document.getElementById(inputAddressId).required = true;
    }

    function getAddress(addressObj) {
      if (!addressObj || addressObj === null) {
        return "";
      }
      return `${addressObj.address}, ${addressObj.ward}, ${addressObj.district}, ${addressObj.province}.`;
    }
  </script>
</body>

</html>