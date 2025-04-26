<?php

include(__DIR__ .'/../admincp/config/config.php');
require_once(__DIR__. '/product_container.php');
class group {
    public $id;
    public $name;
    public $img;
    public $company;
    public $info;

    public function __construct($id = null, $name=null,$img=null,$company=null,$info=null) {
        $this->id = $id;
        $this->name = $name;
        $this->img= $img;
        $this->company= $company;
        $this->info= $info;
    }

    public static function getGroup($id) {
        global $mysqli;
        $querygroup = "SELECT IdGRP, Name,Company,Info,IMG FROM nhom WHERE IdGRP='$id'";
        
        $resultgroup = mysqli_query($mysqli, $querygroup);
        if($resultgroup && mysqli_num_rows($resultgroup)){
            while($row = mysqli_fetch_assoc($resultgroup)){
                $group = new self($row['IdGRP'], $row['Name'],$row['IMG'],$row['Company'],$row['Info']);
            }
        }
        return $group;
    }
}
?>

