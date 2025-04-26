<?php

include(__DIR__ . '/../admincp/config/config.php');
class product {
    public $id;
    public $name;
    public $price;
    public $image;
    public $quant;

    public function __construct($id = null,$name = null, $price = null, $image = null, $quant = null) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->quant = $quant;
    }

    public static function getProductById($id) {
        global $mysqli;
        $query = "SELECT IdSP,Name,Price,IMG,Quantity FROM `sanpham` WHERE IdSP= '$id' AND Status=1";
        $result = mysqli_query($mysqli, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return new product(
                $row['IdSP'],
                $row['Name'],
                $row['Price'],
                $row['IMG'],
                $row['Quantity'],

            );
        }
        return null;
    }
}
?>