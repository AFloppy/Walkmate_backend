<?php 

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$targetWalkKey = $_POST['walkKey'];
$targetMemberKey = $_POST['targetMemberKey'];

$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    if($_SESSION['userKey'] !== $targetMemberKey) {
        $getHostKeySql = "SELECT hostKey FROM walk WHERE walkKey = :walkKey";
        $getHostKeyQuery = $database -> prepare($getHostKeySql);
        $getHostKeyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
        execQuery($getHostKeyQuery);

        if($getHostKeyQuery -> rowCount() < 1) {
            throw new Exception("글 없음", 3);
        }

        $hostKey = $getHostKeyQuery -> fetch(PDO::FETCH_COLUMN);

        if($_SESSION['userKey'] !== $hostKey) {
            throw new Exception("권한 없음", 5);
        }
    }

    $delMemberSql = "DELETE FROM hostKey WHERE walkKey = :walkKey AND memberKey = :memberKey";
    $delMemberQuery = $database -> prepare($delMemberSql);
    $delMemberQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    $delMemberQuery -> bindValue(':memberKey', $targetMemberKey, PDO::PARAM_INT);

    execQuery($delMemberQuery);
    
    if($delMemberQuery -> rowCount() < 1) {
        throw new Exception("글이 없거나 멤버가 아님", 3);
    }

    $updateCountSql = "UPDATE walk SET nowMemberCount = nowMemberCount - 1 WHERE walkKey = :walkKey";
    $updateCountQuery = $database -> prepare($updateCountSql);
    $updateCountQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($updateCountQuery);

    $resArray['isSuccess'] = true;

} catch(Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>