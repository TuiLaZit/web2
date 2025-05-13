const tableHeader = `
            <tr>
              <th>Mã nhóm</th>
              <th>Tên nhóm</th>
              <th>Công Ty</th>
              <th></th>
            </tr>  
  `;

// Display model add group
document.addEventListener('DOMContentLoaded', function() {
    const addGroupBtn = document.getElementById("add-group");
    const closeAddGroupBtn = document.getElementById("close-add-group");
    const cancelAddGroupBtn = document.getElementById("cancel-add-group");
    const clickHideModelBtn = document.getElementById("click-hide-model");
    const modelAddGroupContainer = document.querySelector(".model-add-group-container");

    // Hiển thị form thêm nhóm
    if (addGroupBtn) {
        addGroupBtn.addEventListener("click", function() {
            modelAddGroupContainer.classList.add("open");
        });
    }

    // Đóng form khi click nút đóng
    if (closeAddGroupBtn) {
        closeAddGroupBtn.addEventListener("click", function() {
            modelAddGroupContainer.classList.remove("open");
        });
    }

    // Đóng form khi click nút hủy
    if (cancelAddGroupBtn) {
        cancelAddGroupBtn.addEventListener("click", function() {
            modelAddGroupContainer.classList.remove("open");
        });
    }

    // Đóng form khi click bên ngoài
    if (clickHideModelBtn) {
        clickHideModelBtn.addEventListener("click", function() {
            modelAddGroupContainer.classList.remove("open");
        });
    }

    // Xử lý form submit
    const addGroupForm = document.querySelector(".add-group-container");
    if (addGroupForm) {
        addGroupForm.addEventListener("submit", function(event) {
            event.preventDefault();
            let isError = false;
            const formData = new FormData(this);

            // Kiểm tra tên nhóm
            const name = formData.get("Name");
            const nameAlert = document.getElementById("name-alert");
            if (!name) {
                nameAlert.innerHTML = "Không được để trống tên nhóm!";
                isError = true;
            } else {
                nameAlert.innerHTML = "";
            }

            // Kiểm tra tên công ty
            const company = formData.get("Company");
            const compAlert = document.getElementById("comp-alert");
            if (!company) {
                compAlert.innerHTML = "Không được để trống tên công ty!";
                isError = true;
            } else {
                compAlert.innerHTML = "";
            }

            // Kiểm tra file ảnh
            const image = document.getElementById("image-group").files[0];
            const imageAlert = document.getElementById("image-alert");
            if (!image) {
                imageAlert.innerHTML = "Vui lòng chọn ảnh!";
                isError = true;
            } else {
                imageAlert.innerHTML = "";
            }

            if (!isError) {
                this.submit();
            }
        });
    }
});

// Preview image
function openFile(input, idPreviewElement, idAlertEle) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const output = document.getElementById(idPreviewElement);
            output.src = e.target.result;
            
            const alertEle = document.getElementById(idAlertEle);
            alertEle.innerHTML = "";
        };
        
        reader.readAsDataURL(file);
    }
}

// Cancel model view group details
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
  params.delete("IdGRP");

  // Update the URL without reloading the page
  url.search = params.toString();

  // Replace the current history state with the new URL (without reloading)
  window.location.href = url.toString();
}

cancelViewDetailsID.addEventListener("click", cancelViewDetails);
// --------------------------------------------------------------------------------- //

// Display model edit group
document.addEventListener('DOMContentLoaded', function() {
    const closeEditGroupBtn = document.getElementById("close-edit-group");
    const cancelEditGroupBtn = document.getElementById("cancel-edit-group");
    const modelEditGroupContainer = document.querySelector(".model-edit-group-container");
    const editGroupBtns = document.getElementsByClassName("edit-group");

    // Đóng form khi click nút đóng
    if (closeEditGroupBtn) {
        closeEditGroupBtn.addEventListener("click", function() {
            modelEditGroupContainer.classList.remove("open");
        });
    }

    // Đóng form khi click nút hủy
    if (cancelEditGroupBtn) {
        cancelEditGroupBtn.addEventListener("click", function() {
            modelEditGroupContainer.classList.remove("open");
        });
    }

    // Xử lý nút sửa
    for (let i = 0; i < editGroupBtns.length; i++) {
        const groupID = editGroupBtns[i].dataset.groupid;
        
        editGroupBtns[i].addEventListener("click", () => {
            console.log('Edit button clicked for group:', groupID);
            
            // Hiển thị form sửa nhóm
            modelEditGroupContainer.classList.add("open");
            
            // Lấy dữ liệu của nhóm được chọn từ bảng
            const row = editGroupBtns[i].closest('tr');
            const cells = row.querySelectorAll('td');
            
            // Điền dữ liệu vào form
            const form = modelEditGroupContainer.querySelector('.edit-group-container');
            
            // Lấy dữ liệu từ các ô trong bảng
            // cells[0] là ID
            // cells[1] là ảnh
            // cells[2] là tên nhóm
            // cells[3] là tên công ty
            // cells[4] là thông tin
            
            // Điền tên nhóm
            form.querySelector('input[name="Name"]').value = cells[2].textContent.trim();
            
            // Điền tên công ty
            form.querySelector('input[name="Company"]').value = cells[3].textContent.trim();
            
            // Điền thông tin
            form.querySelector('textarea[name="Info"]').value = cells[4].textContent.trim();
            
            // Hiển thị ảnh hiện tại
            const imgPreview = document.getElementById('output-image-edit');
            const currentImg = cells[1].querySelector('img');
            if (currentImg) {
                imgPreview.src = currentImg.src;
            }
            
            // Thêm trường ẩn để lưu ID của nhóm đang sửa
            let hiddenInput = form.querySelector('input[name="IdGRP"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'IdGRP';
                form.appendChild(hiddenInput);
            }
            hiddenInput.value = groupID;
        });
    }
});

