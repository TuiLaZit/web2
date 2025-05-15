// Lấy giá trị addressOptionsCount từ đối tượng config được truyền từ PHP
const addressOptionsCount = window.checkoutPageConfig.addressOptionsCount;

const savedAddressPrefix = 'saved_address_';
const newAddressContainerId = 'new_address';
const checkoutAddressHiddenInputId = 'checkout_address_hidden_input';
const provinceInputId = 'province-input';
const districtInputId = 'district-input';
const wardInputId = 'ward-input';
const addressDetailInputId = 'address-input-field';
const bankInfoId = 'bank-info';
const paymentCodValue = 'cod';
const paymentOnlineValue = 'online';

// THÊM MỚI: ID cho nút xác nhận thanh toán và vùng hiển thị thông báo
const confirmPaymentButtonId = 'confirm-payment-button';
const paymentStatusMessageId = 'payment-status-message';


// Hàm tiện ích lấy phần tử DOM bằng ID
const getElement = (id) => document.getElementById(id);
// Hàm tiện ích ẩn phần tử DOM
const hideElement = (id) => {
    const el = getElement(id);
    if (el) el.style.display = 'none';
};
// Hàm tiện ích hiển thị phần tử DOM (dạng block)
const showElement = (id) => {
    const el = getElement(id);
    if (el) el.style.display = 'block';
};

// Hàm xử lý việc hiển thị/ẩn form nhập địa chỉ mới hoặc ô hiển thị địa chỉ đã lưu
function toggleNewAddress(selectedValue) {
    for (let i = 0; i < addressOptionsCount; i++) {
        hideElement(savedAddressPrefix + i);
    }
    hideElement(newAddressContainerId);
    const checkoutAddrEl = getElement(checkoutAddressHiddenInputId);
    if(checkoutAddrEl) checkoutAddrEl.value = '';

    if (selectedValue && selectedValue.startsWith('saved_')) {
        const index = selectedValue.split('_')[1];
        const savedAddressDivId = savedAddressPrefix + index;
        showElement(savedAddressDivId);
        const inputElement = getElement(savedAddressDivId)?.querySelector('input');
        if (inputElement && checkoutAddrEl) {
            checkoutAddrEl.value = inputElement.value;
        }
    } else if (selectedValue === 'new') {
        showElement(newAddressContainerId);
        const provinceEl = getElement(provinceInputId);
        if (provinceEl) provinceEl.value = '';
        populateDistricts('');
        populateWards('');  
        const addressDetailEl = getElement(addressDetailInputId);
        if (addressDetailEl) {
            addressDetailEl.value = '';
            addressDetailEl.disabled = true;
        }
    }
}

// Hàm chung để điền các lựa chọn (options) vào một thẻ select
function populateOptions(selectElement, options, defaultText) {
    if (!selectElement) return;
    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
    if (options && Array.isArray(options)) {
        options.forEach(option => {
            const optionValue = option && option.FullName ? option.FullName : '';
            selectElement.innerHTML += `<option value="${optionValue}">${optionValue}</option>`;
        });
    }
}

// Hàm điền danh sách Tỉnh/Thành phố vào dropdown
function populateProvinces() {
    if (typeof vietnameseProvinces !== 'undefined') {
        populateOptions(getElement(provinceInputId), vietnameseProvinces, 'Tỉnh / Thành phố (*)');
    }
}

// Hàm điền danh sách Quận/Huyện dựa trên Tỉnh/Thành phố đã chọn
function populateDistricts(provinceFullName) {
    const districtSelect = getElement(districtInputId);
    const wardSelect = getElement(wardInputId);
    const addressDetailInput = getElement(addressDetailInputId);

    if (!districtSelect || !wardSelect || !addressDetailInput) return;

    let districts = [];
    if (provinceFullName && typeof vietnameseProvinces !== 'undefined') {
        const province = vietnameseProvinces.find(p => p.FullName === provinceFullName);
        districts = province?.District || [];
    }
    populateOptions(districtSelect, districts, 'Quận / Huyện (*)');
    populateOptions(wardSelect, [], 'Xã / Phường / Thị trấn (*)');

    districtSelect.disabled = !provinceFullName || districts.length === 0;
    wardSelect.disabled = true;
    addressDetailInput.disabled = true;
    updateCheckoutAddress();
}

// Hàm điền danh sách Xã/Phường dựa trên Quận/Huyện đã chọn
function populateWards(districtFullName) {
    const wardSelect = getElement(wardInputId);
    const addressDetailInput = getElement(addressDetailInputId);
    const provinceSelect = getElement(provinceInputId);
    if (!wardSelect || !addressDetailInput || !provinceSelect) return;

    const provinceFullName = provinceSelect.value;
    let wards = [];
    if (districtFullName && provinceFullName && typeof vietnameseProvinces !== 'undefined') {
        const province = vietnameseProvinces.find(p => p.FullName === provinceFullName);
        const district = province?.District.find(d => d.FullName === districtFullName);
        wards = district?.Ward || [];
    }
    populateOptions(wardSelect, wards, 'Xã / Phường / Thị trấn (*)');

    wardSelect.disabled = !districtFullName || wards.length === 0;
    addressDetailInput.disabled = true;
    updateCheckoutAddress();
}

