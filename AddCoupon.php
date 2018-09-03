<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type,Accept, Origin, x-requested-with");

require 'DbClass.php';
include 'class.coupon.php';
$dbclass = new DBClass();
$connection = $dbclass->getConnection();
$no_of_coupons = null;
$length=null;
$Expirydate=null;
$Latitude=null;
$Longitude=null;
$Distance=null;
$Event=null;
if (isset($_POST['EventName'])) {
    $Event=$_POST['EventName'];
}
if (isset($_POST['no_of_coupons'])) {
    $no_of_coupons=$_POST['no_of_coupons'];
}
if (isset($_POST['length'])) {
    $length = $_POST['length'];
}
if (isset($_POST['Expirydate'])) {
    $Expirydate = $_POST['Expirydate'];
}
if (isset($_POST['FareAmount'])) {
    $amount = $_POST['FareAmount'];
}
if (isset($_POST['EventLatitude'])) {
    $Latitude = $_POST['EventLatitude'];
}
if (isset($_POST['EventLogitude'])) {
    $Longitude = $_POST['EventLogitude'];
}
if (isset($_POST['CouponValidDistance'])) {
    $Distance = $_POST['CouponValidDistance'];
}
$option=array();
$option['length']=$length;
$option['Expirydate']=$Expirydate;
$option['Latitude']=$Latitude;
$option['amount']=$amount;
$option['Longitude']=$Longitude;
$option['Distance']=$Distance;
$coupons = coupon::generate_coupons($no_of_coupons,$option);
$LatBoundry=$Latitude  + ($Distance / 6371000) * (180 / M_PI);
$LongBoundry=$Longitude + ($Distance / 6371000) * (180 / M_PI);
foreach ($coupons as $key => $value) {
    $dbclass = new DbClass();
    $connection = $dbclass->getConnection();
    $queryToInsert="INSERT INTO coupons_data(EventName,CouponCode,CouponAmount,CouponExpireOn,Latitude,Longitude,LatBoundry,LongBoundry,ValidCouponDistance) VALUES('$Event','$value','$amount','$Expirydate','$Latitude','$Longitude','$LatBoundry','$LongBoundry','$Distance')";
    //$query = "SELECT Movie_Id, Title, Released_Year, Rating, Genres FROM movie_data WHERE Genres like '%$GenesValue%'";
    $stmt = $connection->prepare($queryToInsert);
    $stmt->execute();
    echo $value."\n ";
}
die();

?>