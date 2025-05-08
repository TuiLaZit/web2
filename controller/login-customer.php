<?php
// config.php handles database connection and configuration
require_once('../admincp/config/config.php');
require_once('../utils.php');

class LoginCustomer
{
    private $mysqli;
    private $encryptPassword;
    private $errors = [];

    /**
     * Constructor - initialize with database connection
     */
    public function __construct($mysqli, $key)
    {
        $this->mysqli = $mysqli;
        $this->encryptPassword = $key;
        session_start();
    }

    public function validateData($data, $requiredFields)
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[$field] = "Trường $field không được để trống";
                return false;
            }
        }

        return true;
    }

    public function authenticateUser($data)
    {
        // Prepare query to find user by account/email
        $query = "SELECT * FROM khachhang 
                  WHERE Account = ? LIMIT 1";

        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }

        // Bind parameters
        $stmt->bind_param(
            's',
            $data['username'],
        );

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $this->errors['login'] = "Tên đăng nhập hoặc mật khẩu không chính xác";
            return false;
        }

        $user = $result->fetch_assoc();
        $stmt->close();


        $passcrypted = openssl_encrypt($data['password'], 'AES-128-ECB', $this->encryptPassword);
        // Verify password
        if ($passcrypted !== $user['Password']) {
            $this->errors['login'] = "Tên đăng nhập hoặc mật khẩu không chính xác";
            return false;
        }

        // Check if account is active
        if ($user['Status'] != 1) {
            $this->errors['status'] = "Tài khoản của bạn đã bị vô hiệu hóa";
            return false;
        }
        $user['isAdmin'] = false;
        // Success - store user in session
        $_SESSION['user'] = $user;
        print_r($user);
        return true;
    }

    public function logoutUser()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        return true;
    }

    public function updatePassword($data)
    {
        // Prepare query to find user by account/email
        $query = "SELECT * FROM khachhang 
                    WHERE PNumber = ? LIMIT 1";

        $stmt = $this->mysqli->prepare($query);

        if (!$stmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }

        // Bind parameters
        $stmt->bind_param(
            's',
            $data['Phone'],
        );

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $this->errors['resetPassword'] = "Số điện thoại này chưa được đăng ký!";
            return false;
        }

        $stmt->close();

        $updatePasswordQuery = "UPDATE khachhang SET Password = ? WHERE PNumber = ?";
        $updatePasswordStmt = $this->mysqli->prepare($updatePasswordQuery);

        if (!$updatePasswordStmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            return false;
        }

        $passcrypted = openssl_encrypt($data['Password'], 'AES-128-ECB', $this->encryptPassword);
        $updatePasswordStmt->bind_param('ss', $passcrypted, $data['Phone']);
        $updatePasswordResult = $updatePasswordStmt->execute();

        if (!$updatePasswordResult) {
            $this->errors['db'] = "Database execution error: " . $updatePasswordStmt->error;
            return false;
        }

        $updatePasswordStmt->close();
        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function redirect($location)
    {
        header("Location: $location");
        exit();
    }
}

// Create handler instance
$loginHandler = new LoginCustomer($mysqli, $key);

// Main execution flow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if we're handling login
    if (isset($_POST['login'])) {
        // Extract form data from $_POST
        $loginData = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];

        $requiredFields = ['username', 'password'];

        // Validate data
        if ($loginHandler->validateData($loginData, $requiredFields)) {
            // Authenticate user
            if ($loginHandler->authenticateUser($loginData)) {
                // Redirect based on user role if needed
                // For now just redirect to home page
                $loginHandler->redirect('../index.php?status=login_success');
            } else {
                // Authentication error
                $errors = $loginHandler->getErrors();

                // Store errors in session and redirect back with error message
                $_SESSION['login_errors'] = $errors;
                $_SESSION['login_data'] = ['username' => $loginData['username']]; // Keep username for repopulating
                $loginHandler->redirect('../login.php?action=login&status=error');
            }
        } else {
            // Validation error
            $errors = $loginHandler->getErrors();

            // Store errors in session and redirect back with error message
            session_start();
            $_SESSION['login_errors'] = $errors;
            $_SESSION['login_data'] = ['username' => $loginData['username']]; // Keep username for repopulating
            $loginHandler->redirect('../login.php?action=login&status=validation_error');
        }
    } else if (isset($_GET['reset-password'])) {
        $resetPasswordData = [
            'Phone' => $_POST['Phone'] ?? '',
            'Password' => $_POST['Password'] ?? '',
            'RePassword' => $_POST['RePassword'] ?? '',
        ];

        $requiredFields = ['Phone', 'Password', 'RePassword'];

        if ($loginHandler->validateData($resetPasswordData, $requiredFields)) {
            if ($resetPasswordData['Password'] !== $resetPasswordData['RePassword']) {
                return responseJson([
                    'success' => false,
                    'messages' => [
                        'reset-password' => "Mật khẩu không trùng khớp!"
                    ]
                ]);
            }

            // Authenticate user
            if ($loginHandler->updatePassword($resetPasswordData)) {
                // Redirect based on user role if needed
                return responseJson([
                    'success' => true,
                ]);
            } else {
                // Authentication error
                $errors = $loginHandler->getErrors();
                return responseJson([
                    'success' => false,
                    'messages' => $errors
                ]);
            }
        } else {
            // Validation error
            $errors = $loginHandler->getErrors();
            return responseJson([
                'success' => false,
                'messages' => $errors
            ]);
        }
    }
} else if (isset($_GET['logout'])) {
    if ($loginHandler->logoutUser()) {
        $loginHandler->redirect('../login.php?action=login&status=logout_success');
    } else {
        $loginHandler->redirect('../login.php?status=logout_error');
    }
} else {
    // Invalid request
    die('Invalid request: Missing required parameter');
}
