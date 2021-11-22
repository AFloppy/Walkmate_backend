<?php
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_SESSION['user_id'];
$pw = $_POST['password'];

$sql = "SELECT password FROM user WHERE ID = ?";
$query = $database -> prepare($sql);
$result = $query -> execute(array($id));

$resBody = array('isSuccess' => false, 'reason' => "", 'isMatchPassword' => false);

if($result) {
    if($query->rowCount > 0) {
        $resBody['isSuccess'] = true;
        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $checkPassword = password_verify($pw, $queryData['password']);
        if($checkPassword) {
            $resBody['isMatchPassword'] = true;
        }
    } else {
        $resBody['reason'] = "계정 오류이거나 로그인 되어 있지 않습니다.";
    }
} else {
    $resBody['reason'] = "DB 오류";
}

echo json_encode($resBody, $__JSON_FLAGS);
unset($database);

?>
