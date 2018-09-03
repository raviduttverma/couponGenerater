<?php
error_reporting(1);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type,Accept, Origin, x-requested-with");
require 'DbClass.php';
        $dbclass = new DbClass();
        $connection = $dbclass->getConnection();
        $queryToInsert="SELECT * from coupons_data";
        $stmt = $connection->prepare($queryToInsert);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count > 0){
            $Coupons = array();
            $Coupons["body"] = array();
            $Coupons["count"] = $count;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $p  = array(
                    "CouponId" =>$CouponId,
                    "EventName" =>$EventName,
                    "CouponCode" => $CouponCode,
                    "CouponAmount" => $CouponAmount,
                    "CouponExpireOn" => $CouponExpireOn,
                    "ValidCouponDistance" => $ValidCouponDistance,
                    "Status" => $IsActive
                );
                array_push($Coupons["body"], $p);
            }
            echo json_encode($Coupons);
        }

?>