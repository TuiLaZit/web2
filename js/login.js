// hàm quên pass
function forgotPassword() {
  document.getElementById("formLogin").style.display = "none"; // ẩn trang login
  document.getElementById("formForgotPassword").style.display = "block"; // mở trang quên mật khẩu
}

// tạo password mới
async function createNewPassword(e) {
  e.preventDefault(); // ngăn hành vi mặc định của submit

  var formData = new FormData(document.getElementById("formForgotPassword"));

  // Get form values
  // const phone = formData.get("Phone");
  // const password = formData.get("Password");
  // const rePassword = formData.get("RePassword");

  const response = await fetch(
    "./controller/login-customer.php?reset-password",
    {
      method: "POST",
      body: formData,
    }
  );

  if (!response.ok) {
    alert("Có lỗi xảy ra!");
  }
  const data = await response.json();

  if (!data.success) {
    const alertEle = document.querySelector("div.alert-danger");
    let message = "";
    for (let key in data.messages) {
      message += data.messages[key] + "\n";
    }
    alertEle.innerHTML = message;
    return;
  }

  alert("Thay đổi mật khẩu thành công!");
  window.location.href = "./login.php?action=login&status=password_reset_success";
}
