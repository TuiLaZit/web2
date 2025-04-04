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
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $releasedDate = $_POST['releaseDate'] ?? '2025-01-01';
    $info = $_POST['info'] ?? '';
    $IMG = $_POST['IMG'] ?? '';
    $status = $_POST['status'] ?? '';

    // Printing the values
    echo "IdGRP: " . $IdGRP . "<br>";
    echo "Name: " . $name . "<br>";
    echo "Type: " . $type . "<br>";
    echo "Price: " . $price . "<br>";
    echo "Quantity: " . $quantity . "<br>";
    echo "Released Date: " . $releasedDate . "<br>";
    echo "Info: " . $info . "<br>";
    echo "Image: " . $IMG . "<br>";

    if (isset($_POST['addSP'])) {
        $query = "INSERT INTO sanpham (IdGRP, name, type, price, quantity, releaseDate, info, IMG, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } elseif (isset($_POST['updateSP']) && isset($_GET['IdSP'])) {
        $IdSP = $_GET['IdSP'];
        $query = "UPDATE sanpham SET IdGRP = ?, name = ?, type = ?, price = ?, quantity = ?, releaseDate = ?, info = ?, IMG = ?, status = ?
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
    if (isset($_FILES["IMG"]["tmp_name"])) {
        move_uploaded_file($_FILES["IMG"]["tmp_name"], $target_file);
        $filename = basename($_FILES["IMG"]["name"]) ?? "";

        if ($filename === "") {
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
    }

    if (isset($_POST['addSP'])) {
        mysqli_stmt_bind_param($stmt, 'issiisssi', $IdGRP, $name, $type, $price, $quantity, $releasedDate, $info, $filename, $status);
    } else {
        mysqli_stmt_bind_param($stmt, 'issiisssii', $IdGRP, $name, $type, $price, $quantity, $releasedDate, $info, $filename, $status, $_GET['IdSP']);
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
