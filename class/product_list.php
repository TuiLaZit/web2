<?php

include(__DIR__ .'/../admincp/config/config.php');
require_once('product_container.php');
class group {
    public $id;
    public $name;

    public function __construct($id = null, $name=null) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function getGroup($id) {
        global $mysqli;
        $querygroup = "SELECT IdGRP, Name FROM nhom WHERE IdGRP='$id'";
        
        $resultgroup = mysqli_query($mysqli, $querygroup);
        if($resultgroup && mysqli_num_rows($resultgroup)){
            while($row = mysqli_fetch_assoc($resultgroup)){
                $group = new self($row['IdGRP'], $row['Name']);
            }
        }
        return $group;
    }

}
?>

