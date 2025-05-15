<?php
$sql_get_customers = "Select * from khachhang";
$query_get_customers = mysqli_query($mysqli, $sql_get_customers);

// list of customers from database
$customers = [];

// Nếu có dữ liệu
if ($query_get_customers && mysqli_num_rows($query_get_customers) > 0) {
    while ($row = mysqli_fetch_array($query_get_customers)) {
        $customers[] = $row;
    }
}



class CustomerStatus
{
    public const HOAT_DONG = 1;
    public const KHOA = 2;
}

$listStatus =  [
    ['id' => CustomerStatus::HOAT_DONG, 'name' => 'Hoạt động'],
    ['id' => CustomerStatus::KHOA, 'name' => 'Khóa'],
];


// Get errors from session if they exist
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

?>

<script>
    function getFormData() {
        return typeof phpFormData !== 'undefined' ? phpFormData : null;
    }

    let phpFormData = null;

    // Pass PHP session data to JavaScript
    <?php if (isset($_SESSION['form_data'])): ?>
        phpFormData = <?php echo json_encode($_SESSION['form_data']); ?>;
    <?php endif; ?>

    <?php
    // Clear session data after retrieving it
    if (isset($_SESSION['form_errors'])) {
        unset($_SESSION['form_errors']);
    }
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>

    <?php
    if (isset($_GET['IdKH'])) {
        $idKH = $_GET['IdKH'];

        // Tìm khách hàng theo IdKH trong mảng $customers
        $customerFound = null;
        foreach ($customers as $customer) {
            if ($customer['IdKH'] == $idKH) {
                $customerFound = $customer;
                break;
            }
        }
    }
    ?>

    <?php if (isset($customerFound)): ?>
        var customerFound = <?php echo json_encode($customerFound); ?>;
    <?php else: ?>
        var customerFound = null;
    <?php endif; ?>
</script>



<style>
    body {
        overflow: hidden;
    }

    .main-content {
        padding: 0px;
    }
</style>

<link rel="stylesheet" type="text/css" href="./css/quan-ly-khach-hang.css?v=<?php echo time(); ?>">


