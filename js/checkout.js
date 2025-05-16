// Lấy giá trị addressOptionsCount từ đối tượng config được truyền từ PHP
const addressOptionsCount = window.checkoutPageConfig ? window.checkoutPageConfig.addressOptionsCount : 0; // Default to 0 if undefined

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

const confirmPaymentButtonId = 'confirm-payment-button';
const paymentStatusMessageId = 'payment-status-message';
const checkoutFormId = 'checkoutForm'; // ID for the main checkout form

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

// State variable for online payment confirmation
let onlinePaymentAcknowledged = false;

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
        populateDistricts(''); // Reset and disable districts
        populateWards('');   // Reset and disable wards
        const addressDetailEl = getElement(addressDetailInputId);
        if (addressDetailEl) {
            addressDetailEl.value = '';
            addressDetailEl.disabled = true; // Should be enabled only when ward is selected
        }
    }
    updateCheckoutAddress(); // Ensure hidden input is updated even if no specific new/saved path is taken
}

// Hàm chung để điền các lựa chọn (options) vào một thẻ select
function populateOptions(selectElement, options, defaultText) {
    if (!selectElement) return;
    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
    if (options && Array.isArray(options)) {
        options.forEach(option => {
            // Assuming 'option' objects have a 'Name' property based on previous PHP context for provinces data.
            // If your 'vietnameseProvincesData' structure uses 'FullName', adjust accordingly.
            // For this example, I'll assume 'Name' or 'FullName' might be used.
            const optionValue = option && (option.FullName || option.Name) ? (option.FullName || option.Name) : '';
            const optionText = option && (option.FullName || option.Name) ? (option.FullName || option.Name) : '';
            if(optionValue) { // Only add if value is not empty
                 selectElement.innerHTML += `<option value="${optionValue}">${optionText}</option>`;
            }
        });
    }
}

// Hàm điền danh sách Tỉnh/Thành phố vào dropdown
function populateProvinces() {
    if (typeof vietnameseProvincesData !== 'undefined' && vietnameseProvincesData.provinces) { // Assuming global 'vietnameseProvincesData'
        populateOptions(getElement(provinceInputId), vietnameseProvincesData.provinces, 'Tỉnh / Thành phố (*)');
    } else if (typeof vietnameseProvinces !== 'undefined') { // Fallback for 'vietnameseProvinces' directly
         populateOptions(getElement(provinceInputId), vietnameseProvinces, 'Tỉnh / Thành phố (*)');
    }
}

// Hàm điền danh sách Quận/Huyện dựa trên Tỉnh/Thành phố đã chọn
function populateDistricts(provinceName) {
    const districtSelect = getElement(districtInputId);
    const wardSelect = getElement(wardInputId);
    const addressDetailInput = getElement(addressDetailInputId);

    if (!districtSelect || !wardSelect || !addressDetailInput) return;

    let districts = [];
    const dataSource = (typeof vietnameseProvincesData !== 'undefined' && vietnameseProvincesData.provinces) ? vietnameseProvincesData.provinces : (typeof vietnameseProvinces !== 'undefined' ? vietnameseProvinces : []);

    if (provinceName && dataSource.length > 0) {
        const province = dataSource.find(p => (p.FullName === provinceName || p.Name === provinceName));
        districts = province?.District || province?.Districts || []; // Accommodate 'District' or 'Districts'
    }
    populateOptions(districtSelect, districts, 'Quận / Huyện (*)');
    populateOptions(wardSelect, [], 'Xã / Phường / Thị trấn (*)'); // Clear wards

    districtSelect.disabled = !provinceName || districts.length === 0;
    wardSelect.disabled = true;
    addressDetailInput.disabled = true;
    updateCheckoutAddress();
}

// Hàm điền danh sách Xã/Phường dựa trên Quận/Huyện đã chọn
function populateWards(districtName) {
    const wardSelect = getElement(wardInputId);
    const addressDetailInput = getElement(addressDetailInputId);
    const provinceSelect = getElement(provinceInputId);
    if (!wardSelect || !addressDetailInput || !provinceSelect) return;

    const provinceName = provinceSelect.value;
    let wards = [];
    const dataSource = (typeof vietnameseProvincesData !== 'undefined' && vietnameseProvincesData.provinces) ? vietnameseProvincesData.provinces : (typeof vietnameseProvinces !== 'undefined' ? vietnameseProvinces : []);


    if (districtName && provinceName && dataSource.length > 0) {
        const province = dataSource.find(p => (p.FullName === provinceName || p.Name === provinceName));
        const district = province?. (province.District || province.Districts).find(d => (d.FullName === districtName || d.Name === districtName));
        wards = district?.Ward || district?.Wards || []; // Accommodate 'Ward' or 'Wards'
    }
    populateOptions(wardSelect, wards, 'Xã / Phường / Thị trấn (*)');

    wardSelect.disabled = !districtName || wards.length === 0;
    addressDetailInput.disabled = true; // Keep address detail disabled until ward is selected
    updateCheckoutAddress();
}

