<?php
require_once('../../config/config.php');

function executeQuery($mysqli, $query)
{
    if (mysqli_query($mysqli, $query)) {
        header('Location: ../../index.php?action=products');
        exit();
    } else {
        die('Database error: ' . mysqli_error($mysqli));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $IdGRP = $_POST['IdGRP'] ?? '';
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? '';
    $ratio = $_POST['ratio'] ?? '';
    $releasedDate = $_POST['releaseDate'] ?? '2025-01-01';
    $info = $_POST['info'] ?? '';
    $IMG = $_POST['IMG'] ?? '';
    $status = $_POST['status'] ?? '';

    if (isset($_POST['addSP'])) {
        $query = "INSERT INTO sanpham (IdGRP, name, type, Ratio, quantity, releaseDate, info, IMG, status, Price) 
          VALUES (?, ?, ?, ?, 0, ?, ?, ?, 2, 0)";
    } elseif (isset($_POST['updateSP']) && isset($_GET['IdSP'])) {
        $IdSP = $_GET['IdSP'];

        $query = "UPDATE sanpham SET IdGRP = ?, name = ?, type = ?, Ratio = ?, releaseDate = ?, info = ?, IMG = ?, status = ?, Price = ?
                  WHERE IdSP = ?";
    } else {
        die('Invalid request.');
    }

    $stmt = mysqli_prepare($mysqli, $query);
    if (!$stmt) {
        die('SQL prepare error: ' . mysqli_error($mysqli));
    }

    // Xử lý file ảnh
    $target_dir = "../../img/products/"; // Thư mục lưu ảnh
    $target_file = $target_dir . basename($_FILES["IMG"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Di chuyển ảnh vào thư mục uploads
    move_uploaded_file($_FILES["IMG"]["tmp_name"], $target_file);
    $filename = basename($_FILES["IMG"]["name"]) ?? "";

    if (isset($_POST['updateSP']) && $filename === "") {
        $sql_get_product = "SELECT `IdSP`, `IdGRP`, `name`, `type`, `price`, `quantity`, `releaseDate`, `info`, `IMG`
            FROM `sanpham`
            WHERE IdSP = ?
            LIMIT 1";

        $stmt_get_product_by_id = mysqli_prepare($mysqli, $sql_get_product);
        mysqli_stmt_bind_param($stmt_get_product_by_id, 'i', $IdSP);

        // Fetch the product and assign it to the $product variable
        mysqli_stmt_execute($stmt_get_product_by_id);

        $result = mysqli_stmt_get_result($stmt_get_product_by_id);
        $product = mysqli_fetch_assoc($result);

        // Check if product is found
        if ($product) {
            // Access product data, e.g., $product['name']
            $filename = $product['IMG'];
        } else {
            die('Error product not found');
        }
    }

    if (isset($_POST['addSP'])) {
        mysqli_stmt_bind_param($stmt, 'ississs', $IdGRP, $name, $type, $ratio, $releasedDate, $info, $filename);
    } else {
        // Get the lastest import of old product
        $lastestImportOfProductQuery = "SELECT * FROM nhaphang where IdSP = ? LIMIT 1";
        $lastestImportOfProductStmt = $mysqli->prepare($lastestImportOfProductQuery);

        if (!$lastestImportOfProductStmt) {
            throw new Exception("Database preparation error: " . $mysqli->error);
        }

        $lastestImportOfProductStmt->bind_param('i', $IdSP);
        $lastestImportOfProductStmt->execute(); // Just execute, don't chain get_result()
        $lastestImportOfProductResult = $lastestImportOfProductStmt->get_result(); // Get result separately

        $lastestImportPrice = 0;
        if ($lastestImportOfProductResult->num_rows !== 0) {
            $lastestImportOfProductData = $lastestImportOfProductResult->fetch_assoc();
            $lastestImportPrice = $lastestImportOfProductData['ImportPrice'];
        }

        $lastestImportOfProductStmt->close();
        $caculatedPrice = $lastestImportPrice * $ratio / 100;
        mysqli_stmt_bind_param($stmt, 'ississsiii', $IdGRP, $name, $type, $ratio, $releasedDate, $info, $filename, $status, $caculatedPrice, $_GET['IdSP']);
    }


    if (mysqli_stmt_execute($stmt)) {
        if (isset($_POST['addSP'])) {
            header('Location: ../../index.php?action=products');
        } else {
            header("Location: ../../index.php?action=products&IdSP=$IdSP");
        }
        exit();
    } else {
        die('Database error: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
} else {
    die('Invalid request method.');
}