// Hàm cập nhật giá trị của trường input ẩn 'checkout_address_hidden_input' với địa chỉ đầy đủ
function updateCheckoutAddress() {
    const provinceEl = getElement(provinceInputId);
    const districtEl = getElement(districtInputId);
    const wardEl = getElement(wardInputId);
    const addressDetailEl = getElement(addressDetailInputId);
    const checkoutAddressInputEl = getElement(checkoutAddressHiddenInputId);

    if (!checkoutAddressInputEl) return;

    const province = provinceEl ? provinceEl.value : '';
    const district = districtEl ? districtEl.value : '';
    const ward = wardEl ? wardEl.value : '';
    const address = addressDetailEl ? addressDetailEl.value : '';

    if (province && district && ward && address.trim() !== '') {
        checkoutAddressInputEl.value = `${address.trim()}, ${ward}, ${district}, ${province}`;
    } else {
        checkoutAddressInputEl.value = '';
    }
}

// Hàm thực thi khi toàn bộ DOM đã được tải
document.addEventListener('DOMContentLoaded', () => {
    populateProvinces();

    const provinceEl = getElement(provinceInputId);
    if (provinceEl) {
        provinceEl.addEventListener('change', function() {
            populateDistricts(this.value);
        });
    }

    const districtEl = getElement(districtInputId);
    if (districtEl) {
        districtEl.addEventListener('change', function() {
            populateWards(this.value);
        });
    }

    const wardEl = getElement(wardInputId);
    if (wardEl) {
        wardEl.addEventListener('change', function() {
            const addressDetailInputEl = getElement(addressDetailInputId);
            if (addressDetailInputEl) {
                addressDetailInputEl.disabled = !this.value;
            }
            updateCheckoutAddress();
        });
    }

    const addressDetailEl = getElement(addressDetailInputId);
    if (addressDetailEl) {
        addressDetailEl.addEventListener('input', updateCheckoutAddress);
    }

    const addressOptionEl = getElement('address_option');
    if (addressOptionEl) {
        toggleNewAddress(addressOptionEl.value);
         // Thêm listener để gọi toggleNewAddress khi lựa chọn thay đổi
        addressOptionEl.addEventListener('change', function() {
            toggleNewAddress(this.value);
        });
    }

    const paymentRadios = document.querySelectorAll('input[name="checkout_payment"]');
    const bankInfoEl = getElement(bankInfoId);

    if (bankInfoEl) {
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                bankInfoEl.style.display = this.value === paymentOnlineValue ? 'block' : 'none';
                 // Reset trạng thái nút và thông báo khi thay đổi phương thức thanh toán
                const confirmBtn = getElement(confirmPaymentButtonId);
                const statusMsg = getElement(paymentStatusMessageId);
                if (this.value !== paymentOnlineValue) {
                    if (confirmBtn) {
                        confirmBtn.style.display = 'block'; // Hoặc 'inline-block' tùy theo CSS của bạn
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = 'Đã thanh toán';
                    }
                    if (statusMsg) {
                        statusMsg.textContent = '';
                    }
                }
            });
        });
        const checkedPayment = document.querySelector('input[name="checkout_payment"]:checked');
        if (checkedPayment) {
            bankInfoEl.style.display = checkedPayment.value === paymentOnlineValue ? 'block' : 'none';
        }
    }

    // THÊM MỚI: Xử lý cho nút "Đã thanh toán"
    const confirmPaymentButtonEl = getElement(confirmPaymentButtonId);
    const paymentStatusMessageEl = getElement(paymentStatusMessageId);

    if (confirmPaymentButtonEl && paymentStatusMessageEl) {
        confirmPaymentButtonEl.addEventListener('click', function() {
            // Vô hiệu hóa nút và hiển thị trạng thái đang xử lý
            this.disabled = true;
            this.textContent = 'Đang xử lý...';
            paymentStatusMessageEl.textContent = ''; // Xóa thông báo cũ

            // Giả lập thời gian chờ xử lý
            setTimeout(() => {
                // Hiển thị thông báo thành công
                paymentStatusMessageEl.textContent = 'Xác nhận thanh toán thành công!';
                paymentStatusMessageEl.style.color = 'green';
                
                // Ẩn nút "Đã thanh toán" sau khi xác nhận
                this.style.display = 'none'; 

            }, 2500); // Thời gian chờ 2.5 giây
        });
    }
});
