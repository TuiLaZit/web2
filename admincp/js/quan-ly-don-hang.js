function populateProvinces(selectId = "province", defaultSelectedValue = "") {
  const provinceSelect = document.getElementById(selectId);
  if (!provinceSelect) return;

  let defaultOptionHTML = `<option value="">Tất cả Tỉnh/TP</option>`;
  provinceSelect.innerHTML = defaultOptionHTML;

  if (typeof vietnameseProvinces === 'undefined' || !Array.isArray(vietnameseProvinces)) {
    console.error("Dữ liệu vietnameseProvinces không tồn tại hoặc không đúng định dạng.");
    return;
  }

  vietnameseProvinces.forEach((province) => {
    const option = document.createElement("option");
    option.value = province.FullName;
    option.textContent = province.FullName;
    if (province.FullName === defaultSelectedValue) {
      option.selected = true;
    }
    provinceSelect.appendChild(option);
  });
}

function populateDistricts(provinceFullName, selectDistrictId = "district", selectWardId = "ward", defaultSelectedValue = "") {
  const districtSelect = document.getElementById(selectDistrictId);
  const wardSelect = document.getElementById(selectWardId);
  if (!districtSelect) return;

  districtSelect.innerHTML = `<option value="">Tất cả Quận/Huyện</option>`;
  districtSelect.disabled = true;

  if (wardSelect) {
    wardSelect.innerHTML = `<option value="">Tất cả Xã/Phường</option>`;
    wardSelect.disabled = true;
  }

  if (provinceFullName && provinceFullName !== "" && typeof vietnameseProvinces !== 'undefined') {
    const province = vietnameseProvinces.find((p) => p.FullName === provinceFullName);
    if (province && province.District && Array.isArray(province.District)) {
      districtSelect.disabled = false;
      province.District.forEach((district) => {
        const option = document.createElement("option");
        option.value = district.FullName;
        option.textContent = district.FullName;
        if (district.FullName === defaultSelectedValue) {
          option.selected = true;
        }
        districtSelect.appendChild(option);
      });
    }
  }
}

function populateWards(districtFullName, selectProvinceId = "province", selectWardId = "ward", defaultSelectedValue = "") {
  const wardSelect = document.getElementById(selectWardId);
  const provinceSelect = document.getElementById(selectProvinceId);
  if (!wardSelect || !provinceSelect) return;

  wardSelect.innerHTML = `<option value="">Tất cả Xã/Phường</option>`;
  wardSelect.disabled = true;

  const provinceFullName = provinceSelect.value;

  if (provinceFullName && provinceFullName !== "" && districtFullName && districtFullName !== "" && typeof vietnameseProvinces !== 'undefined') {
    const province = vietnameseProvinces.find((p) => p.FullName === provinceFullName);
    if (province && province.District && Array.isArray(province.District)) {
      const district = province.District.find((d) => d.FullName === districtFullName);
      if (district && district.Ward && Array.isArray(district.Ward)) {
        wardSelect.disabled = false;
        district.Ward.forEach((ward) => {
          const option = document.createElement("option");
          option.value = ward.FullName;
          option.textContent = ward.FullName;
          if (ward.FullName === defaultSelectedValue) {
            option.selected = true;
          }
          wardSelect.appendChild(option);
        });
      }
    }
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const provinceDropdown = document.getElementById("province");
  const districtDropdown = document.getElementById("district");
  const wardDropdown = document.getElementById("ward");

  populateProvinces("province", typeof currentSelectedProvince !== 'undefined' ? currentSelectedProvince : "");

  if (typeof currentSelectedProvince !== 'undefined' && currentSelectedProvince) {
    populateDistricts(currentSelectedProvince, "district", "ward", typeof currentSelectedDistrict !== 'undefined' ? currentSelectedDistrict : "");
    
    if (typeof currentSelectedDistrict !== 'undefined' && currentSelectedDistrict) {
      populateWards(currentSelectedDistrict, "province", "ward", typeof currentSelectedWard !== 'undefined' ? currentSelectedWard : "");
    }
  } else {
      populateDistricts("", "district", "ward");
  }

  if (provinceDropdown) {
    provinceDropdown.addEventListener("change", function () {
      const selectedProvinceFullName = this.value;
      populateDistricts(selectedProvinceFullName, "district", "ward"); 
    });
  }

  if (districtDropdown) {
    districtDropdown.addEventListener("change", function () {
      const selectedDistrictFullName = this.value;
      populateWards(selectedDistrictFullName, "province", "ward");
    });
  }

  const toastContainer = document.getElementById('toast-notification-container');
  if (toastContainer) {
      const toasts = toastContainer.querySelectorAll('.toast-notification');
      toasts.forEach(function(toast, index) {
          setTimeout(function() { }, index * 100);
          const autoDismissTimeout = setTimeout(function() { dismissToast(toast); }, 7000);
          const closeButton = toast.querySelector('.btn-close-toast');
          if (closeButton) {
              closeButton.addEventListener('click', function() {
                  clearTimeout(autoDismissTimeout);
                  dismissToast(toast);
              });
          }
      });
  }
});

