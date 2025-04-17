const formEle = document.querySelector("form");
document.addEventListener("DOMContentLoaded", () => {
  formEle.addEventListener("submit", submitForm);
  // Chèn dữ liệu thành phố
  populateProvinces();

  // Chèn dữ liệu quận vào form chọn quận khi chọn xong thành phố
  document
    .getElementById("province-input")
    .addEventListener("change", function () {
      const selectedProvinceFullName = this.value;
      if (
        selectedProvinceFullName === "" ||
        selectedProvinceFullName === null ||
        selectedProvinceFullName === undefined
      ) {
        document.getElementById("district-input").required = false;
        document.getElementById("district-input").disabled = true;
        document.getElementById("ward-input").required = false;
        document.getElementById("ward-input").disabled = true;
        document.getElementById("address").required = false;
        document.getElementById("address").disabled = true;
        return;
      }

      populateDistricts(selectedProvinceFullName);
    });

  document
    .getElementById("district-input")
    .addEventListener("change", function () {
      const selectedDistrictFullName = this.value;
      if (
        selectedDistrictFullName === "" ||
        selectedDistrictFullName === null ||
        selectedDistrictFullName === undefined
      ) {
        document.getElementById("ward-input").required = false;
        document.getElementById("ward-input").disabled = true;
        document.getElementById("address").required = false;
        document.getElementById("address").disabled = true;
        return;
      }
      populateWards(selectedDistrictFullName);
    });

  document
    .getElementById("ward-input")
    .addEventListener("change", function () {
      const selectedWardFullName = this.value;
      if (
        selectedWardFullName === "" ||
        selectedWardFullName === null ||
        selectedWardFullName === undefined
      ) {
        document.getElementById("address").required = false;
        document.getElementById("address").disabled = true;
        return;
      }
    });
});

// --------------------------------------------------------------------------------- //

function populateProvinces(selectId = "province-input") {
  const provinceSelect = document.getElementById(selectId);
  provinceSelect.innerHTML = `<option value="">Tỉnh / Thành phố (*)</option>`;
  vietnameseProvinces.forEach((province) => {
    provinceSelect.innerHTML += `<option value="${province.FullName}">${province.FullName}</option>`;
  });
}

function populateDistricts(
  provinceFullName,
  selectDistrictId = "district-input",
  selectWardId = "ward-input"
) {
  const districtSelect = document.getElementById(selectDistrictId);
  const wardSelect = document.getElementById(selectWardId);
  districtSelect.innerHTML = `<option value="">Quận / Huyện (*)</option>`;
  districtSelect.disabled = !provinceFullName;
  districtSelect.required = true;
  wardSelect.innerHTML = `<option value="">Xã / Phường / Thị trấn (*)</option>`;
  wardSelect.disabled = true;
  const address = document.querySelector("#address");
  address.disabled = true;

  const province = vietnameseProvinces.find(
    (province) => province.FullName === provinceFullName
  );
  province.District.forEach((district) => {
    districtSelect.innerHTML += `<option value="${district.FullName}">${district.FullName}</option>`;
  });
}

