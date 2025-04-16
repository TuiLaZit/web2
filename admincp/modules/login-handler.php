<?php
// config.php handles database connection and configuration
require_once('../config/config.php');

/**
 * Login Handler Class
 * Handles user authentication following SOLID principles
 */
class LoginCustomer
{
    private $mysqli;
    private $encryptPassword;
    private $errors = [];
    private $loginAttempts = 0;
    private $maxLoginAttempts = 5;

    /**
     * Constructor - initialize with database connection
     */
    public function __construct($mysqli, $key)
    {
        $this->mysqli = $mysqli;
        $this->encryptPassword = $key;
        session_start();
    }

    /**
     * Validate login data
     * @param array $data Login data to validate
     * @return bool True if valid, false otherwise
     */
    public function validateData($data)
    {
        // Required fields
        $requiredFields = ['username', 'password'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[$field] = "Trường $field không được để trống";
                return false;
            }
        }

        return true;
    }

    /**
     * Authenticate user
     * @param array $data Login credentials
     * @return bool Success status
     */
    public function authenticateUser($data)
    {
        // Prepare query to find user by account/email
        $query = "SELECT * FROM nhanvien 
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

        $user['isAdmin'] = true;

        // Success - store user in session
        $_SESSION['user'] = $user;
        return true;
    }


    /**
     * Log out user
     */
    public function logoutUser()
    {
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        return true;
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
    $loginHandler = new LoginCustomer($mysqli, $key);

    // Check if we're handling login
    if (isset($_POST['login'])) {
        // Extract form data from $_POST
        $loginData = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
        ];

        // Validate data
        if ($loginHandler->validateData($loginData)) {
            // Authenticate user
            if ($loginHandler->authenticateUser($loginData)) {
                // Redirect based on user role if needed
                $_SESSION['user'] = ['username' => $loginData['username']]; // Keep username for repopulating
                // For now just redirect to home page
                $loginHandler->redirect('../index.php?status=login_success');
            } else {
                // Authentication error
                $errors = $loginHandler->getErrors();

                // Store errors in session and redirect back with error message
                session_start();
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
    }
    // Check if we're handling logout
    else if (isset($_POST['logout'])) {
        if ($loginHandler->logoutUser()) {
            $loginHandler->redirect('../login.php?action=login&status=logout_success');
        } else {
            $loginHandler->redirect('../login.php?status=logout_error');
        }
    } else {
        // Invalid request
        die('Invalid request: Missing required parameter');
    }
} else {
    // Not a POST request
    die('Invalid request method. Please use the form.');
}
