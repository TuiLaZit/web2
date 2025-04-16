// Display model add product
const addID = document.getElementById("add-product");

function openAddProductModel(e) {
  document
    .getElementsByClassName("model-add-product-container")[0]
    .classList.add("open");
}
// Get the current URL
let url = new URL(window.location.href);
let params = new URLSearchParams(url.search);
if (params.get("adding")) {
  openAddProductModel();
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

// Cancel model view product details
const cancelViewDetailsID = document.getElementsByClassName(
  "cancel-view-details"
)[0];

function cancelViewDetails(e) {
  document
    .getElementsByClassName("model-view-details-container")[0]
    .classList.remove("open");

  // Get the current URL
  let url = new URL(window.location.href);

  // Get the search parameters (query string)
  let params = new URLSearchParams(url.search);

  // Remove the query parameter (e.g., 'id')
  params.delete("IdKH");

  // Update the URL without reloading the page
  url.search = params.toString();

  // Replace the current history state with the new URL (without reloading)
  window.location.href = url.toString();
}

cancelViewDetailsID.addEventListener("click", cancelViewDetails);
// --------------------------------------------------------------------------------- //

// Display products data from localStorage into table
const table = document.querySelector(".table-product-container table");
const viewDetailsID = document.getElementsByClassName("view-details");

//  Get ID trash icon
const deleteProductEles = document.getElementsByClassName("delete-product");

for (let i = 0; i < deleteProductEles.length; i++) {
  const productID = deleteProductEles[i].dataset.productid;

  deleteProductEles[i].addEventListener("click", (e) => {
    openConfirmDeleteModel(productID);
  });
}
// --------------------------------------------------------------------------------- //

const editProductForm = document.querySelector("#edit-product-form");

// Get ID eye icon
for (let i = 0; i < viewDetailsID.length; i++) {
  const productID = viewDetailsID[i].dataset.productid;

  // Get the current URL
  const url = new URL(window.location.href);

  // Access the query parameters using URLSearchParams
  const params = new URLSearchParams(url.search);

  // Get a specific query parameter, e.g., 'id'
  const id = params.get("IdKH");

  viewDetailsID[i].addEventListener("click", (e) => {
    let url = new URL(window.location);
    url.searchParams.set("IdKH", productID);
    window.location.href = url.toString();
  });

  if (productID === id) {
    document
      .getElementsByClassName("model-view-details-container")[0]
      .classList.add("open");

    // Display edit product model
    const editButton = document.getElementById("edit-product");

    function openEditProductModel(productFound) {
      document
        .getElementsByClassName("model-edit-product-container")[0]
        .classList.add("open");
    }
    editButton.addEventListener("click", () => {
      openEditProductModel();
    });

    if (params.get("editing")) {
      openEditProductModel();
    }

    editProductForm.addEventListener("submit", (e) => {
      editProduct(e, productID);
    });
  }
}

// Edit product
function editProduct(event, productID) {
  event.preventDefault();

  var formData = new FormData(event.target);

  // Get form values
  const account = formData.get("account");
  const email = formData.get("email");
  const name = formData.get("name");
  const pNumber = formData.get("pNumber");
  const address = formData.get("address"); // Note: there's a typo in the HTML - "addres" instead of "address"
  const status = formData.get("status");

  let isError = false;

  // Get alert elements
  const accountAlertEle = document.getElementById("account-alert-edit");
  const emailAlertEle = document.getElementById("email-alert-edit");
  const nameAlertEle = document.getElementById("name-alert-edit");
  const pNumberAlertEle = document.getElementById("pNumber-alert-edit");
  const addressAlertEle = document.getElementById("address-alert-edit");

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
  if (address === "") {
    addressAlertEle.innerHTML = "Không được để trống địa chỉ!";
    isError = true;
  }

  if (isError === true) {
    return;
  }

  cancelEditProductModel();
  editProductForm.submit();
}

function isValidUsername(username) {
  const regex = /^[a-zA-Z0-9_]{4,20}$/;
  return regex.test(username);
}

// --------------------------------------------------------------------------------- //

// Hide edit product model
function cancelEditProductModel() {
  document
    .getElementsByClassName("model-edit-product-container")[0]
    .classList.remove("open");
}
// --------------------------------------------------------------------------------- //

// Preview image
var openFile = function (input, idPreviewElement, idAlertEle) {
  const file = input.files[0];

  const reader = new FileReader();
  reader.onload = function () {
    const dataURL = reader.result;
    const output = document.getElementById(idPreviewElement);
    output.src = dataURL;

    const alertEle = document.getElementById(idAlertEle);
    alertEle.innerHTML = "";
  };
  reader.readAsDataURL(file);
};

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

document
  .querySelector(".add-product-container")
  .addEventListener("submit", (event) => {
    event.preventDefault();
    var formData = new FormData(event.target);

    // Get form values
    const account = formData.get("account");
    const email = formData.get("email");
    const name = formData.get("name");
    const pNumber = formData.get("pNumber");
    const address = formData.get("address"); // Note: there's a typo in the HTML - "addres" instead of "address"
    const status = formData.get("status");

    let isError = false;

    // Get alert elements
    const accountAlertEle = document.getElementById("account-alert");
    const emailAlertEle = document.getElementById("email-alert");
    const nameAlertEle = document.getElementById("name-alert");
    const pNumberAlertEle = document.getElementById("pNumber-alert");
    const addressAlertEle = document.getElementById("address-alert");

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
    if (address === "") {
      addressAlertEle.innerHTML = "Không được để trống địa chỉ!";
      isError = true;
    }

    if (isError === true) {
      return;
    }

    // If form is valid, submit it
    event.target.submit();

    // Close model if needed
    if (typeof cancelModel === "function") {
      cancelModel();
    }
  });

// Helper functions for validation
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function isValidPhoneNumber(phone) {
  const phoneRegex = /^[0-9]{10}$/; // Assuming 10-digit phone number
  return phoneRegex.test(phone);
}
// --------------------------------------------------------------------------------- /

// Delete product
function deleteProduct(productID) {
  window.location.href = `./modules/products/xoa-san-pham.php?IdSP=${productID}`;

  showProductData();
  cancelDeleteProduct();
}

function openConfirmDeleteModel(productID) {
  document
    .getElementsByClassName("model-confirm-delete-container")[0]
    .classList.add("open");
  const confirmDeleteEle = document.getElementById("cofirm-delete-product");

  confirmDeleteEle.addEventListener("click", () => deleteProduct(productID));
}

function cancelDeleteProduct() {
  document
    .getElementsByClassName("model-confirm-delete-container")[0]
    .classList.remove("open");
}

// Filter product
function filterProduct(event) {
  event.preventDefault();

  const filterProductForm = document.querySelector(".search-options-style");
  const formData = new FormData(filterProductForm);
  const name = formData.get("name");
  const brand = formData.get("brand");
  const category = formData.get("category");
  const sort = formData.get("sort");

  const listProduct = getProduct();

  let foundProducts = listProduct.filter((product) => {
    let isTrue = true;

    if (name != "") {
      if (product.name === name) {
        isTrue = true;
      } else isTrue = false;
    }
    return isTrue;
  });

  foundProducts = foundProducts.filter((product) => {
    let isTrue = true;

    if (brand != "") {
      if (product.brand === brand) {
        isTrue = true;
      } else isTrue = false;
    }
    return isTrue;
  });

  foundProducts = foundProducts.filter((product) => {
    let isTrue = true;

    if (category != "all") {
      if (product.category === category) {
        isTrue = true;
      } else isTrue = false;
    }
    return isTrue;
  });

  if (sort != "lastest-product") {
    if (sort === "price-decrease") {
      foundProducts.sort(function (a, b) {
        return b.price - a.price;
      });
    } else {
      foundProducts.sort(function (a, b) {
        return a.price - b.price;
      });
    }
  }

  showProductData(foundProducts);
}
// -------------------Update status-------------------------

const switchEles = document.querySelectorAll(
  ".table-product-container .switch input"
);

switchEles.forEach((switchEle) =>
  switchEle.addEventListener("click", async (e) => {
    const isChecked = e.target.checked;
    const IdKH = e.target.dataset.customerid;
    const status = isChecked ? 1 : 2; // checked thì là 1 (hoạt động) else thì là 2 (Khóa)
    const formData = new FormData();
    formData.set("IdKH", IdKH);
    formData.set("status", status);
    formData.set("updateStatus", "");
    const res = await fetch("./modules/customers/them-sua.php", {
      method: "POST",
      body: formData,
    });
    if (!res) {
      // error
      alert("Lỗi!");
      return;
    }
    const success = await res.json();

    if (success) {
      if (isChecked) {
        alert(`Đã mở khóa khách hàng ID = ${IdKH}`);
        return;
      } else {
        alert(`Đã khóa khách hàng ID = ${IdKH}`);
        return;
      }
    }
    alert("Lỗi!");
  })
);

document.addEventListener("DOMContentLoaded", () => {
  // Retrieve stored form data from PHP session (if available)
  const formData = getFormData();

  // Chèn dữ liệu thành phố
  populateProvinces();
  populateProvinces("province-input-edit");

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

  if (customerFound) {
    displayCustomerAddress(customerFound);
  }

  // Chèn dữ liệu quận vào form chọn quận khi chọn xong thành phố
  document
    .getElementById("province-input")
    .addEventListener("change", function () {
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
    .getElementById("province-input-edit")
    .addEventListener("change", function () {
      const selectedProvinceCode = this.value;
      if (
        selectedProvinceCode === "" ||
        selectedProvinceCode === null ||
        selectedProvinceCode === undefined
      ) {
        document.getElementById("district-input-edit").required = false;
        document.getElementById("district-input-edit").disabled = true;
        document.getElementById("ward-input-edit").required = false;
        document.getElementById("ward-input-edit").disabled = true;
        document.getElementById("address-edit").required = false;
        document.getElementById("address-edit").disabled = true;
        return;
      }

      populateDistricts(
        selectedProvinceCode,
        "district-input-edit",
        "ward-input-edit"
      );
    });

  document
    .getElementById("district-input")
    .addEventListener("change", function () {
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

  document
    .getElementById("district-input-edit")
    .addEventListener("change", function () {
      const selectedDistrictCode = this.value;
      if (
        selectedDistrictCode === "" ||
        selectedDistrictCode === null ||
        selectedDistrictCode === undefined
      ) {
        document.getElementById("ward-input-edit").required = false;
        document.getElementById("ward-input-edit").disabled = true;
        document.getElementById("address-edit").required = false;
        document.getElementById("address-edit").disabled = true;
        return;
      }
      populateWards(
        selectedDistrictCode,
        "province-input-edit",
        "ward-input-edit",
        "address-edit"
      );
    });
});

// function to display customer address in edit form
function displayCustomerAddress(customer) {
  const provinceSelect = document.getElementById("province-input-edit");
  provinceSelect.value = customer.Provinces;

  // Trigger province change to populate districts
  if (customer.Provinces) {
    populateDistricts(customer.Provinces, "district-input-edit", "ward-input-edit");

    // If there's stored district value, select it and populate wards
    if (customer.District) {
      const districtSelect = document.getElementById("district-input-edit");
      districtSelect.value = customer.District;

      // Trigger district change to populate wards
      if (customer.District) {
        populateWards(customer.District, "province-input-edit", "ward-input-edit", "address-edit");

        // If there's stored ward value, select it
        if (customer.Ward) {
          const wardSelect = document.getElementById("ward-input-edit");
          wardSelect.value = customer.Ward;
        }
      }
    }
  }
}

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