// Display groups data from localStorage into table
const table = document.querySelector(".table-group-container table");
const viewDetailsID = document.getElementsByClassName("view-details");

// Get ID trash icon
const deleteGroupEles = document.getElementsByClassName("delete-group");

for (let i = 0; i < deleteGroupEles.length; i++) {
  const groupID = deleteGroupEles[i].dataset.groupid;
  deleteGroupEles[i].addEventListener("click", (e) => {
    // Tạm thời không làm gì khi click vào nút xóa
    console.log("Delete button clicked for group:", groupID);
  });
}

// --------------------------------------------------------------------------------- //

// Show group data
function showGroupData() {
    // Reload lại trang với URL hiện tại
    window.location.href = 'index.php?action=groups';
}

// Get groups data from table
function getGroup() {
    const groups = [];
    const table = document.querySelector(".table-group-container table");
    const rows = table.querySelectorAll("tr:not(:first-child)");
    
    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        if (cells.length > 0) {
            const img = cells[1].querySelector('img');
            groups.push({
                IdGRP: cells[0].textContent,
                IMG: img ? img.src.split('/').pop() : '',
                Name: cells[2].textContent,
                Company: cells[3].textContent,
                Info: cells[4].textContent
            });
        }
    });
    
    console.log('Fetched groups:', groups); // Debug log
    return groups;
}

// Filter group
function filterGroup(event) {
  event.preventDefault();

    const filterGroupForm = document.querySelector(".search-options-style");
    const formData = new FormData(filterGroupForm);
    const name = formData.get("name").toLowerCase();
    const brand = formData.get("brand").toLowerCase();
    const listGroup = getGroup();

    let foundGroups = listGroup;

    // Lọc theo tên nếu có
    if (name !== "") {
        foundGroups = foundGroups.filter(group => 
            group.Name.toLowerCase().includes(name)
        );
    }

    // Lọc theo công ty nếu có
    if (brand !== "") {
        foundGroups = foundGroups.filter(group => 
            group.Company.toLowerCase().includes(brand)
        );
    }

    // Hiển thị kết quả
    const table = document.querySelector(".table-group-container table");
    const headerRow = table.querySelector("tr:first-child").cloneNode(true);
    table.innerHTML = "";
    table.appendChild(headerRow);

    if (foundGroups.length > 0) {
        foundGroups.forEach(group => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${group.IdGRP}</td>
                <td><img src="../../../admincp/img/groups/${group.IMG}" width="64" height="64" alt="nhom" /></td>
                <td>${group.Name}</td>
                <td>${group.Company}</td>
                <td>${group.Info}</td>
                <td>
                    <div style="display: flex; justify-content: center">
                        <i class="fa-solid fa-pen-to-square edit-group" data-groupid="${group.IdGRP}" style="margin-right: 10px; cursor: pointer;"></i>
                        <i class="fa-solid fa-trash-can delete-group" data-groupid="${group.IdGRP}" style="cursor: pointer;"></i>
                    </div>
                </td>
            `;
            table.appendChild(row);
        });
  } else {
        const row = document.createElement("tr");
        row.innerHTML = '<td colspan="6" class="no-data">Không tìm thấy nhóm nào</td>';
        table.appendChild(row);
    }
}

// Delete group
function deleteGroup(groupID) {
  window.location.href = `./modules/groups/del.php?IdGRP=${groupID}`;

  showGroupData();
  cancelDeleteGroup();
}

function openConfirmDeleteModel(groupID) {
  document
    .getElementsByClassName("model-confirm-delete-container")[0]
    .classList.add("open");
  const confirmDeleteEle = document.getElementById("cofirm-delete-group");

  confirmDeleteEle.addEventListener("click", () => deleteGroup(groupID));
}

function cancelDeleteGroup() {
  document
    .getElementsByClassName("model-confirm-delete-container")[0]
    .classList.remove("open");
}

// --------------------------------------------