// Hàm cập nhật giá trị của trường input ẩn 'checkout_address_hidden_input' với địa chỉ đầy đủ
function updateCheckoutAddress() {
    const provinceEl = getElement(provinceInputId);
    const districtEl = getElement(districtInputId);
    const wardEl = getElement(wardInputId);
    const addressDetailEl = getElement(addressDetailInputId);
    const checkoutAddressInputEl = getElement(checkoutAddressHiddenInputId);
    const addressOptionEl = getElement('address_option');


    if (!checkoutAddressInputEl || !addressOptionEl) return;

    const selectedAddressOptionValue = addressOptionEl.value;

    if (selectedAddressOptionValue && selectedAddressOptionValue.startsWith('saved_')) {
        const index = selectedAddressOptionValue.split('_')[1];
        const savedAddressDivId = savedAddressPrefix + index;
        const inputElement = getElement(savedAddressDivId)?.querySelector('input');
        if (inputElement) {
            checkoutAddressInputEl.value = inputElement.value;
        } else {
            checkoutAddressInputEl.value = '';
        }
    } else if (selectedAddressOptionValue === 'new') {
        const province = provinceEl ? provinceEl.value : '';
        const district = districtEl ? districtEl.value : '';
        const ward = wardEl ? wardEl.value : '';
        const address = addressDetailEl ? addressDetailEl.value.trim() : '';

        if (province && district && ward && address !== '') {
            checkoutAddressInputEl.value = `${address}, ${ward}, ${district}, ${province}`;
        } else {
            checkoutAddressInputEl.value = '';
        }
    } else {
         checkoutAddressInputEl.value = ''; // Clear if no valid option
    }
}


