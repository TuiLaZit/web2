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


    public function addOne($data)
    {
        // Start transaction to ensure data consistency
        $this->mysqli->begin_transaction();

        try {
            // Prepare query with named parameters for better readability
            $query = "INSERT INTO nhaphang (IdSP, ImportPrice, ImportQuantity, ImportDate) 
                 VALUES (?, ?, ?, ?)";

            $stmt = $this->mysqli->prepare($query);

            if (!$stmt) {
                $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            // Bind parameters
            $stmt->bind_param(
                'iiis',
                $data['IdSP'],
                $data['ImportPrice'],
                $data['ImportQuantity'],
                $data['ImportDate']
            );

            // Execute query
            $result = $stmt->execute();

            if (!$result) {
                $this->errors['db'] = "Database execution error: " . $stmt->error;
                throw new Exception($this->errors['db']);
            }

            $stmt->close();

            // After successful insert, update quantity in sanpham table
            $updateQuery = "UPDATE sanpham SET Quantity = Quantity + ? WHERE IdSP = ?";
            $updateStmt = $this->mysqli->prepare($updateQuery);

            if (!$updateStmt) {
                $this->errors['db'] = "Database preparation error on quantity update: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            // Bind parameters for update query
            $updateStmt->bind_param('ii', $data['ImportQuantity'], $data['IdSP']);

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

            // Update the import record
            $updateQuery = "UPDATE nhapHang SET IdSP = ?, ImportPrice = ?, ImportQuantity = ?, ImportDate = ?
                        WHERE idNhapHang = ?";

            $updateStmt = $this->mysqli->prepare($updateQuery);

            if (!$updateStmt) {
                $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                throw new Exception($this->errors['db']);
            }

            // Bind parameters
            $updateStmt->bind_param(
                'iiisi',
                $data['IdSP'],
                $data['ImportPrice'],
                $data['ImportQuantity'],
                $data['ImportDate'],
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
                // Subtract old quantity from old product
                $updateOldQuery = "UPDATE sanpham SET Quantity = Quantity - ? WHERE IdSP = ?";
                $updateOldStmt = $this->mysqli->prepare($updateOldQuery);

                if (!$updateOldStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $updateOldStmt->bind_param('ii', $oldQuantity, $oldIdSP);
                $updateOldResult = $updateOldStmt->execute();

                if (!$updateOldResult) {
                    $this->errors['db'] = "Database execution error: " . $updateOldStmt->error;
                    throw new Exception($this->errors['db']);
                }

                $updateOldStmt->close();

                // Add new quantity to new product
                $updateNewQuery = "UPDATE sanpham SET Quantity = Quantity + ? WHERE IdSP = ?";
                $updateNewStmt = $this->mysqli->prepare($updateNewQuery);

                if (!$updateNewStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $updateNewStmt->bind_param('ii', $data['ImportQuantity'], $data['IdSP']);
                $updateNewResult = $updateNewStmt->execute();

                if (!$updateNewResult) {
                    $this->errors['db'] = "Database execution error: " . $updateNewStmt->error;
                    throw new Exception($this->errors['db']);
                }

                $updateNewStmt->close();
            } else {
                // Same product, just update the difference in quantity
                $quantityDifference = $data['ImportQuantity'] - $oldQuantity;
                $updateQuantityQuery = "UPDATE sanpham SET Quantity = Quantity + ? WHERE IdSP = ?";
                $updateQuantityStmt = $this->mysqli->prepare($updateQuantityQuery);

                if (!$updateQuantityStmt) {
                    $this->errors['db'] = "Database preparation error: " . $this->mysqli->error;
                    throw new Exception($this->errors['db']);
                }

                $updateQuantityStmt->bind_param('ii', $quantityDifference, $data['IdSP']);
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
