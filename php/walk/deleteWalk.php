<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$targetWalkKey = $_POST['walkKey'];
$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);        
    }

    $getHostSql = "SELECT hostKey FROM walk WHERE walkKey = :walkKey";
    $getHostQuery = $database -> prepare($getHostSql);
    $getHostQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    
    execQuery($getHostQuery);
    
    if($getHostQuery -> rowCount() < 1) {
        throw new Exception("존재하지 않는 글", 3);
    }

    $hostKey = $getHostQuery -> fetch(PDO::FETCH_COLUMN);

    if($_SESSION['userKey'] !== $hostKey) {
        throw new Exception("권한 없음", 5);
    }

    $delWalkSql = "DELETE FROM walk WHERE walkKey = :walkKey";
    $delWalkQuery = $database -> prepare($delWalkSql);
    $delWalkQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delWalkQuery);

    $delMemberListSql = "DELETE FROM memberlist WHERE walkKey = :walkKey";
    $delMemberListQuery = $database -> prepare($delMemberListSql);
    $delMemberListQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delMemberListQuery);

    $delApplyListSql = "DELETE FROM applylist WHERE walkKey = :walkKey";
    $delApplyListQuery = $database -> prepare($delApplyListSql);
    $delApplyListQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delApplyListQuery);

    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>