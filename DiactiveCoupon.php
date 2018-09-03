<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type,Accept, Origin, x-requested-with");
$CouponId=null;
if (isset($_POST['CouponId'])) {
    $CouponId=$_POST['CouponId'];
}
require 'DbClass.php';
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
$QueryToUpdate="UPDATE coupons_data set IsActive=0 where CouponId=$CouponId;";
$stmt = $connection->prepare($QueryToUpdate);
$stmt->execute();
echo "Promo Code Deactivated";
die();

?>