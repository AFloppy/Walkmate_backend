<?php 

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];
$pw = $_POST['password'];

$sql = "SELECT * FROM user WHERE ID = ?";
$query = $database->prepare($sql);
$query->execute(array($id));

$resArray = ['isSuccess' => false, 'reason' => ""];

if($query->rowCount() === 1) {
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $pwVerify = password_verify($pw, $result['password']);
    if($pwVerify) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_nickname'] = $result['nickname'];
        $resArray['isSuccess'] = true;
    } else {
        $resArray['reason'] = "비밀번호가 일치하지 않습니다.";
    }
} else {
    $resArray['reason'] = "계정이 존재하지 않습니다.";
}
echo json_encode($resArray, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);

unset($database);
?>