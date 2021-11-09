<?php 
//Written by NamHyeok Kim

session_start();
header("Content-Type:application/json");

$resBody = ['isLogin'=>false, 'id'=>"", 'nickname'=>""];

if($_SESSION['user_id'] && $_SESSION['user_nickname']) {
    $resBody['isLogin'] = true;
    $resBody['id'] = $_SESSION['user_id'];
    $resBody['nickname'] = $_SESSION['user_nickname'];
}

echo json_encode($resBody, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);

?>
