<?php
include('../../config/config.php');

$IdNV = $_POST['ID'];
$AccNV = $_POST['Account'];
$PassNV = $_POST['Password'];
$NameNV = $_POST['Name'];
$PnNV = $_POST['Phone'];
$AddNV = $_POST['Address'];
$PosNV = $_POST['Position'];
$Passcrypted = openssl_encrypt($PassNV,'AES-128-ECB',$key);
if(isset($_POST['AddNV'])){
    $sql_them = "INSERT INTO `nhanvien` (`IdNV`, `Account`, `Password`, `Name`, `PNumber`, `Address`, `Status`, `IdPos`) VALUES ('".$IdNV."', '".$AccNV."', '".$Passcrypted."', '".$NameNV."', '".$PnNV."', '".$AddNV."', '1', '".$PosNV."')";
    mysqli_query($mysqli,$sql_them);
    header('Location: ../../index.php?action=staff');
} elseif(isset($_POST['FixNV'])){
    $sql_sua_nhanvien = "UPDATE `nhanvien` SET IdNV ='".$IdNV."',Account= '".$AccNV."',Password = '".$Passcrypted."',Name = '".$NameNV."', PNumber = '".$PnNV."', Address = '".$AddNV."', IdPos= '".$PosNV."' Where IdNV='$_GET[IdNV]'";
    mysqli_query($mysqli,$sql_sua_nhanvien);
    header('Location: ../../index.php?action=staff');
}

?>