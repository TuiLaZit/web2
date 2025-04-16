<?php
// config.php handles database connection and configuration
require_once('../../config/config.php');

/**
 * Customer Handler Class
 * Handles customer operations following SOLID principles
 */
class CustomerHandler
{
    private $mysqli;
    private $errors = [];
    private $encryptPassword;

    /**
     * Constructor - initialize with database connection
     */
    public function __construct($mysqli, $key)
    {
        $this->mysqli = $mysqli;

        $this->encryptPassword = $key;
    }

    /**
     * Validate form data
     * @param array $data Customer data to validate
     * @return bool True if valid, false otherwise
     */
    public function validateData($data)
    {
        // Required fields
        $requiredFields = ['account', 'email', 'name', 'pNumber', 'address'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[$field] = "Trường $field không được để trống";
                return false;
            }
        }

        // // Validate email format
        // if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        //     $this->errors['email'] = "Email không hợp lệ";
        //     return false;
        // }

        // // Validate phone number (assuming Vietnamese phone number format)
        // if (!preg_match('/^(0[3|5|7|8|9])+([0-9]{9})$/', $data['pNumber'])) {
        //     $this->errors['pNumber'] = "Số điện thoại không hợp lệ";
        //     return false;
        // }

        return true;
    }

    /**
     * Add new customer
     * @param array $data Customer data
     * @return bool Success status
     */
    public function addCustomer($data)
    {
        // Prepare query with named parameters for better readability
        $query = "INSERT INTO khachhang (Account, Email, Password, Name, PNumber, Provinces, Ward, District , AddressLine, Status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }

        if ($data['register']) {
            $hashedPassword = openssl_encrypt($data['password'], 'AES-128-ECB', $this->encryptPassword);
        } else {
            $hashedPassword = openssl_encrypt("NewCustomer", 'AES-128-ECB', $this->encryptPassword);
        }


        // Bind parameters
        $stmt->bind_param(
            'sssssssssi',
            $data['account'],
            $data['email'],
            $hashedPassword,
            $data['name'],
            $data['pNumber'],
            $data['province'],
            $data['ward'],
            $data['district'],
            $data['address'],
            $data['status'],
        );

        // Execute query
        $result = $stmt->execute();

        if (!$result) {
            $this->errors['db'] = "Database execution error: " . $stmt->error;
        }

        $stmt->close();
        return $result;
    }

    public function editCustomer($data, $idKH)
    {
        // Prepare query with named parameters for better readability
        $query = "UPDATE khachhang SET Account = ?, Email = ?, Name = ?, PNumber = ?, AddressLine = ?, Provinces = ?, Ward = ?, District = ?, Status = ?
            WHERE idKH = ?     
        ";

        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }


        // Bind parameters
        $stmt->bind_param(
            'ssssssssii',
            $data['account'],
            $data['email'],
            $data['name'],
            $data['pNumber'],
            $data['address'],
            $data['province'],
            $data['ward'],
            $data['district'],
            $data['status'],
            $idKH,
        );

        // Execute query
        $result = $stmt->execute();

        if (!$result) {
            $this->errors['db'] = "Database execution error: " . $stmt->error;
        }

        $stmt->close();
        return $result;
    }

    public function updateStatus($IdKH, $status)
    {
        // Prepare query with named parameters for better readability
        $query = "UPDATE khachhang SET Status = ?
                WHERE idKH = ?     
            ";

        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }


        // Bind parameters
        $stmt->bind_param(
            'ii',
            $status,
            $IdKH,
        );

        // Execute query
        $result = $stmt->execute();

        if (!$result) {
            $this->errors['db'] = "Database execution error: " . $stmt->error;
        }

        $stmt->close();
        return $result;
    }

    /**
     * Get validation errors
     * @return array Errors array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Redirect to a specific page
     * @param string $location Redirect location
     */
    public function redirect($location)
    {
        header("Location: $location");
        exit();
    }
}

