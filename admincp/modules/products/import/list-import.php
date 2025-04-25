<?php
$sql_get_imports = "SELECT *
FROM `nhaphang` nhapHang
JOIN `sanpham` sp ON sp.`IdSP` = nhapHang.`IdSP`";
$query_get_imports = mysqli_query($mysqli, $sql_get_imports);

$imports = [];

// Nếu có dữ liệu
if ($sql_get_imports && mysqli_num_rows($query_get_imports) > 0) {
    while ($row = mysqli_fetch_array($query_get_imports)) {
        $imports[] = $row;
    }
}

$sql_get_products = "SELECT * from sanpham";
$query_get_products = mysqli_query($mysqli, $sql_get_products);

$products = []; // Mảng lưu trữ

// Nếu có dữ liệu
if ($query_get_products && mysqli_num_rows($query_get_products) > 0) {
    while ($row = mysqli_fetch_array($query_get_products)) {
        $products[] = $row;
    }
}

// if (isset($_GET['IdNhapHang'])) {
//     $idNhapHang = $_GET['IdNhapHang'];

//     // Tìm sản phẩm theo IdSP trong mảng $products
//     $importFound = null;
//     foreach ($imports as $import) {
//         if ($import['IdNhapHang'] == $idNhapHang) {
//             $importFound = $import;
//             break;
//         }
//     }
// }

$listStatus = [
    ['id' => 1, 'name' => 'Đang bán'],
    ['id' => 2, 'name' => 'Ẩn'],
    ['id' => 3, 'name' => 'Hết hàng']
];
?>

<script>
    var imports = [];
    var imports = <?php echo json_encode($imports); ?>;
</script>

<style>
    body {
        overflow: hidden;
    }

    .main-content {
        padding: 0px;
    }
</style>

