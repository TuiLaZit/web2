<?php
// config.php handles database connection and configuration
require_once('../../../config/config.php');

class ImportHandler
{
    private $mysqli;
    private $errors = [];

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function validateData($data)
    {
        // Required fields
        $requiredFields = ['IdSP', 'ImportPrice', 'ImportQuantity', 'ImportDate'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->errors[$field] = "Trường $field không được để trống";
                return false;
            }
        }

        return true;
    }

    public function findProductById($idSP)
    {
        $getQuery = "SELECT * FROM sanpham WHERE IdSP = ?";
        $getStmt = $this->mysqli->prepare($getQuery);

        if (!$getStmt) {
            $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
            throw new Exception($this->errors['db']);
        }

        $getStmt->bind_param('i', $idSP);
        $getStmt->execute();
        $getResult = $getStmt->get_result();
        $getStmt->close();

        if ($getResult->num_rows === 0) {
            $this->errors['db'] = "Product record not found";
            throw new Exception($this->errors['db']);
        }


        return $getResult->fetch_assoc();
    }


    public function addOne($data)
    {
        // Start transaction to ensure data consistency
        $this->mysqli->begin_transaction();

        try {
            // Prepare query with named parameters for better readability
            $productData = $this->findProductById($data['IdSP']);
            $query = "INSERT INTO nhaphang (IdSP, ImportPrice, ImportQuantity, ImportDate, ProductName) 
                 VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->mysqli->prepare($query);

            if (!$stmt) {
                $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            // Bind parameters
            $stmt->bind_param(
                'iiiss',
                $data['IdSP'],
                $data['ImportPrice'],
                $data['ImportQuantity'],
                $data['ImportDate'],
                $productData['Name']
            );

            // Execute query
            $result = $stmt->execute();

            if (!$result) {
                $this->errors['db'] = "Database execution error: " . $stmt->error;
                throw new Exception($this->errors['db']);
            }

            $stmt->close();

            // After successful insert, update quantity in sanpham table
            $productUpdatedData = $this->findProductById($data['IdSP']);
            $updateQuery = "UPDATE sanpham SET Quantity = Quantity + ?, Price = ?, Status = 1 WHERE IdSP = ?";
            $updateStmt = $this->mysqli->prepare($updateQuery);

            if (!$updateStmt) {
                $this->errors['db'] = "Database preparation error on quantity update: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            $calculatedPrice = $data['ImportPrice'] * $productUpdatedData['Ratio'] / 100;

            // Bind parameters for update query
            $updateStmt->bind_param('iii', $data['ImportQuantity'], $calculatedPrice, $data['IdSP']);

            // Execute update query
            $updateResult = $updateStmt->execute();

            if (!$updateResult) {
                $this->errors['db'] = "Database execution error on quantity update: " . $updateStmt->error;
                throw new Exception($this->errors['db']);
            }

            $updateStmt->close();

            // If everything is successful, commit the transaction
            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            // If any errors occurred, roll back changes
            $this->mysqli->rollback();
            return false;
        }
    }

    public function editById($data, $idNhapHang)
    {
        // Start transaction
        $this->mysqli->begin_transaction();

        try {
            // First, get the current import record to know the old quantity
            $getQuery = "SELECT IdSP, ImportQuantity FROM nhapHang WHERE idNhapHang = ?";
            $getStmt = $this->mysqli->prepare($getQuery);

            if (!$getStmt) {
                $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            $getStmt->bind_param('i', $idNhapHang);
            $getStmt->execute();
            $getResult = $getStmt->get_result();

            if ($getResult->num_rows === 0) {
                $this->errors['db'] = "Import record not found";
                throw new Exception($this->errors['db']);
            }

            $oldImport = $getResult->fetch_assoc();
            $oldIdSP = $oldImport['IdSP'];
            $oldQuantity = $oldImport['ImportQuantity'];
            $getStmt->close();

            $productData = $this->findProductById($data['IdSP']);
            // Update the import record
            $updateQuery = "UPDATE nhapHang SET IdSP = ?, ImportPrice = ?, ImportQuantity = ?, ImportDate = ?, ProductName = ?
                        WHERE idNhapHang = ?";

            $updateStmt = $this->mysqli->prepare($updateQuery);

            if (!$updateStmt) {
                $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            // Bind parameters
            $updateStmt->bind_param(
                'iiissi',
                $data['IdSP'],
                $data['ImportPrice'],
                $data['ImportQuantity'],
                $data['ImportDate'],
                $productData['Name'],
                $idNhapHang
            );

            // Execute query
            $updateResult = $updateStmt->execute();

            if (!$updateResult) {
                $this->errors['db'] = "Database execution error: " . $updateStmt->error;
                throw new Exception($this->errors['db']);
            }

            $updateStmt->close();

            // Update product quantity in sanpham table

            // If the product ID changed, we need to update both the old and new product
            if ($oldIdSP != $data['IdSP']) {
                // Get the lastest import of old product
                $lastestImportOfProductQuery = "SELECT * FROM nhaphang where IdSP = ? LIMIT 1";
                $lastestImportOfProductStmt = $this->mysqli->prepare($lastestImportOfProductQuery);

                if (!$lastestImportOfProductStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $lastestImportOfProductStmt->bind_param('i', $oldIdSP);
                $lastestImportOfProductStmt->execute(); // Just execute, don't chain get_result()
                $lastestImportOfProductResult = $lastestImportOfProductStmt->get_result(); // Get result separately

                $lastestImportPrice = 0;
                if ($lastestImportOfProductResult->num_rows !== 0) {
                    $lastestImportOfProductData = $lastestImportOfProductResult->fetch_assoc();
                    $lastestImportPrice = $lastestImportOfProductData['ImportPrice'];
                }

                $lastestImportOfProductStmt->close();

                // Subtract old quantity from old product
                $productUpdatingData = $this->findProductById($oldIdSP);
                $status = 1;
                if ($productUpdatingData['Quantity'] - $oldQuantity <= 0) {
                    $status = 2;
                }
                $updateOldQuery = "UPDATE sanpham SET Quantity = Quantity - ?, Price = ?, Status = $status WHERE IdSP = ?";
                $updateOldStmt = $this->mysqli->prepare($updateOldQuery);

                if (!$updateOldStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $calculatedPrice = $lastestImportPrice * $productUpdatingData['Ratio'] / 100;
                $updateOldStmt->bind_param('iii', $oldQuantity, $calculatedPrice, $oldIdSP);
                $updateOldResult = $updateOldStmt->execute();

                if (!$updateOldResult) {
                    $this->errors['db'] = "Database execution error: " . $updateOldStmt->error;
                    throw new Exception($this->errors['db']);
                }

                $updateOldStmt->close();

                // Add new quantity to new product
                $productUpdatingData = $this->findProductById($data['IdSP']);
                $updateNewQuery = "UPDATE sanpham SET Quantity = Quantity + ?, Price = ?, Status = 1 WHERE IdSP = ?";
                $updateNewStmt = $this->mysqli->prepare($updateNewQuery);

                if (!$updateNewStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $calculatedPrice = $data['ImportPrice'] * $productUpdatingData['Ratio'] / 100;
                $updateNewStmt->bind_param('iii', $data['ImportQuantity'], $calculatedPrice, $data['IdSP']);
                $updateNewResult = $updateNewStmt->execute();

                if (!$updateNewResult) {
                    $this->errors['db'] = "Database execution error: " . $updateNewStmt->error;
                    throw new Exception($this->errors['db']);
                }

                $updateNewStmt->close();
            } else {
                // Same product, just update quantity or importPrice
                $productUpdatingData = $this->findProductById($data['IdSP']);
                $quantityDifference = $data['ImportQuantity'] - $oldQuantity;
                $updateQuantityQuery = "UPDATE sanpham SET Quantity = Quantity + ?, Price = ? WHERE IdSP = ?";
                $updateQuantityStmt = $this->mysqli->prepare($updateQuantityQuery);

                if (!$updateQuantityStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $calculatedPrice = $data['ImportPrice'] * $productUpdatingData['Ratio'] / 100;
                $updateQuantityStmt->bind_param('iii', $quantityDifference, $calculatedPrice, $data['IdSP']);
                $updateQuantityResult = $updateQuantityStmt->execute();

                if (!$updateQuantityResult) {
                    $this->errors['db'] = "Database execution error: " . $updateQuantityStmt->error;
                    throw new Exception($this->errors['db']);
                }

                $updateQuantityStmt->close();
            }

            // If everything succeeded, commit the transaction
            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction if there was an error
            $this->mysqli->rollback();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