<div style="position: relative; width: 100%; height: calc(100vh - 54px);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">

        <!-- Body page -->
        <div
            class="bg-color"
            style="height: 100vh; width: 100%; overflow-y: auto; position: relative;">
            <!-- Title page -->
            <div style="display: flex">
                <p class="title-page">Khách hàng</p>

                <div style="margin-left: auto; padding: 20px">
                    <button class="button-style" id="add-product">
                        <i class="fa-solid fa-plus" style="font-size: 15px"></i>
                        <div style="padding-left: 10px; font-size: 15px">
                            Khách hàng
                        </div>
                    </button>
                </div>
            </div>

            <!-- Table product contaniner -->
            <div class="table-product-container">
                <table>
                    <tr>
                        <th>ID KH</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Tên</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>

                    <?php
                    if (sizeof($customers)) {
                        foreach ($customers as $customer) {
                    ?>
                            <tr>
                                <td><?php echo $customer['IdKH'] ?></td>
                                <td><?php echo $customer['Account'] ?></td>
                                <td><?php echo $customer['Email'] ?></td>
                                <td><?php echo $customer['Name'] ?></td>
                                <td><?php echo $customer['PNumber'] ?></td>
                                <td><?php echo $customer['AddressLine'] . ", " . $customer['District'] . ", " . $customer['Ward'] . ", " . $customer['Provinces'] ?></td>
                                <td><label class="switch">
                                        <input type="checkbox"
                                            data-customerid="<?php echo $customer['IdKH'] ?>"
                                            <?php echo ((int) $customer['Status'] == CustomerStatus::HOAT_DONG) ? 'checked' : ''; ?>
                                            ?> />
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div style="display: flex; justify-content: center">
                                        <i class="fa-solid fa-eye view-details" data-productid="<?php echo $customer['IdKH'] ?>"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" class="no-data">Không có dữ liệu khách hàng</td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>



        <!-- Model add customer -->
        <div class="model-add-product-container">
            <div id="click-hide-model" style="height: 100%; width: 30%"></div>
            <form class="add-product-container" action="./modules/customers/them-sua.php" method="post" enctype="multipart/form-data">
                <input name="addKH" type="text" hidden />
                <!-- Model header -->
                <div class="model-header">
                    <h2 style="color: #21568a; width: 100%">Thêm khách hàng</h2>
                    <i class="fa-solid fa-xmark close-icon" id="close-add-product"></i>
                </div>

                <?php if (isset($errors['db'])): ?>
                    <div class="alert alert-danger" style="color: red; margin: 10px 20px;">
                        <?php echo $errors['db']; ?>
                    </div>
                <?php endif; ?>

                <div>
                    <div class="model-body">
                        <label for="account" style="width: 15%; margin-left: 20px">Tên đăng nhập</label>
                        <div>
                            <input
                                type="text"
                                name="account"
                                placeholder="Tên đăng nhập"
                                style="
                            margin-left: 40px;
                            width: 100%;
                            padding: 3px;
                            border-radius: 5px;
                            border: 1px solid #636262;
                        "
                                value="<?php echo isset($formData['account']) ? htmlspecialchars($formData['account']) : ''; ?>"
                                onchange="isNotEmpty(event, 'tên đăng nhập', 'account-alert')" />
                            <p id="account-alert" class="alert" style="color: red;">
                                <?php echo isset($errors['account']) ? $errors['account'] : ''; ?>
                            </p>
                        </div>
                    </div>

                    <div class="model-body">
                        <label for="email" style="width: 15%; margin-left: 20px">Email</label>
                        <div>
                            <input
                                type="text"
                                name="email"
                                placeholder="Email"
                                style="
                            margin-left: 40px;
                            padding: 3px;
                            border-radius: 5px;
                            border: 1px solid #636262;
                            width: 100%;
                        "
                                value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                                onchange="isNotEmpty(event, 'email', 'email-alert')" />
                            <p id="email-alert" class="alert" style="color: red;">
                                <?php echo isset($errors['email']) ? $errors['email'] : ''; ?>
                            </p>
                        </div>
                    </div>

                    <div class="model-body">
                        <label for="name" style="width: 15%; margin-left: 20px">Tên khách hàng</label>
                        <div>
                            <input
                                type="text"
                                name="name"
                                placeholder="Tên khách hàng"
                                style="
                            margin-left: 40px;
                            padding: 3px;
                            border-radius: 5px;
                            border: 1px solid #636262;
                            width: 100%;
                        "
                                value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>"
                                onchange="isNotEmpty(event, 'tên', 'name-alert')" />
                            <p id="name-alert" class="alert" style="color: red;">
                                <?php echo isset($errors['name']) ? $errors['name'] : ''; ?>
                            </p>
                        </div>
                    </div>

                    <div class="model-body">
                        <label for="pNumber" style="width: 15%; margin-left: 20px">Số điện thoại</label>
                        <div>
                            <input
                                type="text"
                                name="pNumber"
                                placeholder="Số điện thoại"
                                style="
                            margin-left: 40px;
                            padding: 3px;
                            border-radius: 5px;
                            border: 1px solid #636262;
                            width: 100%;
                        "
                                value="<?php echo isset($formData['pNumber']) ? htmlspecialchars($formData['pNumber']) : ''; ?>"
                                onchange="isNotEmpty(event, 'số điện thoại', 'pNumber-alert')" />
                            <p id="pNumber-alert" class="alert" style="color: red;">
                                <?php echo isset($errors['pNumber']) ? $errors['pNumber'] : ''; ?>
                            </p>
                        </div>
                    </div>


                    <div class="model-body">
                        <label for="address" style="width: 15%; margin-left: 20px">Địa chỉ</label>
                        <div
                            style="
                                    display: flex;
                                    flex-direction: column;
                                    margin-left: 40px;
                                    gap: 1rem;
                                ">
                            <select id="province-input" name="province">
                                <option value="">Tỉnh / Thành phố</option>
                            </select>
                            <select id="district-input" disabled name="district">
                                <option value="">Quận / Huyện</option>
                            </select>
                            <select id="ward-input" disabled name="ward">
                                <option value="">Xã / Phường / Thị trấn</option>
                            </select>
                            <input type="text" id="address" placeholder="Địa chỉ" disabled name="address"
                                value="<?php echo isset($formData['address']) ? htmlspecialchars($formData['address']) : ''; ?>"
                                onchange="isNotEmpty(event, 'Địa chỉ', 'address-alert')" />
                            <p id="address-alert" class="alert" style="color: red;">
                                <?php echo isset($errors['address']) ? $errors['address'] : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="model-body" style="margin-bottom: 30px">
                    <label for="status" style="width: 15%; margin-left: 20px">Trạng thái</label>
                    <select
                        name="status"
                        style="
                    border-radius: 5px;
                    margin-left: 40px;
                    padding: 3px;
                    width: 20.5%;
                    ">
                        <?php
                        foreach ($listStatus as $status) {
                            $selected = (isset($formData['status']) && $formData['status'] == $status['id']) ? 'selected' : '';
                            echo "<option value='{$status['id']}' $selected>{$status['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Model footer -->
                <div class="model-footer">
                    <button type="submit" class="confirm-button">Xác nhận</button>
                    <button type="button" class="cancel-button" id="cancel-add-product">
                        Hủy
                    </button>
                </div>
            </form>
        </div>

        <!-- Model customer details -->
        <div class="model-view-details-container">
            <div style="display: flex">
                <i class="fa-solid fa-left-long cancel-view-details"></i>
                <h1 style="color: #21568a">Chi tiết khách hàng</h1>
            </div>
            <?php
            // Kiểm tra nếu tìm thấy khách hàng
            if ($customerFound) { ?>
                <div class="product-details">
                    <div style="display: flex; margin-bottom: 30px">
                        <div style="width: 30%; margin-right: 20px; text-align: center;">
                            <i class="fa-solid fa-user" style="font-size: 150px; color: #21568a;"></i>
                        </div>
                        <div style="margin-left: 20px; width: 70%;">
                            <h3 style="margin: 0px;">ID: <?php echo $customerFound['IdKH']; ?></h3>
                            <h1 style="color: #21568a"><?php echo $customerFound['Name']; ?></h1>

                            <div class="product-attribute">
                                <h2 class="attribute-header">Tên đăng nhập:</h2>
                                <p class="attribute-body"><?php echo $customerFound['Account']; ?></p>
                            </div>
                            <div class="product-attribute">
                                <h2 class="attribute-header">Email:</h2>
                                <p class="attribute-body"><?php echo $customerFound['Email']; ?></p>
                            </div>
                            <div class="product-attribute">
                                <h2 class="attribute-header">Số điện thoại:</h2>
                                <p class="attribute-body"><?php echo $customerFound['PNumber']; ?></p>
                            </div>
                            <div class="product-attribute">
                                <h2 class="attribute-header">Địa chỉ:</h2>
                                <p class="attribute-body"><?php echo $customerFound['AddressLine'] . ", " . $customerFound['District'] . ", " . $customerFound['Ward'] . ", " . $customerFound['Provinces'] ?></p>
                            </div>
                            <div class="product-attribute">
                                <h2 class="attribute-header">Trạng thái:</h2>
                                <p class="attribute-body">
                                    <?php
                                    foreach ($listStatus as $status) {
                                        if ($status['id'] === (int) $customerFound['Status']) {
                                            echo $status['name'];
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <button class="button-style" id="edit-product" style="margin-top: 10px" data-customerid="<?php echo $customerFound['IdKH']; ?>">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 13px; padding-top: 1px"></i>
                                <div style="padding-left: 10px; font-size: 15px">Sửa khách hàng</div>
                            </button>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                echo "Không tìm thấy khách hàng!";
            }
            ?>
        </div>

        <!-- Model cofirm delete -->
        <div class="model-confirm-delete-container">
            <div class="dialog-cofirm-delete">
                <div>
                    <i class="fa-solid fa-triangle-exclamation alert-icon"></i>
                    <h1 class="header-alert">Xóa sản phẩm</h1>
                    <h3 style="font-weight: normal; text-align: center">
                        Bạn có chắc muốn xóa sản phẩm này?
                    </h3>
                </div>
                <div class="model-footer">
                    <button class="confirm-button" id="cofirm-delete-product">
                        Xác nhận
                    </button>
                    <button class="cancel-button" onclick="cancelDeleteProduct()">
                        Hủy
                    </button>
                </div>
            </div>
        </div>

        <!-- Model edit product container -->
        <div class="model-edit-product-container">
            <div id="click-hide-model" style="height: 100%; width: 30%"></div>
            <div class="edit-product-container">
                <!-- Model header -->
                <div class="model-header">
                    <h2 style="color: #21568a; width: 100%">Sửa khách hàng</h2>
                    <i class="fa-solid fa-xmark close-icon" onclick="cancelEditProductModel()"></i>
                </div>

                <?php if (isset($errors['db'])): ?>
                    <div class="alert alert-danger" style="color: red; margin: 10px 20px;">
                        <?php echo $errors['db']; ?>
                    </div>
                <?php endif; ?>

                <!-- Model body -->
                <div id="edit-model-body">
                    <form id="edit-product-form" action="./modules/customers/them-sua.php?IdKH=<?php echo $customerFound['IdKH'] ?>" method="post" enctype="multipart/form-data">
                        <input name="updateKH" type="text" value="" hidden />
                        <div class="model-body">
                            <label for="account" style="width: 15%; margin-left: 20px">Tên đăng nhập</label>
                            <div>
                                <input
                                    type="text"
                                    name="account"
                                    placeholder="Tên đăng nhập"
                                    value="<?php echo $customerFound['Account'] ?? ''; ?>"
                                    style="margin-left: 40px; width: 60%; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                                    onchange="isNotEmpty(event, 'tên đăng nhập', 'account-alert-edit')" />
                                <p id="account-alert-edit" class="alert"></p>
                            </div>
                        </div>

                        <div class="model-body">
                            <label for="email" style="width: 15%; margin-left: 20px">Email</label>
                            <div>
                                <input
                                    type="text"
                                    name="email"
                                    placeholder="Email"
                                    value="<?php echo $customerFound['Email'] ?? ''; ?>"
                                    style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                                    onchange="isNotEmpty(event, 'email', 'email-alert-edit')" />
                                <p id="email-alert-edit" class="alert"></p>
                            </div>
                        </div>

                        <div class="model-body">
                            <label for="name" style="width: 15%; margin-left: 20px">Tên khách hàng</label>
                            <div>
                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Tên khách hàng"
                                    value="<?php echo $customerFound['Name'] ?? ''; ?>"
                                    style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                                    onchange="isNotEmpty(event, 'tên khách hàng', 'name-alert-edit')" />
                                <p id="name-alert-edit" class="alert"></p>
                            </div>
                        </div>

                        <div class="model-body">
                            <label for="pNumber" style="width: 15%; margin-left: 20px">Số điện thoại</label>
                            <div>
                                <input
                                    type="text"
                                    name="pNumber"
                                    placeholder="Số điện thoại"
                                    value="<?php echo $customerFound['PNumber'] ?? ''; ?>"
                                    style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                                    onchange="isNotEmpty(event, 'số điện thoại', 'pNumber-alert-edit')" />
                                <p id="pNumber-alert-edit" class="alert"></p>
                            </div>
                        </div>

                        <div class="model-body">
                            <label for="addres" style="width: 15%; margin-left: 20px">Địa chỉ</label>
                            <div>
                                <div
                                    style="
                                    display: flex;
                                    flex-direction: column;
                                    margin-left: 40px;
                                    gap: 1rem;
                                ">
                                    <select id="province-input-edit" name="province">
                                        <option value="">Tỉnh / Thành phố</option>
                                    </select>
                                    <select id="district-input-edit" disabled name="district">
                                        <option value="">Quận / Huyện</option>
                                    </select>
                                    <select id="ward-input-edit" disabled name="ward">
                                        <option value="">Xã / Phường / Thị trấn</option>
                                    </select>
                                    <input type="text" id="address-edit" placeholder="Địa chỉ" disabled name="address"
                                        value="<?php echo $customerFound['AddressLine'] ?? ''; ?>"
                                        onchange="isNotEmpty(event, 'địa chỉ', 'address-alert-edit')" />
                                    <p id="address-alert-edit" class="alert"></p>
                                </div>
                            </div>
                        </div>

                        <div class="model-body" style="margin-bottom: 30px">
                            <label for="status" style="width: 15%; margin-left: 20px">Trạng thái</label>
                            <select
                                name="status"
                                style="
                          border-radius: 5px;
                          margin-left: 40px;
                          padding: 3px;
                          width: 20.5%;
                        ">
                                <?php
                                foreach ($listStatus as $status) {
                                    $selectedStatus = ($customerFound['Status'] ?? '') == $status['id'] ? 'selected' : '';
                                    echo "<option value='{$status['id']}' $selectedStatus>{$status['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Model footer -->
                        <div class="model-footer">
                            <button type="submit" class="confirm-button">Xác nhận</button>
                            <button type="button" class="cancel-button" onclick="cancelEditProductModel()">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script
            src="https://kit.fontawesome.com/793699135f.js"
            crossorigin="anonymous"></script>

        <script src="./js/vietnamese-provinces-data.js"></script>
        <script src="./js/quan-ly-khach-hang.js?v=<?php echo time(); ?>"></script>
    </div>
</div>