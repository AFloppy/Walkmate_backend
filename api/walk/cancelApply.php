<?php 

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$targetWalkKey = $_POST['walkKey'];
$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $checkAppSql = "DELETE FROM applylist WHERE walkKey = :walkKey AND memberKey = :memberKey";
    $checkAppQuery = $database -> prepare($checkAppSql);
    $checkAppQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    $checkAppQuery -> bindValue(':memberKey', $_SESSION['userKey'], PDO::PARAM_INT);
    
    execQuery($checkAppQuery);

    if($checkAppQuery -> rowCount() < 1) {
        throw new Exception("신청 내역이 없거나 글 없음", 3);
    }

    $updateCountSql = "UPDATE walk SET applyMemberCount = applyMemberCount - 1 WHERE walkKey = :walkKey";
    $updateCountQuery = $database -> prepare($updateCountSql);
    $updateCountQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($updateCountQuery);

    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>
