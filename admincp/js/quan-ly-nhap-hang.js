document.querySelector("input[name='ImportDate']").value = new Date()
  .toISOString()
  .split("T")[0];

// Display model add
const addID = document.getElementById("add-product");

function openAddProductModel(e) {
  document
    .getElementsByClassName("model-add-product-container")[0]
    .classList.add("open");
}

addID.addEventListener("click", openAddProductModel);
// --------------------------------------------------------------------------------- //

// Hide model add product
const closeID = document.getElementById("close-add-product");
const cancelID = document.getElementById("cancel-add-product");
const clickHideModelID = document.getElementById("click-hide-model");

function cancelModel(e) {
  document
    .getElementsByClassName("model-add-product-container")[0]
    .classList.remove("open");
}

closeID.addEventListener("click", cancelModel);
cancelID.addEventListener("click", cancelModel);
clickHideModelID.addEventListener("click", cancelModel);
// --------------------------------------------------------------------------------- //

const editIconButtonEles = document.querySelectorAll(".edit-import");
// Get ID eye icon
for (let i = 0; i < editIconButtonEles.length; i++) {
  const idNhapHang = editIconButtonEles[i].dataset.idNhapHang;

  editIconButtonEles[i].addEventListener("click", () => {
    document
      .getElementsByClassName("model-edit-product-container")[0]
      .classList.add("open");
    const importData = imports.find(
      (importData) => importData.IdNhapHang === idNhapHang
    );
    const editInputForm = document.querySelector("#edit-product-form");
    // Set the values to the form fields
    if (importData) {
      // For the select dropdown (IdSP)
      const selectElement = editInputForm.querySelector('select[name="IdSP"]');
      if (selectElement) {
        selectElement.value = importData.IdSP;
      }

      // For regular input fields
      editInputForm.querySelector('input[name="ImportPrice"]').value =
        importData.ImportPrice;
      editInputForm.querySelector('input[name="ImportQuantity"]').value =
        importData.ImportQuantity;
      editInputForm.querySelector('input[name="ImportDate"]').value =
        importData.ImportDate;
    }
    editInputForm.addEventListener("submit", (e) => {
      editProduct(e, idNhapHang);
    });
  });
}

// Edit product
async function editProduct(event, IdNhapHang) {
  event.preventDefault();
  const editProductForm = document.querySelector("#edit-product-form");
  const formData = new FormData(editProductForm);
  formData.set("IdNhapHang", IdNhapHang);
  const idSP = formData.get("IdSP");
  const importPrice = formData.get("ImportPrice");
  const importQuantity = formData.get("ImportQuantity");
  const importDate = formData.get("ImportDate");

  let isError = false;

  const importPriceAlertEl = document.getElementById("ImportPrice-alert-edit");
  const importQuantityEl = document.getElementById("ImportQuantity-alert-edit");
  const importDateEl = document.getElementById("ImportDate-alert-edit");

  if (importPrice === "") {
    importPriceAlertEl.innerHTML = "Không được để trống giá nhập !";
    isError = true;
  }

  if (importQuantity === "") {
    importQuantityEl.innerHTML = "Không được để trống số lượng nhập !";
    isError = true;
  }

  if (!isValidNumber(importPrice) && importPrice !== "") {
    importPriceAlertEl.innerHTML = "Giá nhập không hợp lệ !";
    isError = true;
  }

  if (!isValidNumber(importQuantity) && importQuantity !== "") {
    importQuantityEl.innerHTML = "Số lượng nhập không hợp lệ !";
    isError = true;
  }

  if (isError === true) {
    return;
  }

  const res = await fetch("./modules/products/import/edit-import.php", {
    method: "post",
    body: formData,
  });
  const data = await res.json();

  if (!data.success) {
    const alertEle = document.querySelector("div.alert-edit.alert-danger");
    let message = "";
    for (let key in data.messages) {
      message += data.messages[key] + "\n";
    }
    alertEle.innerHTML = message;
  } else {
    alert("Sửa nhập hàng thành công");
    window.location.reload();
  }
}
// --------------------------------------------------------------------------------- //

// Hide edit product model
function cancelEditProductModel() {
  document
    .getElementsByClassName("model-edit-product-container")[0]
    .classList.remove("open");
}
// --------------------------------------------------------------------------------- //

// Add product
// Check data is not empty
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

const addProductForm = document.querySelector(".add-product-container");

addProductForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  var formData = new FormData(addProductForm);
  const idSP = formData.get("IdSP");
  const importPrice = formData.get("ImportPrice");
  const importQuantity = formData.get("ImportQuantity");
  const importDate = formData.get("ImportDate");

  let isError = false;

  const importPriceAlertEl = document.getElementById("ImportPrice-alert");
  const importQuantityEl = document.getElementById("ImportQuantity-alert");
  const importDateEl = document.getElementById("ImportDate-alert");

  if (importPrice === "") {
    importPriceAlertEl.innerHTML = "Không được để trống giá nhập !";
    isError = true;
  }

  if (importQuantity === "") {
    importQuantityEl.innerHTML = "Không được để trống số lượng nhập !";
    isError = true;
  }

  if (!isValidNumber(importPrice) && importPrice !== "") {
    importPriceAlertEl.innerHTML = "Giá nhập không hợp lệ !";
    isError = true;
  }

  if (!isValidNumber(importQuantity) && importQuantity !== "") {
    importQuantityEl.innerHTML = "Số lượng nhập không hợp lệ !";
    isError = true;
  }

  if (isError === true) {
    return;
  }

  const res = await fetch("./modules/products/import/add-import.php", {
    method: "post",
    body: formData,
  });
  const data = await res.json();

  if (!data.success) {
    const alertEle = document.querySelector("div.alert.alert-danger");
    let message = "";
    for (let key in data.messages) {
      message += data.messages[key] + "\n";
    }
    alertEle.innerHTML = message;
  } else {
    alert("Nhập hàng thành công");
    window.location.reload();
  }

  return;
});
// --------------------------------------------------------------------------------- //
