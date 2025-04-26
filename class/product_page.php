<?php

include('../admincp/config/config.php');
class productPage {
    public $id;
    public $name;
    public $price;
    public $image;
    public $grpname;
    public $type;
    public $quant;
    public $info;
    public $releaseDate;

    public function __construct($id = null, $name = null, $price = null, $image = null, $grpname = null, $type = null, $quant = null, $info = null, $releaseDate = null,) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->grpname = $grpname;
        $this->type = $type;
        $this->quant = $quant;
        $this->info = $info;
        $this->releaseDate = $releaseDate;
    }

    public static function getProductById($id) {
        global $mysqli;
        $query = "SELECT SP.IdSP,SP.Name,SP.Type,SP.Price,SP.IMG,SP.Quantity,SP.Info,SP.ReleaseDate,N.Name as grpname 
                FROM `sanpham` as SP JOIN `nhom` AS N ON SP.IdGRP = N.IdGRP 
                WHERE  SP.IdSP= '$id' AND SP.Status=1 GROUP BY SP.IdGRP = N.IdGRP";
        $result = mysqli_query($mysqli, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return new productPage(
                $row['IdSP'],
                $row['Name'],
                $row['Price'],
                $row['IMG'],
                $row['grpname'],
                $row['Type'],
                $row['Quantity'],
                $row['Info'],
                $row['ReleaseDate']
            );
        }
        return null;
    }
}
?>