<?php

// Lấy danh sách nhóm từ database
$sql_get_groups = "SELECT `IdGRP`, `Name`, `Company`, `Info`, `IMG` FROM `nhom` ORDER BY `IdGRP` ASC";
$query_get_groups = mysqli_query($mysqli, $sql_get_groups);

// Kiểm tra lỗi query
if (!$query_get_groups) {
  die("Lỗi truy vấn: " . mysqli_error($mysqli));
}

// Debug data
?>

<style>
  body {
    overflow: hidden;
  }

  .main-content {
    padding: 0px;
  }
</style>

<link rel="stylesheet" type="text/css" href="./css/quan-ly-nhom.css?v=<?php echo time(); ?>">


<div style="position: relative; width: 100%; height: calc(100vh - 54px);">
  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">

    <!-- Body page -->
    <div
      class="bg-color"
      style="height: 100vh; width: 100%; overflow-y: auto; position: relative;">
      <!-- Title page -->
      <div style="display: flex">
        <p class="title-page">Nhóm</p>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
          <div style="margin: auto; padding: 10px; background-color: #4CAF50; color: white; border-radius: 5px;">
            Thêm nhóm thành công!
          </div>
        <?php endif; ?>

        <div style="margin-left: auto; padding: 20px">
          <button class="button-style" id="add-group">
            <i class="fa-solid fa-plus" style="font-size: 15px"></i>
            <div style="padding-left: 10px; font-size: 15px">
              Thêm nhóm
            </div>
          </button>
        </div>
      </div>

      <!-- Table group container -->
      <div class="table-group-container">
        <table>
          <tr>
            <th>ID Nhóm</th>
            <th>Logo</th>
            <th>Nhóm</th>
            <th>Công ty</th>
            <th>Thông tin</th>
            <th></th>
          </tr>

          <?php
          if (mysqli_num_rows($query_get_groups) > 0) {
            $stt = 1;
            while ($row = mysqli_fetch_assoc($query_get_groups)) {
          ?>
              <tr>
                <td><?php echo $row['IdGRP'] ?></td>
                <td>
                  <img
                    src="./img/groups/<?php echo $row['IMG']; ?>"
                    width="64"
                    height="64"
                    alt="nhom" />
                </td>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['Company']; ?></td>
                <td><?php echo $row['Info']; ?></td>
                <td>
                  <div style="display: flex; justify-content: center">
                    <i class="fa-solid fa-pen-to-square edit-group" data-groupid="<?php echo $row['IdGRP']; ?>" style="margin-right: 10px; cursor: pointer;"></i>
                    <i class="fa-solid fa-trash-can delete-group" data-groupid="<?php echo $row['IdGRP']; ?>" style="cursor: pointer;"></i>
                  </div>
                </td>
              </tr>
            <?php
            }
          } else {
            ?>
            <tr>
              <td colspan="6" class="no-data">Không có dữ liệu nhóm</td>
            </tr>
          <?php
          }
          ?>
        </table>
      </div>
    </div>

    <div class="model-add-group-container">
      <div id="click-hide-model" style="height: 100%; width: 30%"></div>
      <form class="add-group-container" action="modules/groups/add.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="addGRP" value="1" />
        <!-- Model header -->
        <div class="model-header">
          <h2 style="color: #21568a; width: 100%">Thêm nhóm</h2>
          <i class="fa-solid fa-xmark close-icon" id="close-add-group"></i>
        </div>

        <!-- Model body -->
        <div>
          <div class="model-body">
            <label for="name" style="width: 15%; margin-left: 20px">Tên nhóm</label>
            <div>
              <input
                type="text"
                name="Name"
                placeholder="Tên nhóm"
                style="
                margin-left: 40px;
                width: 100%;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
              "
                required
                onchange="isNotEmpty(event, 'tên', 'name-alert')" />
              <p id="name-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="company" style="width: 15%; margin-left: 20px">Công Ty</label>
            <div>
              <input
                type="text"
                name="Company"
                placeholder="Tên công ty"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                required
                onchange="isNotEmpty(event, 'công ty', 'comp-alert')" />
              <p id="comp-alert" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="info" style="width: 15%; margin-left: 20px">Thông tin</label>
            <textarea
              name="Info"
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
                id="image-group"
                required
                onchange="openFile(this, 'output-image', 'image-alert')" />
              <p id="image-alert" class="alert"></p>
              <img
                id="output-image"
                style="height: 100px; width: 100px" />
            </div>
          </div>
        </div>

        <!-- Model footer -->
        <div class="model-footer">
          <button type="submit" name="addGRP" class="confirm-button">Xác nhận</button>
          <button type="button" class="cancel-button" id="cancel-add-group">
            Hủy
          </button>
        </div>
      </form>
    </div>

    <!-- Model group details -->
    <div class="model-view-details-container">
      <div style="display: flex">
        <i class="fa-solid fa-left-long cancel-view-details"></i>
        <h1 style="color: #21568a">Chi tiết sản phẩm</h1>
      </div>

      <?php
      if (isset($_GET['IdGRP'])) {
        $IdGRP = $_GET['IdGRP'];

        // Tìm sản phẩm theo IdGRP trong mảng $groups
        $groupFound = null;
        while ($row = mysqli_fetch_assoc($query_get_groups)) {
          if ($row['IdGRP'] == $IdGRP) {
            $groupFound = $row;
            break;
          }
        }
      }
      ?>
      <?php
      // Kiểm tra nếu tìm thấy sản phẩm
      if ($groupFound) { ?>
        <div class="group-details">
          <div style="display: flex; margin-bottom: 30px">
            <img
              src="../../../admincp/img/groups/<?php echo $groupFound['IMG']; ?>"
              alt="group-image"
              style="height: auto; width: 50%; margin-right: 20px" />
            <div style="margin-left: 20px">
              <h3 style="margin: 0px;"><?php echo $groupFound['IdGRP']; ?></h3>
              <h1 style="color: #21568a"><?php echo $groupFound['Name']; ?></h1>
              <h2 style="color: red; margin: 0px"><?php echo $groupFound['Company']; ?></h2>


              <button class="button-style" id="edit-group" style="margin-top: 10px">
                <i
                  class="fa-solid fa-pen-to-square"
                  style="font-size: 13px; padding-top: 1px"></i>
                <div style="padding-left: 10px; font-size: 15px">Sửa nhóm</div>
              </button>
            </div>
          </div>
          <h2 style="margin: 20px">Thông tin:</h2>
          <p style="font-size: 22px; margin: 20px 50px"><?php echo $groupFound['Info']; ?></p>
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
          <button class="confirm-button" id="cofirm-delete-group">
            Xác nhận
          </button>
          <button class="cancel-button" onclick="cancelDeleteGroup()">
            Hủy
          </button>
        </div>
      </div>
    </div>

    <!-- Model edit group container -->
    <div class="model-edit-group-container">
      <div id="click-hide-model" style="height: 100%; width: 30%"></div>
      <form class="edit-group-container" action="modules/groups/fix.php" method="post" enctype="multipart/form-data">
        <!-- Model header -->
        <div class="model-header">
          <h2 style="color: #21568a; width: 100%">Sửa nhóm</h2>
          <i class="fa-solid fa-xmark close-icon" id="close-edit-group"></i>
        </div>

        <!-- Model body -->
        <div>
          <div class="model-body">
            <label for="name" style="width: 15%; margin-left: 20px">Tên nhóm</label>
            <div>
              <input
                type="text"
                name="Name"
                placeholder="Tên nhóm"
                style="
                margin-left: 40px;
                width: 100%;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
              "
                required
                onchange="isNotEmpty(event, 'tên', 'name-alert-edit')" />
              <p id="name-alert-edit" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="company" style="width: 15%; margin-left: 20px">Công Ty</label>
            <div>
              <input
                type="text"
                name="Company"
                placeholder="Tên công ty"
                style="
                margin-left: 40px;
                padding: 3px;
                border-radius: 5px;
                border: 1px solid #636262;
                width: 100%;
              "
                required
                onchange="isNotEmpty(event, 'công ty', 'comp-alert-edit')" />
              <p id="comp-alert-edit" class="alert"></p>
            </div>
          </div>

          <div class="model-body">
            <label for="info" style="width: 15%; margin-left: 20px">Thông tin</label>
            <textarea
              name="Info"
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
                id="image-group-edit"
                onchange="openFile(this, 'output-image-edit', 'image-alert-edit')" />
              <p id="image-alert-edit" class="alert"></p>
              <img
                id="output-image-edit"
                style="height: 100px; width: 100px" />
            </div>
          </div>
        </div>

        <!-- Model footer -->
        <div class="model-footer">
          <button type="submit" name="updateGRP" class="confirm-button">Cập nhật</button>
          <button type="button" class="cancel-button" id="cancel-edit-group">
            Hủy
          </button>
        </div>
      </form>
    </div>

    <script
      src="https://kit.fontawesome.com/793699135f.js"
      crossorigin="anonymous"></script>

    <script src="./js/quanlygrp.js?v=<?php echo time() ?>"></script>
  </div>
</div>