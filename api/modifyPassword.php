<?php 

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$resBody = array('isSuccess' => false);

if(isset($_SESSION['user_id'])) {
    $originalPw = $_POST['originalPassword'];
    $desirePw = password_hash($_POST['desirePassword'], PASSWORD_DEFAULT);
    
    $sql = "SELECT password FROM user WHERE ID = ?";
    $query = $database->prepare($sql);
    $query->execute(array($_SESSION['user_id']));

    if($query->rowCount() === 1) {
        $queryData = $query->fetch(PDO::FETCH_ASSOC);
        $pwCheckResult = password_verify($originalPw, $queryData['password']);
        if($pwCheckResult) {
            $modifySql = "UPDATE user SET password = ? WHERE ID = ?";
            $modifyQuery = $database->prepare($modifySql);
            $modifyQuery->execute(array($desirePw, $_SESSION['user_id']));
            $resBody['isSuccess'] = true;
        } else {
            $resBody['reason'] = "기존 비밀번호 불일치";
        }
    } else {
        $resBody['reason'] = "잘못된 아이디";
    }
} else {
    $resBody['reason'] = "세션 오류";
}

echo json_encode($resBody, $__JSON_FLAGS);
unset($database);

?>
