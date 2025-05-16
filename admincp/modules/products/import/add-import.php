<?php
// config.php handles database connection and configuration
require_once('./import-handler.php');
require_once('../../../../utils.php');

// Main execution flow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create handler instance
    $importHandler = new ImportHandler($mysqli);

    // Extract form data from $_POST
    $importData = [
        'IdSP' => $_POST['IdSP'] ?? '',
        'ImportPrice' => $_POST['ImportPrice'] ?? '',
        'ImportQuantity' => $_POST['ImportQuantity'] ?? '',
        'ImportDate' => $_POST['ImportDate'] ?? '',
    ];

    if (isset($_POST['add'])) {
        // Validate data
        if ($importHandler->validateData($importData)) {
            // Add customer
            if ($importHandler->addOne($importData)) {
                // Success - redirect to customers list
                responseJson([
                    'success' => true,
                    'data' => $importData,
                ]);
            } else {
                // Database error
                $errors = $importHandler->getErrors();
                responseJson([
                    'success' => false,
                    'messages' => $errors
                ]);
            }
        } else {
            // Validation error
            $errors = $importHandler->getErrors();
            responseJson([
                'success' => false,
                'messages' => $errors
            ]);
        }
    } else {
        responseJson([
            'success' => false,
            'messages' => 'Invalid request: Missing required parameter'
        ]);
    }
} else {
    // Not a POST request
    responseJson([
        'success' => false,
        'formErrors' => 'Invalid request method. Please use the form.'
    ]);
}