function dismissToast(toastElement) {
    toastElement.classList.add('fade-out'); 
    toastElement.addEventListener('animationend', function() {
        if (toastElement.parentNode) {
            toastElement.parentNode.removeChild(toastElement); 
        }
        const container = document.getElementById('toast-notification-container');
        if (container && container.children.length === 0) {
        }
    }, { once: true }); 
}

function confirmOrderStatusUpdate(selectElement, orderId, newStatusText) {
  const originalStatusValue = selectElement.dataset.currentStatus;
  const message = `Bạn có chắc chắn muốn cập nhật trạng thái của đơn hàng #${orderId} thành "${newStatusText}" không?`;

  if (window.confirm(message)) {
    if (selectElement.form) {
      selectElement.form.submit();
    } else {
      console.error("Không tìm thấy form cha của select element.");
    }
  } else {
    if (originalStatusValue !== undefined) {
      selectElement.value = originalStatusValue;
    }
  }
}

// Thông báo xác nhận
let confirmationDialogElement = null;
let confirmationOverlayElement = null;
let currentActiveSelectElement = null; // Select element đang được xử lý
let currentOriginalStatusValue = null; // Trạng thái gốc của select element đó

// Tạo hoặc lấy tham chiếu đến modal xác nhận và lớp phủ của nó.
function getOrCreateConfirmationDialogElements() {
    if (!confirmationOverlayElement) {
        confirmationOverlayElement = document.createElement('div');
        confirmationOverlayElement.id = 'confirm-overlay';
        confirmationOverlayElement.className = 'confirm-dialog-overlay';
        confirmationOverlayElement.style.display = 'none';
        document.body.appendChild(confirmationOverlayElement);

        confirmationOverlayElement.addEventListener('click', () => { // Click ra ngoài để hủy
            cancelConfirmationAction();
        });
    }

    if (!confirmationDialogElement) {
        confirmationDialogElement = document.createElement('div');
        confirmationDialogElement.id = 'centered-confirmation-dialog';
        confirmationDialogElement.className = 'centered-confirm-dialog';
        confirmationDialogElement.style.display = 'none';

        confirmationDialogElement.innerHTML = `
            <div class="confirm-dialog-header">
                <h5 class="confirm-dialog-title">Xác nhận hành động</h5>
                <button type="button" class="btn-close-dialog" aria-label="Close">&times;</button>
            </div>
            <div class="confirm-dialog-body">
                <p class="confirm-message"></p>
            </div>
            <div class="confirm-dialog-footer">
                <button class="btn btn-secondary btn-sm confirm-no">Hủy</button> 
                <button class="btn btn-primary btn-sm confirm-yes">Đồng ý</button>
            </div>
        `;
        document.body.appendChild(confirmationDialogElement);

        confirmationDialogElement.querySelector('.confirm-yes').addEventListener('click', () => {
            if (currentActiveSelectElement && currentActiveSelectElement.form) {
                currentActiveSelectElement.form.submit();
            }
            hideConfirmationDialog();
        });
        
        const closeButtons = [
            confirmationDialogElement.querySelector('.confirm-no'),
            confirmationDialogElement.querySelector('.btn-close-dialog')
        ];
        closeButtons.forEach(btn => btn.addEventListener('click', () => cancelConfirmationAction()));
    }
}

function cancelConfirmationAction() {
    if (currentActiveSelectElement && currentOriginalStatusValue !== undefined) {
        currentActiveSelectElement.value = currentOriginalStatusValue;
    }
    hideConfirmationDialog();
}

function hideConfirmationDialog() {
    if (confirmationDialogElement) confirmationDialogElement.style.display = 'none';
    if (confirmationOverlayElement) confirmationOverlayElement.style.display = 'none';
}

/**
 * Hiển thị modal xác nhận tùy chỉnh.
 * @param {HTMLElement} selectElement Phần tử <select> đã thay đổi.
 * @param {string} orderId Mã đơn hàng.
 * @param {string} newStatusText Text của trạng thái mới được chọn.
 */
function confirmOrderStatusUpdate(selectElement, orderId, newStatusText) {
    getOrCreateConfirmationDialogElements(); // Đảm bảo dialog và overlay đã được tạo

    currentActiveSelectElement = selectElement;
    currentOriginalStatusValue = selectElement.dataset.currentStatus;

    const messageElement = confirmationDialogElement.querySelector('.confirm-message');
    messageElement.textContent = `Bạn có chắc chắn muốn cập nhật trạng thái của đơn hàng #${orderId} thành "${newStatusText}" không?`;
    
    if (confirmationOverlayElement) confirmationOverlayElement.style.display = 'block';
    if (confirmationDialogElement) confirmationDialogElement.style.display = 'block';
}