// Hàm thực thi khi toàn bộ DOM đã được tải
document.addEventListener('DOMContentLoaded', () => {
    populateProvinces();

    const mainConfirmOrderButton = document.querySelector('button[name="confirm_order"]');
    const paymentStatusMessageEl = getElement(paymentStatusMessageId);
    const confirmPaymentButtonEl = getElement(confirmPaymentButtonId);

    function setMainOrderButtonState() {
        if (!mainConfirmOrderButton) return;
        const selectedPaymentRadio = document.querySelector('input[name="checkout_payment"]:checked');
        if (!selectedPaymentRadio) { // No payment method selected
            mainConfirmOrderButton.disabled = true;
            return;
        }
        const selectedPaymentMethod = selectedPaymentRadio.value;

        if (selectedPaymentMethod === paymentOnlineValue) {
            if (onlinePaymentAcknowledged) {
                mainConfirmOrderButton.disabled = false;
            } else {
                mainConfirmOrderButton.disabled = true;
                if (paymentStatusMessageEl && getElement(bankInfoId).style.display === 'block') {
                     paymentStatusMessageEl.textContent = 'Vui lòng xác nhận đã chuyển khoản bằng cách nhấn "Đã thanh toán".';
                     paymentStatusMessageEl.style.color = 'orange'; // Or some other prompt color
                }
            }
        } else { // COD or other non-online methods
            mainConfirmOrderButton.disabled = false;
            if (paymentStatusMessageEl) paymentStatusMessageEl.textContent = ''; // Clear any pending messages
        }
    }


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
                 if(this.value) addressDetailInputEl.focus(); // Focus on address line when ward is selected
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
        toggleNewAddress(addressOptionEl.value); // Initial call
        addressOptionEl.addEventListener('change', function() {
            toggleNewAddress(this.value);
        });
    }

    const paymentRadios = document.querySelectorAll('input[name="checkout_payment"]');
    const bankInfoEl = getElement(bankInfoId);

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            onlinePaymentAcknowledged = false; // Reset on any payment method change
            const isOnline = this.value === paymentOnlineValue;
            if (bankInfoEl) bankInfoEl.style.display = isOnline ? 'block' : 'none';

            if (isOnline) {
                if (confirmPaymentButtonEl) {
                    confirmPaymentButtonEl.style.display = 'block'; // Or 'inline-block' or ''
                    confirmPaymentButtonEl.disabled = false;
                    confirmPaymentButtonEl.textContent = 'Đã thanh toán';
                }
                if (paymentStatusMessageEl) {
                    paymentStatusMessageEl.textContent = 'Vui lòng xác nhận đã chuyển khoản bằng cách nhấn "Đã thanh toán".';
                    paymentStatusMessageEl.style.color = 'orange';
                }
            } else { // COD or other
                if (paymentStatusMessageEl) {
                    paymentStatusMessageEl.textContent = '';
                }
            }
            setMainOrderButtonState();
        });
    });

    // Initial state for payment section and main order button
    const checkedPayment = document.querySelector('input[name="checkout_payment"]:checked');
    if (bankInfoEl && checkedPayment) {
        const isOnlineInitial = checkedPayment.value === paymentOnlineValue;
        bankInfoEl.style.display = isOnlineInitial ? 'block' : 'none';
        if (isOnlineInitial) {
             if (paymentStatusMessageEl) {
                 paymentStatusMessageEl.textContent = 'Vui lòng xác nhận đã chuyển khoản bằng cách nhấn "Đã thanh toán".';
                 paymentStatusMessageEl.style.color = 'orange';
             }
        }
    }
    setMainOrderButtonState(); // Set initial button state

    if (confirmPaymentButtonEl && paymentStatusMessageEl) {
        confirmPaymentButtonEl.addEventListener('click', function() {
            this.disabled = true;
            this.textContent = 'Đang xử lý...';
            paymentStatusMessageEl.textContent = '';

            setTimeout(() => {
                paymentStatusMessageEl.textContent = 'Xác nhận thanh toán thành công! Bạn có thể tiếp tục đặt hàng.';
                paymentStatusMessageEl.style.color = 'green';
                this.style.display = 'none'; // Hide "Đã thanh toán" button after success

                onlinePaymentAcknowledged = true;
                setMainOrderButtonState(); // Enable main order button
            }, 1500); // Reduced timeout for quicker feedback
        });
    }

    // Form submission listener
    const checkoutFormEl = getElement(checkoutFormId);
    if (checkoutFormEl) {
        checkoutFormEl.addEventListener('submit', function(event) {
            // 1. Address Validation
            updateCheckoutAddress(); // Ensure hidden input is current
            const currentCheckoutAddress = getElement(checkoutAddressHiddenInputId)?.value;
            const selectedAddressOption = getElement('address_option')?.value;

            let isAddressValid = true;
            if (!selectedAddressOption) {
                isAddressValid = false;
                alert('Lỗi: Không tìm thấy lựa chọn địa chỉ.');
            } else if (selectedAddressOption === 'new' && (!currentCheckoutAddress || currentCheckoutAddress.split(',').length < 4)) {
                // Basic check for new address; more specific field checks can be added here
                isAddressValid = false;
                alert('Vui lòng điền đầy đủ thông tin địa chỉ giao hàng mới (Số nhà, Phường/Xã, Quận/Huyện, Tỉnh/Thành phố).');
                // Highlight individual empty fields if desired
                if(!getElement(provinceInputId)?.value) getElement(provinceInputId)?.focus();
                else if(!getElement(districtInputId)?.value) getElement(districtInputId)?.focus();
                else if(!getElement(wardInputId)?.value) getElement(wardInputId)?.focus();
                else if(!getElement(addressDetailInputId)?.value.trim()) getElement(addressDetailInputId)?.focus();

            } else if (selectedAddressOption.startsWith('saved_') && !currentCheckoutAddress) {
                isAddressValid = false;
                alert('Địa chỉ đã lưu không hợp lệ. Vui lòng chọn lại hoặc nhập địa chỉ mới.');
            }


            if (!isAddressValid) {
                event.preventDefault();
                return;
            }

            // 2. Payment Validation
            const selectedPaymentRadio = document.querySelector('input[name="checkout_payment"]:checked');
            if (!selectedPaymentRadio) {
                event.preventDefault();
                alert('Vui lòng chọn phương thức thanh toán.');
                return;
            }
            const selectedPaymentMethod = selectedPaymentRadio.value;

            if (selectedPaymentMethod === paymentOnlineValue && !onlinePaymentAcknowledged) {
                event.preventDefault();
                alert('Thanh toán trực tuyến yêu cầu bạn xác nhận đã chuyển khoản. Vui lòng nhấn nút "Đã thanh toán" trong mục thông tin chuyển khoản.');
                if (bankInfoEl) bankInfoEl.style.display = 'block';
                if (paymentStatusMessageEl) {
                    paymentStatusMessageEl.textContent = 'Bạn cần xác nhận đã thanh toán!';
                    paymentStatusMessageEl.style.color = 'red';
                }
                confirmPaymentButtonEl?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});
