<?php
require_once("dbconfig.php");
$_POST = JSON_DECODE(file_get_contents("php://input"),true);
//세션 초기화
session_start();
$user_id=$_POST["user_id"];
$user_pw=$_POST["user_pw"];

$sql = "SELECT * FROM account WHERE real_id ='$user_id' AND pw ='$user_pw'";
$res = $db->query($sql);
$row = $res->fetch_array(MYSQLI_ASSOC);
echo $sql;
if($row){
    echo "로그인 성공";
    $_SESSION['userId'] = $user_id;
    // $_SESSION['userPw'] = $user_pw; ==> Security Problem

    // ADD NH-K
    $_SESSION['userKey'] = $row['id'];
    $_SESSION['userNickname'] = $row['nickname'];
    // END

    echo json_encode(true,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
    echo $_SESSION['userId'];
} else {            // 만약 참이 아니면 로그인 실패
   echo json_encode(false,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK); 
   echo "로그인실패";
}
 mysqli_close($db);
 ?>