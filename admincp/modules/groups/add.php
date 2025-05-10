<?php
include('../../config/config.php');

// Bật hiển thị lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

function executeQuery($mysqli, $query)
{
    if (mysqli_query($mysqli, $query)) {
        header('Location: ../../index.php?action=groups');
        exit();
    } else {
        die('Database error: ' . mysqli_error($mysqli));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: In ra dữ liệu nhận được
    echo "POST Data:<br>";
    print_r($_POST);
    echo "<br><br>FILES Data:<br>";
    print_r($_FILES);
    echo "<br><br>";

    // Lấy dữ liệu từ form
    $name = isset($_POST['Name']) ? $_POST['Name'] : '';
    $company = isset($_POST['Company']) ? $_POST['Company'] : '';
    $info = isset($_POST['Info']) ? $_POST['Info'] : '';

    if (empty($name) || empty($company)) {
        die("Vui lòng điền đầy đủ tên nhóm và công ty.");
    }

    // Xử lý file ảnh
    if(isset($_FILES['IMG']) && $_FILES['IMG']['error'] == 0) {
        $target_dir = "../../img/groups/";
        
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("Không thể tạo thư mục lưu ảnh.");
            }
        }
        
        // Tạo tên file ngẫu nhiên để tránh trùng lặp
        $imageFileType = strtolower(pathinfo($_FILES["IMG"]["name"], PATHINFO_EXTENSION));
        $filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $filename;
        
        // Kiểm tra định dạng file
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            die("Chỉ chấp nhận file JPG, JPEG, PNG.");
        }
        
        // Upload file
        if (move_uploaded_file($_FILES["IMG"]["tmp_name"], $target_file)) {
            try {
                // Thêm nhóm mới vào database
                $sql = "INSERT INTO nhom (Name, Company, Info, IMG) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($mysqli, $sql);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ssss', $name, $company, $info, $filename);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        // Chuyển hướng về trang danh sách nhóm
                        header('Location: ../../index.php?action=groups&status=success');
                        exit();
                    } else {
                        // Xóa file ảnh nếu thêm vào database thất bại
                        unlink($target_file);
                        die("Lỗi thêm nhóm: " . mysqli_error($mysqli));
                    }
                    
                    mysqli_stmt_close($stmt);
                } else {
                    // Xóa file ảnh nếu chuẩn bị câu lệnh thất bại
                    unlink($target_file);
                    die("Lỗi chuẩn bị câu lệnh: " . mysqli_error($mysqli));
                }
            } catch (Exception $e) {
                // Xóa file ảnh nếu có lỗi xảy ra
                unlink($target_file);
                die("Có lỗi xảy ra: " . $e->getMessage());
            }
        } else {
            die("Lỗi upload file. Chi tiết: " . error_get_last()['message']);
        }
    } else {
        $error_message = "Lỗi file: ";
        switch($_FILES['IMG']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $error_message .= "File vượt quá kích thước cho phép.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $error_message .= "File vượt quá kích thước cho phép trong form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_message .= "File chỉ được tải lên một phần.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message .= "Không có file nào được tải lên.";
                break;
            default:
                $error_message .= "Lỗi không xác định.";
        }
        die($error_message);
    }
    
    mysqli_close($mysqli);
} else {
    die("Phương thức không được hỗ trợ.");
}
