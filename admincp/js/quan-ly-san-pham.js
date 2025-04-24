const tableHeader = `
            <tr>
              <th>Mã sản phẩm</th>
              <th>Tên sản phẩm</th>
              <th>Danh mục</th>
              <th>Hãng</th>
              <th>Đơn giá</th>
              <th>Kho hàng</th>
              <th></th>
            </tr>  
  `;

// Display model add product
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
  params.delete("IdSP");

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

// Get ID eye icon
for (let i = 0; i < viewDetailsID.length; i++) {
  const productID = viewDetailsID[i].dataset.productid;

  // Get the current URL
  const url = new URL(window.location.href);

  // Access the query parameters using URLSearchParams
  const params = new URLSearchParams(url.search);

  // Get a specific query parameter, e.g., 'id'
  const id = params.get("IdSP");

  viewDetailsID[i].addEventListener("click", (e) => {
    let url = new URL(window.location);
    url.searchParams.set("IdSP", productID);
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

    const editProductForm = document.querySelector("#edit-product-form");
    editProductForm.addEventListener("submit", (e) => {
      editProduct(e, productID);
    });
  }
}

// Edit product
function editProduct(event, productID) {
  event.preventDefault();

  const editProductForm = document.querySelector("#edit-product-form");
  const formData = new FormData(editProductForm);
  const name = formData.get("name");
  const category = formData.get("category");
  const brand = formData.get("brand");
  const price = formData.get("price");
  const quantity = formData.get("quantity");
  const description = formData.get("description");
  const image = document.getElementById("output-image-edit").src;
  const imageAlertEle = document.getElementById("image-alert-edit");

  let isError = false;

  if (name === "") {
    const nameAlertEle = document.getElementById("name-alert-edit");
    nameAlertEle.innerHTML = "Không được để trống tên sản phẩm !";
    isError = true;
  }

  if (brand === "") {
    const brandAlertEle = document.getElementById("brand-alert-edit");
    brandAlertEle.innerHTML = "Không được để trống hãng sản phẩm !";
    isError = true;
  }

  if (price === "") {
    const priceAlertEle = document.getElementById("price-alert-edit");
    priceAlertEle.innerHTML = "Không được để trống giá sản phẩm !";
    isError = true;
  }

  if (quantity === "") {
    const quantityAlertEle = document.getElementById("quantity-alert-edit");
    quantityAlertEle.innerHTML = "Không được để trống số lượng sản phẩm !";
    isError = true;
  }

  if (image === "") {
    imageAlertEle.innerHTML = "Hình ảnh sản phẩm không hợp lệ !";
    isError = true;
  }

  let validExtensions = /\.(jpg|jpeg|png|gif|bmp|webp)$/i;

  if (!image.includes("data:image") && !validExtensions.test(image)) {
    imageAlertEle.innerHTML = "Hình ảnh sản phẩm không hợp lệ!";
    isError = true;
  }

  if (isError === true) {
    return;
  }

  cancelEditProductModel();
  editProductForm.submit();
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

addProductForm.addEventListener("submit", (event) => {
  event.preventDefault();
  var formData = new FormData(addProductForm);
  const group = formData.get("group");
  const name = formData.get("name");
  const type = formData.get("type");
  console.log(type);
  const price = formData.get("price");
  // const quantity = formData.get("quantity");
  const releaseDate = formData.get("releaseDate");
  const info = formData.get("info");
  let image = document.getElementById("output-image").src;

  let isError = false;

  const nameAlertEle = document.getElementById("name-alert");
  const typeAlertEle = document.getElementById("type-alert");
  const priceAlertEle = document.getElementById("price-alert");
  // const quantityAlertEle = document.getElementById("quantity-alert");
  const imageAlertEle = document.getElementById("image-alert");

  if (name === "") {
    nameAlertEle.innerHTML = "Không được để trống tên sản phẩm !";
    isError = true;
  }

  if (type === "") {
    typeAlertEle.innerHTML = "Không được để trống type sản phẩm !";
    isError = true;
  }

  if (price === "") {
    priceAlertEle.innerHTML = "Không được để trống giá sản phẩm !";
    isError = true;
  }

  if (!isValidNumber(price) && price !== "") {
    priceAlertEle.innerHTML = "Giá sản phẩm không hợp lệ !";
    isError = true;
  }

  // if (quantity === "") {
  //   quantityAlertEle.innerHTML = "Không được để trống số lượng sản phẩm !";
  //   isError = true;
  // }

  // if (!isValidNumber(quantity) && quantity !== "") {
  //   quantityAlertEle.innerHTML = "Số lượng sản phẩm không hợp lệ !";
  //   isError = true;
  // }

  if (image === "") {
    imageAlertEle.innerHTML = "Hình ảnh sản phẩm không hợp lệ !";
    isError = true;
  }

  if (!image.includes("data:image")) {
    imageAlertEle.innerHTML = "Hình ảnh sản phẩm không hợp lệ!";
    isError = true;
  }

  if (isError === true) {
    return;
  }
  cancelModel();

  addProductForm.submit();

  const listInputEle = addProductForm.querySelectorAll("input");
  listInputEle.forEach((inputEle) => {
    inputEle.value = "";
  });
  addProductForm.querySelector("textarea").value = "";
});
// --------------------------------------------------------------------------------- //

// Get products data from localStorage
function getProduct() {}
// --------------------------------------------------------------------------------- //

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
// --------------------------------------------