<div style="position: relative; width: 100%; height: calc(100vh - 110px);">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">

        <!-- Body page -->
        <div
            class="bg-color"
            style="height: 100vh; width: 100%; overflow-y: auto; position: relative;">
            <!-- Title page -->
            <div style="display: flex">
                <p class="title-page">Nhập hàng</p>

                <div style="margin-left: auto; padding: 20px">
                    <button class="button-style" id="add-product">
                        <i class="fa-solid fa-plus" style="font-size: 15px"></i>
                        <div style="padding-left: 10px; font-size: 15px">
                            Nhập thêm hàng
                        </div>
                    </button>
                </div>
            </div>

            <!-- Search options -->
            <form class="search-options-style" onsubmit="filterProduct(event)">
                <!-- Search by product name -->
                <div class="search-option">
                    <input
                        name="name"
                        type="text"
                        placeholder="Tìm theo tên sản phẩm"
                        style="padding: 5px" />
                </div>

                <!-- Search by brand-->
                <div class="search-option">
                    <input
                        name="brand"
                        type="text"
                        placeholder="Tìm theo tên hãng"
                        style="padding: 5px" />
                </div>

                <!-- Filter by category -->
                <div class="search-option">
                    <select name="category" style="padding: 5px">
                        <option value="all" selected>Tất cả sản phẩm</option>
                        <option value="Bàn phím cơ">Bàn phím cơ</option>
                        <option value="Bàn phím gaming">Bàn phím gaming</option>
                        <option value="Bàn phím không dây">Bàn phím không dây</option>
                        <option value="Bàn phím đối xứng">Bàn phím đối xứng</option>
                        <option value="Bàn phím mini">Bàn phím mini</option>
                    </select>
                </div>

                <!-- Arrange option -->
                <div class="search-option">
                    <select name="sort" style="padding: 5px">
                        <option value="lastest-product" selected>
                            Sản phẩm mới nhất
                        </option>
                        <option value="price-decrease">Giá từ cao đến thấp</option>
                        <option value="price-increase">Giá từ thấp đến cao</option>
                    </select>
                </div>

                <!-- Filter button -->
                <div class="search-option">
                    <button
                        class="button-style"
                        type="submit"
                        style="background-color: #15803d">
                        <i
                            class="fa-solid fa-filter"
                            style="font-size: 13px; padding-top: 1px"></i>
                        <div style="padding-left: 10px; font-size: 15px">
                            Lọc sản phẩm
                        </div>
                    </button>
                </div>

                <!-- Refresh button -->
                <div class="search-option">
                    <button
                        class="button-style flex-container"
                        type="button"
                        style="background-color: #d1d5db"
                        onclick="showProductData()">
                        <i
                            class="fa-solid fa-rotate-right"
                            style="font-size: 13px; padding-top: 1px; color: black"></i>

                        <div style="padding-left: 10px; font-size: 15px; color: black">
                            Làm mới
                        </div>
                    </button>
                </div>
            </form>

            <!-- Table product contaniner -->
            <div class="table-product-container">
                <table>
                    <tr>
                        <th>ID Nhập hàng</th>
                        <th>ID SP</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá nhập</th>
                        <th>Số lượng nhập</th>
                        <th>Ngày nhập</th>
                        <th></th>
                    </tr>

                    <?php

                    // Nếu có dữ liệu
                    if ($query_get_imports && mysqli_num_rows($query_get_imports) > 0) {
                        foreach ($imports as $import) {
                    ?>
                            <tr>
                                <td><?php echo $import['IdNhapHang'] ?></td>
                                <td><?php echo $import['IdSP'] ?></td>
                                <td><?php echo $import['Name'] ?></td>
                                <td><?php echo $import['ImportPrice'] ?></td>
                                <td><?php echo $import['ImportQuantity'] ?></td>
                                <td><?php echo $import['ImportDate'] ?></td>
                                <td style="display: flex; justify-content: center">
                                    <i class="fa-solid fa-edit edit-import" style="cursor: pointer;" data-id-nhap-hang="<?php echo $import['IdNhapHang'] ?>"></i>
                                    <!-- <i class="fa-solid fa-trash-can delete-product" data-id-nhap-hang="<?php echo $import['IdNhapHang'] ?>"></i> -->
                                </td>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" class="no-data">Không có dữ liệu sản phẩm</td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="model-add-product-container">
            <div id="click-hide-model" style="height: 100%; width: 30%"></div>
            <form class="add-product-container">
                <input name="add" type="text" hidden />
                <!-- Model header -->
                <div class="model-header">
                    <h2 style="color: #21568a; width: 100%">Nhập hàng</h2>
                    <i class="fa-solid fa-xmark close-icon" id="close-add-product"></i>
                </div>

                <div class="alert alert-danger" style="color:red;">

                </div>

                <!-- Model body -->
                <div class="model-body" style="margin-bottom: 30px">
                    <label for="IdSP" style="width: 15%; margin-left: 20px">Sản phẩm</label>
                    <select
                        name="IdSP"
                        style="
                            border-radius: 5px;
                            margin-left: 40px;
                            padding: 3px;
                            width: 20.5%;
                            ">
                        <?php
                        foreach ($products as $product) {
                            echo "<option value='{$product['IdSP']}'>{$product['Name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>

                    <div class="model-body">
                        <label for="ImportPrice" style="width: 15%; margin-left: 20px">Giá nhập</label>
                        <div>
                            <input
                                type="text"
                                name="ImportPrice"
                                placeholder="Giá nhập"
                                style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                onchange="isNotEmpty(event, 'giá nhập', 'ImportPrice-alert-edit')" />
                            <p id="ImportPrice-alert-edit" class="alert"></p>
                        </div>
                    </div>

                    <div class="model-body">
                        <label for="ImportQuantity" style="width: 15%; margin-left: 20px">Số lượng</label>
                        <div>
                            <input
                                type="text"
                                name="ImportQuantity"
                                placeholder="Số lượng"
                                style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                onchange="isNotEmpty(event, 'số lượng', 'ImportQuantity-alert-edit')" />
                            <p id="ImportQuantity-alert-edit" class="alert"></p>
                        </div>
                    </div>

                    <div class="model-body">
                        <label for="ImportDate" style="width: 15%; margin-left: 20px">Ngày nhập hàng</label>
                        <div>
                            <input
                                type="date"
                                name="ImportDate"
                                placeholder="Ngày nhập hàng"
                                style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                onchange="isNotEmpty(event, 'ngày nhập', 'ImportDate-alert-edit')" />
                            <p id="ImportDate-alert-edit" class="alert"></p>
                        </div>
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
                    <h2 style="color: #21568a; width: 100%">Sửa thông tin nhập hàng</h2>
                    <i
                        class="fa-solid fa-xmark close-icon"
                        onclick="cancelEditProductModel()"></i>
                </div>
                <!-- Model body -->
                <div id="edit-model-body">
                    <form id="edit-product-form">
                        <input name="edit" type="text" value="" hidden />
                        <div class="alert-edit alert-danger" style="color:red;">

                        </div>

                        <!-- Model body -->
                        <div class="model-body" style="margin-bottom: 30px">
                            <label for="IdSP" style="width: 15%; margin-left: 20px">Sản phẩm</label>
                            <select
                                name="IdSP"
                                style="
                            border-radius: 5px;
                            margin-left: 40px;
                            padding: 3px;
                            width: 20.5%;
                            ">
                                <?php
                                foreach ($products as $product) {
                                    echo "<option value='{$product['IdSP']}'>{$product['Name']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>

                            <div class="model-body">
                                <label for="ImportPrice" style="width: 15%; margin-left: 20px">Giá nhập</label>
                                <div>
                                    <input
                                        type="text"
                                        name="ImportPrice"
                                        placeholder="Giá nhập"
                                        style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                        onchange="isNotEmpty(event, 'giá nhập', 'ImportPrice-alert')" />
                                    <p id="ImportPrice-alert" class="alert"></p>
                                </div>
                            </div>

                            <div class="model-body">
                                <label for="ImportQuantity" style="width: 15%; margin-left: 20px">Số lượng</label>
                                <div>
                                    <input
                                        type="text"
                                        name="ImportQuantity"
                                        placeholder="Số lượng"
                                        style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                        onchange="isNotEmpty(event, 'số lượng', 'ImportQuantity-alert-edit')" />
                                    <p id="ImportQuantity-alert-edit" class="alert"></p>
                                </div>
                            </div>

                            <div class="model-body">
                                <label for="ImportDate" style="width: 15%; margin-left: 20px">Ngày nhập hàng</label>
                                <div>
                                    <input
                                        type="date"
                                        name="ImportDate"
                                        placeholder="Ngày nhập hàng"
                                        style="
                                    margin-left: 40px;
                                    padding: 3px;
                                    border-radius: 5px;
                                    border: 1px solid #636262;
                                    width: 100%;
                                "
                                        onchange="isNotEmpty(event, 'ngày nhập', 'ImportDate-alert-edit')" />
                                    <p id="ImportDate-alert-edit" class="alert"></p>
                                </div>
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

        <script src="./js/quan-ly-nhap-hang.js?v=<?php echo time(); ?>"></script>
    </div>
</div>