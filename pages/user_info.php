<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../admincp/config/config.php'); // Điều chỉnh đường dẫn nếu cần

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Chuyển đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$userId = $_SESSION['user']['IdKH'];
$message = ''; // Biến lưu thông báo (thành công/lỗi)

// Xử lý khi form chính được gửi đi (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile_main'])) { // Thêm kiểm tra nút submit chính
    // Lấy dữ liệu từ form (bao gồm cả các trường ẩn cho địa chỉ)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Địa chỉ lấy từ các trường input ẩn được cập nhật bởi modal
    $address_line = trim($_POST['address_line_hidden'] ?? '');
    $provinces = trim($_POST['provinces_hidden'] ?? '');
    $district = trim($_POST['district_hidden'] ?? '');
    $ward = trim($_POST['ward_hidden'] ?? '');

    // --- Validate dữ liệu ---
    if (empty($name) || empty($email) || empty($phone)) {
        $message = '<div class="alert-message alert-danger">Họ tên, Email và Số điện thoại không được để trống.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert-message alert-danger">Định dạng email không hợp lệ.</div>';
    } else {
        // Nếu người dùng đã nhập thông tin địa chỉ qua modal (và nó không rỗng) thì phải đủ
        if ((!empty($provinces) || !empty($district) || !empty($ward) || !empty($address_line)) &&
            (empty($provinces) || empty($district) || empty($ward) || empty($address_line))) {
            $message = '<div class="alert-message alert-danger">Vui lòng điền đầy đủ thông tin địa chỉ (Tỉnh/Thành, Quận/Huyện, Phường/Xã, Số nhà/Đường) nếu bạn muốn cập nhật địa chỉ.</div>';
        } else {
            // --- Cập nhật vào CSDL ---
            $sql = "UPDATE khachhang SET Name = ?, Email = ?, PNumber = ?, AddressLine = ?, Ward = ?, District = ?, Provinces = ? WHERE IdKH = ?";
            $stmt = $mysqli->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssssssi", $name, $email, $phone, $address_line, $ward, $district, $provinces, $userId);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        // Cập nhật thành công, làm mới thông tin trong session
                        $_SESSION['user']['Name'] = $name;
                        $_SESSION['user']['Email'] = $email;
                        $_SESSION['user']['PNumber'] = $phone;
                        $_SESSION['user']['AddressLine'] = $address_line;
                        $_SESSION['user']['Ward'] = $ward;
                        $_SESSION['user']['District'] = $district;
                        $_SESSION['user']['Provinces'] = $provinces;
                        $message = '<div class="alert-message alert-success">Thông tin tài khoản đã được cập nhật thành công!</div>';
                    } else {
                        $message = '<div class="alert-message alert-info">Không có thay đổi nào được ghi nhận hoặc dữ liệu giống với hiện tại.</div>';
                    }
                } else {
                    $message = '<div class="alert-message alert-danger">Lỗi khi cập nhật cơ sở dữ liệu: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            } else {
                $message = '<div class="alert-message alert-danger">Lỗi khi chuẩn bị truy vấn: ' . $mysqli->error . '</div>';
            }
        }
    }
}

// Lấy thông tin người dùng (có thể đã được cập nhật từ POST)
$user = $_SESSION['user'];

// Hàm để hiển thị giá trị an toàn
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_address_display($user_data) {
    $address_parts = [];
    if (!empty($user_data['AddressLine'])) $address_parts[] = e($user_data['AddressLine']);
    if (!empty($user_data['Ward'])) $address_parts[] = e($user_data['Ward']);
    if (!empty($user_data['District'])) $address_parts[] = e($user_data['District']);
    if (!empty($user_data['Provinces'])) $address_parts[] = e($user_data['Provinces']);
    return implode(', ', $address_parts) ?: 'Chưa có thông tin địa chỉ.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản - <?php echo e($user['Name']); ?></title>
    <link rel="stylesheet" type="text/css" href="./css/user_info.css">
</head>
<body>
    <?php include_once(__DIR__ . '/header.php'); // Include header ?>
    <div class="profile-container">
        <h1>Thông tin tài khoản</h1>

        <?php if (!empty($message)) echo $message; // Hiển thị thông báo ?>

        <form id="user-profile-form" method="POST" action="">
            <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" value="<?php echo e($user['Name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo e($user['Email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo e($user['PNumber']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Địa chỉ hiện tại:</label>
                <div id="current-address-display" class="address-display-box">
                    <?php echo format_address_display($user); ?>
                </div>
                <button type="button" id="open-address-modal-btn" class="btn btn-secondary">Chỉnh sửa địa chỉ</button>
            </div>

            <input type="hidden" name="provinces_hidden" id="hidden_provinces" value="<?php echo e($user['Provinces']); ?>">
            <input type="hidden" name="district_hidden" id="hidden_district" value="<?php echo e($user['District']); ?>">
            <input type="hidden" name="ward_hidden" id="hidden_ward" value="<?php echo e($user['Ward']); ?>">
            <input type="hidden" name="address_line_hidden" id="hidden_address_line" value="<?php echo e($user['AddressLine']); ?>">

            <button type="submit" name="save_profile_main" class="btn-submit">Lưu thay đổi chung</button>
        </form>
    </div>

    <div id="address-edit-modal" class="modal-overlay">
        <div class="modal-content">
            <button type="button" id="close-address-modal-btn" class="modal-close-btn">&times;</button>
            <h3>Chỉnh sửa địa chỉ</h3>
            <div id="address-modal-message" style="color:red; margin-bottom:10px; font-size:0.9em;"></div>
            <fieldset class="address-fields">
                <legend style="font-size:0.9em; color:#777;">Để trống tất cả nếu muốn xóa địa chỉ, hoặc điền đầy đủ.</legend>
                <div class="form-group">
                    <label for="modal_provinces">Tỉnh/Thành phố:</label>
                    <select id="modal_provinces" name="modal_provinces">
                        <option value="">-- Chọn Tỉnh/Thành phố --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modal_district">Quận/Huyện:</label>
                    <select id="modal_district" name="modal_district" disabled>
                        <option value="">-- Chọn Quận/Huyện --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modal_ward">Phường/Xã:</label>
                    <select id="modal_ward" name="modal_ward" disabled>
                        <option value="">-- Chọn Phường/Xã --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modal_address_line">Số nhà, tên đường:</label>
                    <input type="text" id="modal_address_line" name="modal_address_line" disabled>
                </div>
            </fieldset>
            <button type="button" id="save-address-from-modal-btn" class="btn btn-primary-modal">Lưu địa chỉ này</button>
            <button type="button" id="cancel-address-modal-btn" class="btn btn-secondary">Hủy</button>
        </div>
    </div>

    <script src="./admincp/js/vietnamese-provinces-data.js"></script> 
    <script src="./js/user_info.js"></script>
</body>
</html>