<?php
$sql_get_products = "SELECT sp.`IdSP`, sp.`IdGRP`, nh.`name` AS `group_name`, sp.`name`, sp.`type`, sp.`price`, sp.`quantity`, sp.`releaseDate`, sp.`info`, sp.`IMG`
FROM `sanpham` sp
JOIN `nhom` nh ON sp.`IdGRP` = nh.`IdGRP`";
$query_get_products = mysqli_query($mysqli, $sql_get_products);

$sql_get_nhoms = "SELECT * from nhom";
$query_get_nhoms = mysqli_query($mysqli, $sql_get_nhoms);

$nhoms = []; // Mảng lưu trữ

// Nếu có dữ liệu
if ($query_get_nhoms && mysqli_num_rows($query_get_nhoms) > 0) {
  while ($row = mysqli_fetch_array($query_get_nhoms)) {
    $nhoms[] = $row;
  }
}
?>

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
        <p class="title-page">Sản phẩm</p>

        <div style="margin-left: auto; padding: 20px">
          <button class="button-style" id="add-product">
            <i class="fa-solid fa-plus" style="font-size: 15px"></i>
            <div style="padding-left: 10px; font-size: 15px">
              Thêm sản phẩm
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
            <th>ID SP</th>
            <th>Nhóm</th>
            <th>Ảnh sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Type</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thông tin</th>
            <th></th>
          </tr>

          <?php
          $products = []; // Mảng lưu trữ sản phẩm

          // Nếu có dữ liệu
          if ($query_get_products && mysqli_num_rows($query_get_products) > 0) {
            while ($row = mysqli_fetch_array($query_get_products)) {
              $products[] = $row; // Thêm sản phẩm vào mảng

          ?>
              <tr>
                <td><?php echo $row['IdSP'] ?></td>
                <td><?php echo $row['group_name'] ?></td>
                <td>
                  <img
                    src="./img/products/<?php echo $row['IMG'] ?>"
                    width="64"
                    height="64"
                    alt="San pham" />
                </td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['type'] ?></td>
                <td><?php echo $row['price'] ?></td>
                <td><?php echo $row['quantity'] ?></td>
                <td><?php echo $row['info'] ?></td>
                <td>
                  <div style="display: flex; justify-content: center">
                    <i class="fa-solid fa-eye view-details" data-productid="<?php echo $row['IdSP'] ?>"></i>
                    <i class="fa-solid fa-trash-can delete-product" data-productid="<?php echo $row['IdSP'] ?>"></i>
                  </div>
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
      <form class="add-product-container" action="./modules/products/them-sua-san-pham.php" method="post" enctype="multipart/form-data">
        <input name="addSP" type="text" hidden />
        <!-- Model header -->
        <div class="model-header">
          <h2 style="color: #21568a; width: 100%">Thêm sản phẩm</h2>
          <i class="fa-solid fa-xmark close-icon" id="close-add-product"></i>
        </div>

        <!-- Model body -->
        <div class="model-body" style="margin-bottom: 30px">
          <label for="IdGRP" style="width: 15%; margin-left: 20px">Nhóm</label>
          <select
            name="IdGRP"
            style="
              border-radius: 5px;
              margin-left: 40px;
              padding: 3px;
              width: 20.5%;
            ">
            <?php
            foreach ($nhoms as $nhom) {
              echo "<option value='{$nhom['IdGRP']}'>{$nhom['Name']}</option>";
            }
            ?>
          </select>
        </div>

        <div>
          <div class="model-body">
            <label for="name" style="width: 15%; margin-left: 20px">Tên sản phẩm</label>
            <div>
              <input
                type="text"
                name="name"
                placeholder="Tên sản phẩm"
                style="
                margin-left: 40px;
                width: 100%;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
              "
                onchange="isNotEmpty(event, 'tên', 'name-alert')" />
              <p id="name-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="type" style="width: 15%; margin-left: 20px">Type</label>
            <div>
              <input
                type="text"
                name="type"
                placeholder="Type"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                onchange="isNotEmpty(event, 'type', 'type-alert')" />
              <p id="type-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="price" style="width: 15%; margin-left: 20px">Đơn giá</label>
            <div>
              <input
                type="text"
                name="price"
                placeholder="Đơn giá"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                onchange="isNotEmpty(event, 'giá', 'price-alert')" />
              <p id="price-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="quantity" style="width: 15%; margin-left: 20px">Số lượng</label>
            <div>
              <input
                type="text"
                name="quantity"
                placeholder="Số lượng"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                onchange="isNotEmpty(event, 'số lượng', 'quantity-alert')" />
              <p id="quantity-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="releaseDate" style="width: 15%; margin-left: 20px">Ngày phát hành</label>
            <div>
              <input
                type="date"
                name="releaseDate"
                placeholder="Ngày phát hành"
                value="2025-01-01"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                onchange="isNotEmpty(event, 'số lượng', 'releaseDate-alert')" />
              <p id="releaseDate-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="description" style="width: 15%; margin-left: 20px">Thông tin</label>
            <textarea
              name="info"
              type="text"
              placeholder="Thông tin"
              style="
              margin-left: 40px;
              width: 70%;
              height: 150px;
              padding: 3px;
              border-radius: 5px;
              border: 1px solid #636262;
            "></textarea>
          </div>

          <div class="model-body">
            <label for="image" style="width: 15%; margin-left: 20px">Hình ảnh</label>
            <div>
              <input
                accept="image/*"
                type="file"
                name="IMG"
                style="margin-left: 40px"
                id="image-product"
                onchange="openFile(this, 'output-image', 'image-alert')" />
              <p id="image-alert" class="alert"></p>
              <img
                id="output-image"
                style="height: 100; width: 100px" />
            </div>
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

    <!-- Model product details -->
    <div class="model-view-details-container">
      <div style="display: flex">
        <i class="fa-solid fa-left-long cancel-view-details"></i>
        <h1 style="color: #21568a">Chi tiết sản phẩm</h1>
      </div>

      <?php
      if (isset($_GET['IdSP'])) {
        $idSP = $_GET['IdSP'];

        // Tìm sản phẩm theo IdSP trong mảng $products
        $productFound = null;
        foreach ($products as $product) {
          if ($product['IdSP'] == $idSP) {
            $productFound = $product;
            break;
          }
        }
      }
      ?>
      <?php
      // Kiểm tra nếu tìm thấy sản phẩm
      if ($productFound) { ?>
        <div class="product-details">
          <div style="display: flex; margin-bottom: 30px">
            <img
              src="./img/products/<?php echo $productFound['IMG']; ?>"
              alt="product-image"
              style="height: auto; width: 50%; margin-right: 20px" />
            <div style="margin-left: 20px">
              <h3 style="margin: 0px;"><?php echo $productFound['IdSP']; ?></h3>
              <h1 style="color: #21568a"><?php echo $productFound['name']; ?></h1>
              <h2 style="color: red; margin: 0px"><?php echo $productFound['price']; ?></h2>

              <div class="product-attribute">
                <h2 class="attribute-header">Type:</h2>
                <p class="attribute-body"><?php echo $productFound['type']; ?></p>
              </div>
              <div class="product-attribute">
                <h2 class="attribute-header">Nhóm:</h2>
                <p class="attribute-body"><?php echo $productFound['group_name']; ?></p>
              </div>
              <div class="product-attribute">
                <h2 class="attribute-header">Số lượng:</h2>
                <p class="attribute-body"><?php echo $productFound['quantity']; ?></p>
              </div>
              <button class="button-style" id="edit-product" style="margin-top: 10px">
                <i
                  class="fa-solid fa-pen-to-square"
                  style="font-size: 13px; padding-top: 1px"></i>
                <div style="padding-left: 10px; font-size: 15px">Sửa sản phẩm</div>
              </button>
            </div>
          </div>
          <h2 style="margin: 20px">Thông tin:</h2>
          <p style="font-size: 22px; margin: 20px 50px"><?php echo $productFound['info']; ?></p>
        </div>
      <?php
      } else {
        echo "Không tìm thấy sản phẩm!";
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
          <h2 style="color: #21568a; width: 100%">Sửa sản phẩm</h2>
          <i
            class="fa-solid fa-xmark close-icon"
            onclick="cancelEditProductModel()"></i>
        </div>
        <!-- Model body -->
        <div id="edit-model-body">
          <form id="edit-product-form" action="./modules/products/them-sua-san-pham.php?IdSP=<?php echo $productFound['IdSP'] ?>" method="post" enctype="multipart/form-data">
            <input name="updateSP" type="text" value="" hidden />
            <div class="model-body">
              <label for="name" style="width: 15%; margin-left: 20px">Tên sản phẩm</label>
              <div>
                <input
                  type="text"
                  name="name"
                  placeholder="Tên sản phẩm"
                  value="<?php echo $productFound['name'] ?? ''; ?>"
                  style="margin-left: 40px; width: 60%; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                  onchange="isNotEmpty(event, 'tên', 'name-alert-edit')" />
                <p id="name-alert-edit" class="alert"></p>
              </div>
            </div>

            <div class="model-body">
              <label for="group" style="width: 15%; margin-left: 20px; margin-bottom: 20px">Nhóm</label>
              <select name="IdGRP" style="border-radius: 5px; margin-left: 40px; padding: 3px; width: 20%;">
                <?php
                foreach ($nhoms as $nhom) {
                  $selected = ($productFound['IdGRP'] ?? '') == $nhom['IdGRP'] ? 'selected' : '';
                  echo "<option value='{$nhom['IdGRP']}' $selected>{$nhom['Name']}</option>";
                }
                ?>
              </select>
            </div>

            <div class="model-body">
              <label for="type" style="width: 15%; margin-left: 20px">Type</label>
              <div>
                <input
                  type="text"
                  name="type"
                  placeholder="Hãng"
                  value="<?php echo $productFound['type'] ?? ''; ?>"
                  style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                  onchange="isNotEmpty(event, 'hãng', 'brand-alert-edit')" />
                <p id="brand-alert-edit" class="alert"></p>
              </div>
            </div>

            <div class="model-body">
              <label for="price" style="width: 15%; margin-left: 20px">Đơn giá</label>
              <div>
                <input
                  type="text"
                  name="price"
                  placeholder="Đơn giá"
                  value="<?php echo $productFound['price'] ?? ''; ?>"
                  style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                  onchange="isNotEmpty(event, 'giá', 'price-alert-edit')" />
                <p id="price-alert-edit" class="alert"></p>
              </div>
            </div>

            <div class="model-body">
              <label for="quantity" style="width: 15%; margin-left: 20px">Số lượng</label>
              <div>
                <input
                  type="text"
                  name="quantity"
                  placeholder="Số lượng"
                  value="<?php echo $productFound['quantity'] ?? ''; ?>"
                  style="margin-left: 40px; padding: 3px; border-radius: 5px; border: 1px solid #636262; width: 100%;"
                  onchange="isNotEmpty(event, 'số lượng', 'quantity-alert-edit')" />
                <p id="quantity-alert-edit" class="alert"></p>
              </div>
            </div>

            <div class="model-body">
              <label for="info" style="width: 15%; margin-left: 20px">Thông tin sản phẩm</label>
              <textarea
                name="info"
                type="text"
                placeholder="Mô tả sản phẩm"
                style="margin-left: 40px; width: 70%; height: 150px; padding: 3px; border-radius: 5px; border: 1px solid #636262;"><?php echo $productFound['info'] ?? ''; ?></textarea>
            </div>

            <div class="model-body">
              <label for="IMG" style="width: 15%; margin-left: 20px">Hình ảnh</label>
              <div>
                <input accept="image/*" value="<?php echo $productFound['IMG'] ?>" type="file" name="IMG" style="margin-left: 40px" onchange="openFile(this,'output-image-edit', 'image-alert-edit')" />
                <p id="image-alert-edit" class="alert"></p>
                <img src="./img/products/<?php echo $productFound['IMG']; ?>" id="output-image-edit" style="height: 100px; width: 100px" />
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

    <script src="./js/quan-ly-san-pham.js?v=<?php echo time(); ?>"></script>
  </div>
</div>