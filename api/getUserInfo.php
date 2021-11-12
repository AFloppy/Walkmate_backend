<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$resBody = array('isSuccess' => false);

if(isset($_SESSION['user_id'])) {
    $sql = "SELECT ID, nickname, address, gender, age FROM user WHERE ID=?";
    $query = $database -> prepare($sql);
    $query -> execute(array($_SESSION['user_id']));

    if($query->rowCount() === 1) {
        $userInfo = $query->fetch(PDO::FETCH_ASSOC);
        $resBody['isSuccess'] = true;
        $resBody['userInfo'] = $userInfo;
    } else {
        $resBody['reason'] = "계정 조회 오류";
    }
} else {
    $resBody['reason'] = "세션 오류";
}
echo json_encode($resBody, $__JSON_FLAGS);
unset($database);

?>