function populateWards(
  districtFullName,
  selectProvinceId = "province-input",
  selectWardId = "ward-input",
  inputAddressId = "address"
) {
  const wardSelect = document.getElementById(selectWardId);
  wardSelect.innerHTML = `<option value="">Xã / Phường / Thị trấn (*)</option>`;
  wardSelect.disabled = !districtFullName;
  wardSelect.required = true;
  address.disabled = true;

  const provinceFullName = document.getElementById(selectProvinceId).value;
  const province = vietnameseProvinces.find(
    (province) => province.FullName === provinceFullName
  );

  const district = province.District.find(
    (district) => district.FullName === districtFullName
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

async function submitForm(e) {
  e.preventDefault();

  const formData = new FormData(formEle);
  formData.set("register", "true");
  // Get form values
  const account = formData.get("account");
  const email = formData.get("email");
  const name = formData.get("name");
  const pNumber = formData.get("pNumber");
  const address = formData.get("address");
  const province = formData.get("province");
  const district = formData.get("district");
  const ward = formData.get("ward");
  const password = formData.get("password");
  const rePassword = formData.get("re-password");

  let isError = false;

  // Get alert elements
  const accountAlertEle = document.getElementById("account-alert");
  const emailAlertEle = document.getElementById("email-alert");
  const nameAlertEle = document.getElementById("name-alert");
  const pNumberAlertEle = document.getElementById("pNumber-alert");
  const addressAlertEle = document.getElementById("address-alert");
  const passwordAlertEle = document.getElementById("password-alert");
  const rePasswordAlertEle = document.getElementById("re-password-alert");

  // Validate account
  if (account === "") {
    accountAlertEle.innerHTML = "Không được để trống tên đăng nhập!";
    isError = true;
  } else if (!isValidUsername(account)) {
    accountAlertEle.innerHTML =
      "Tên đăng nhập chỉ được chứa chữ cái, chữ số, dấu gạch dưới; tối thiểu 4 ký tự và tối đa 20 ký tự !";
    isError = true;
  }

  // Validate email
  if (email === "") {
    emailAlertEle.innerHTML = "Không được để trống email!";
    isError = true;
  } else if (!isValidEmail(email)) {
    emailAlertEle.innerHTML = "Email không hợp lệ!";
    isError = true;
  }

  // Validate name
  if (name === "") {
    nameAlertEle.innerHTML = "Không được để trống tên khách hàng!";
    isError = true;
  }

  // Validate phone number
  if (pNumber === "") {
    pNumberAlertEle.innerHTML = "Không được để trống số điện thoại!";
    isError = true;
  } else if (!isValidPhoneNumber(pNumber)) {
    pNumberAlertEle.innerHTML = "Số điện thoại không hợp lệ!";
    isError = true;
  }

  // Validate address
  if (address === "" || address === null) {
    addressAlertEle.innerHTML = "Không được để trống địa chỉ!";
    isError = true;
  }

  // Validate address
  if (password === "") {
    passwordAlertEle.innerHTML = "Không được để trống mật khẩu!";
    isError = true;
  }

  // Validate address
  if (rePassword === "") {
    rePasswordAlertEle.innerHTML = "Không được để trống xác nhận mật khẩu!";
    isError = true;
  }

  if (password !== rePassword) {
    rePasswordAlertEle.innerHTML = "Mật khẩu không khớp!";
    isError = true;
  }

  if (isError === true) {
    return;
  }

  const response = await fetch("./admincp/modules/customers/them-sua.php", {
    method: "post",
    body: formData,
  });
  const data = await response.json();

  if (data.success) {
    alert("Đăng ký thành công");
    window.location.href = "./login.php";
  } else {
    const alertEle = document.querySelector("div .alert.alert-danger");
    let message = "";
    for (let key in data.formErrors) {
      message += data.formErrors[key] + "\n";
    }
    alertEle.innerHTML = message;
  }
}

function isValidUsername(username) {
  const regex = /^[a-zA-Z0-9_]{4,20}$/;
  return regex.test(username);
}

function isNotEmpty(e, message, idAlertEle) {
  const alertEle = document.getElementById(idAlertEle);
  if (e.target.value === "") {
    alertEle.innerHTML = `Không được để trống ${message} sản phẩm !`;
    isError = true;
  } else {
    alertEle.innerHTML = ``;
    isError = false;
  }
}

function isValidNumber(number) {
  const regex = /^[0-9]+(\.[0-9]+)?$/;
  return regex.test(number) && parseFloat(number) > 0;
}
// Helper functions for validation
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidPhoneNumber(phone) {
  const phoneRegex = /^[0-9]{10}$/; // Assuming 10-digit phone number
  return phoneRegex.test(phone);
}
