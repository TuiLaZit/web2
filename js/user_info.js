document.addEventListener('DOMContentLoaded', function () {
            // Elements for main form display
            const currentAddressDisplay = document.getElementById('current-address-display');
            const openAddressModalBtn = document.getElementById('open-address-modal-btn');
            
            // Hidden fields in main form
            const hiddenProvinces = document.getElementById('hidden_provinces');
            const hiddenDistrict = document.getElementById('hidden_district');
            const hiddenWard = document.getElementById('hidden_ward');
            const hiddenAddressLine = document.getElementById('hidden_address_line');

            // Modal elements
            const addressEditModal = document.getElementById('address-edit-modal');
            const closeAddressModalBtn = document.getElementById('close-address-modal-btn');
            const cancelAddressModalBtn = document.getElementById('cancel-address-modal-btn');
            const saveAddressFromModalBtn = document.getElementById('save-address-from-modal-btn');
            const addressModalMessage = document.getElementById('address-modal-message');

            // Modal form fields
            const modalProvinceSelect = document.getElementById('modal_provinces');
            const modalDistrictSelect = document.getElementById('modal_district');
            const modalWardSelect = document.getElementById('modal_ward');
            const modalAddressLineInput = document.getElementById('modal_address_line');

            // Initial data from PHP (already in hidden fields, used for modal population)
            let currentModalProvince = hiddenProvinces.value;
            let currentModalDistrict = hiddenDistrict.value;
            let currentModalWard = hiddenWard.value;
            let currentModalAddressLine = hiddenAddressLine.value;

            function populateOptions(selectElement, options, defaultOptionText, selectedValue = '') {
                selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
                if (options && Array.isArray(options)) {
                    options.forEach(option => {
                        const optionValue = option.FullName || option;
                        const optionText = option.FullName || option;
                        const optionEl = document.createElement('option');
                        optionEl.value = optionValue;
                        optionEl.textContent = optionText;
                        if (optionValue === selectedValue) {
                            optionEl.selected = true;
                        }
                        selectElement.appendChild(optionEl);
                    });
                }
            }

            function initModalProvinces(selectedValue = '') {
                if (typeof vietnameseProvinces !== 'undefined') {
                    populateOptions(modalProvinceSelect, vietnameseProvinces, '-- Chọn Tỉnh/Thành phố --', selectedValue);
                }
            }

            function updateModalDistricts(provinceName, selectedValue = '') {
                const province = vietnameseProvinces.find(p => p.FullName === provinceName);
                const districts = province ? province.District : [];
                populateOptions(modalDistrictSelect, districts, '-- Chọn Quận/Huyện --', selectedValue);
                modalDistrictSelect.disabled = !provinceName || districts.length === 0;
                modalWardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                modalWardSelect.disabled = true;
                modalAddressLineInput.disabled = true;
            }

            function updateModalWards(provinceName, districtName, selectedValue = '') {
                const province = vietnameseProvinces.find(p => p.FullName === provinceName);
                const district = province ? province.District.find(d => d.FullName === districtName) : null;
                const wards = district ? district.Ward : [];
                populateOptions(modalWardSelect, wards, '-- Chọn Phường/Xã --', selectedValue);
                modalWardSelect.disabled = !districtName || wards.length === 0;
                modalAddressLineInput.disabled = modalWardSelect.disabled;
            }

            // Event listeners for modal address dropdowns
            modalProvinceSelect.addEventListener('change', function () {
                updateModalDistricts(this.value);
                modalAddressLineInput.value = ''; // Clear address line
            });
            modalDistrictSelect.addEventListener('change', function () {
                if (modalProvinceSelect.value) {
                    updateModalWards(modalProvinceSelect.value, this.value);
                }
                modalAddressLineInput.value = ''; // Clear address line
            });
            modalWardSelect.addEventListener('change', function () {
                modalAddressLineInput.disabled = !this.value;
                if (!this.value) {
                    modalAddressLineInput.value = '';
                }
            });

            // Open Address Modal
            if (openAddressModalBtn) {
                openAddressModalBtn.addEventListener('click', function() {
                    // Populate modal with current values from hidden fields
                    currentModalProvince = hiddenProvinces.value;
                    currentModalDistrict = hiddenDistrict.value;
                    currentModalWard = hiddenWard.value;
                    currentModalAddressLine = hiddenAddressLine.value;

                    initModalProvinces(currentModalProvince);
                    if (currentModalProvince) {
                        updateModalDistricts(currentModalProvince, currentModalDistrict);
                        if (currentModalDistrict) {
                            updateModalWards(currentModalProvince, currentModalDistrict, currentModalWard);
                            if(currentModalWard) modalAddressLineInput.disabled = false;
                        }
                    }
                    modalAddressLineInput.value = currentModalAddressLine;
                    addressModalMessage.textContent = ''; // Clear previous modal messages
                    addressEditModal.classList.add('active');
                });
            }

            // Close Address Modal
            function closeModal() {
                addressEditModal.classList.remove('active');
            }
            if (closeAddressModalBtn) closeAddressModalBtn.addEventListener('click', closeModal);
            if (cancelAddressModalBtn) cancelAddressModalBtn.addEventListener('click', closeModal);

            // Save Address From Modal
            if (saveAddressFromModalBtn) {
                saveAddressFromModalBtn.addEventListener('click', function() {
                    const newProvince = modalProvinceSelect.value;
                    const newDistrict = modalDistrictSelect.value;
                    const newWard = modalWardSelect.value;
                    const newAddressLine = modalAddressLineInput.value.trim();

                    // Validation for modal fields
                    if ((newProvince || newDistrict || newWard || newAddressLine) && 
                        (!newProvince || !newDistrict || !newWard || !newAddressLine)) {
                        addressModalMessage.textContent = 'Vui lòng điền đầy đủ thông tin địa chỉ hoặc để trống tất cả các trường.';
                        return;
                    }
                    addressModalMessage.textContent = '';

                    // Update hidden fields
                    hiddenProvinces.value = newProvince;
                    hiddenDistrict.value = newDistrict;
                    hiddenWard.value = newWard;
                    hiddenAddressLine.value = newAddressLine;

                    // Update display on main page
                    let displayTextParts = [];
                    if (newAddressLine) displayTextParts.push(newAddressLine);
                    if (newWard) displayTextParts.push(newWard);
                    if (newDistrict) displayTextParts.push(newDistrict);
                    if (newProvince) displayTextParts.push(newProvince);
                    currentAddressDisplay.textContent = displayTextParts.length > 0 ? displayTextParts.join(', ') : 'Chưa có thông tin địa chỉ.';
                    
                    closeModal();
                });
            }
        });