// Main execution flow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create handler instance
    $customerHandler = new CustomerHandler($mysqli, $key);

    // Extract form data from $_POST
    $customerData = [
        'account' => $_POST['account'] ?? '',
        'email' => $_POST['email'] ?? '',
        'name' => $_POST['name'] ?? '',
        'pNumber' => $_POST['pNumber'] ?? '',
        'province' => $_POST['province'] ?? '',
        'ward' => $_POST['ward'] ?? '',
        'district' => $_POST['district'] ?? '',
        'address' => $_POST['address'] ?? '',
        'status' => $_POST['status'] ?? 1,
        'register' => $_POST['register'] === 'true' ? true : false,
        'password' => $_POST['password'] ?? '',
    ];

    // Check if we're adding a customer (form has addKH field)
    if (isset($_POST['addKH'])) {
        $redirectPath = '../../index.php';
        if ($customerData['register']) {
            $redirectPath = '../../../register.php';
        }

        // Validate data
        if ($customerHandler->validateData($customerData)) {
            // Add customer
            if ($customerHandler->addCustomer($customerData)) {
                // Success - redirect to customers list
                if ($customerData['register']) {
                    $redirectPath = '../../../login.php';
                }
                $customerHandler->redirect("$redirectPath?action=customers&status=success");
            } else {
                // Database error
                $errors = $customerHandler->getErrors();
                // Store errors in session and redirect back with error message
                session_start();
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_data'] = $customerData; // Keep form data for repopulating
                // Nếu trùng các dữ liệu unique trong db
                if (str_contains($errors['db'], "Duplicate entry")) {
                    if (str_contains($errors['db'], "ACCOUNT")) {
                        $errors['db'] = 'Tên đăng nhập này đã tồn tại !';
                    } else if (str_contains($errors['db'], "Email_UNIQUE")) {
                        $errors['db'] = 'Email này đã được đăng ký !';
                    } else if (str_contains($errors['db'], "PNumber")) {
                        $errors['db'] = 'Số điện thoại này đã được đăng ký !';
                    }

                    $_SESSION['form_errors'] = $errors;
                }

                $customerHandler->redirect("$redirectPath?action=customers&status=error&adding=true");
            }
        } else {
            // Validation error
            $errors = $customerHandler->getErrors();

            // Store errors in session and redirect back with error message
            session_start();
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $customerData; // Keep form data for repopulating
            $customerHandler->redirect("$redirectPath?action=customers&status=validation_error&adding=true");
        }
    } else if (isset($_POST['updateKH']) && isset($_GET['IdKH'])) {
        $idKH = $_GET['IdKH'];
        // Validate data
        if ($customerHandler->validateData($customerData)) {
            // Add customer
            if ($customerHandler->editCustomer($customerData, $idKH)) {
                // Success - redirect to customers list
                $customerHandler->redirect("../../index.php?action=customers&status=success&IdKH=$idKH");
            } else {
                // Database error
                $errors = $customerHandler->getErrors();
                // Store errors in session and redirect back with error message
                session_start();
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_data'] = $customerData; // Keep form data for repopulating
                // Nếu trùng các dữ liệu unique trong db
                if (str_contains($errors['db'], "Duplicate entry")) {
                    if (str_contains($errors['db'], "ACCOUNT")) {
                        $errors['db'] = 'Tên đăng nhập này đã tồn tại !';
                    } else if (str_contains($errors['db'], "Email_UNIQUE")) {
                        $errors['db'] = 'Email này đã được đăng ký !';
                    } else if (str_contains($errors['db'], "PNumber")) {
                        $errors['db'] = 'Số điện thoại này đã được đăng ký !';
                    }

                    $_SESSION['form_errors'] = $errors;
                }

                $customerHandler->redirect("../../index.php?action=customers&status=error&editing=true&IdKH=$idKH");
            }
        } else {
            // Validation error
            $errors = $customerHandler->getErrors();

            // Store errors in session and redirect back with error message
            session_start();
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $customerData; // Keep form data for repopulating
            $customerHandler->redirect('../../index.php?action=customers&status=validation_error&editing=true&IdKH=15');
        }
    } else if (isset($_POST['updateStatus']) && isset($_POST['IdKH']) && isset($_POST['status'])) {
        $IdKH = $_POST['IdKH'];
        $status = $_POST['status'];

        if ($customerHandler->updateStatus($IdKH, $status)) {
            // Success - redirect to customers list
            echo true;
        } else {
            // Database error
            $errors = $customerHandler->getErrors();
            echo false;
        }
    } else {
        // Invalid request
        die('Invalid request: Missing required parameter');
    }
} else {
    // Not a POST request
    die('Invalid request method. Please use the form.');
}
