<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);
$resArray = array('isSuccess' => false);

try {
    if(!checkSession()) {
        throw new Exception("로그인 세션 오류", 2);
    }

    $targetWalkKey = $_POST['walkKey'];

    $memberSql = "SELECT * FROM memberList WHERE walkKey = :walkKey";
    $memberQuery = $database -> prepare($memberSql);
    $memberQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    $flag = true;

    execQuery($memberQuery);

    if($memberQuery -> rowCount() > 0) {
        $memberResult = $memberQuery -> fetchAll(PDO::FETCH_ASSOC);
        foreach($memberResult as $value) {
            if($value['memberKey'] === $_SESSION['user_key']) {
                $flag = false;
                break;
            }
        }
    }

    $applySql = "SELECT * FROM applyList WHERE walkKey = :walkKey";
    $applyQuery = $database -> prepare($applySql);
    $applyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($applyQuery);

    if($applyQuery -> rowCount() > 0) {
        $applyResult = $applyQuery -> fetchAll(PDO::FETCH_ASSOC);
        foreach($applyResult as $value) {
            if($value['memberKey'] === $_SESSION['user_key']) {
                $flag = false;
                break;
            }
        }
    }

    if(!$flag) {
        throw new Exception("이미 신청 또는 참가 중", 4);
    }
    
    $insSql = "INSERT applyList (walkKey, memberKey, memberID, nickname, applyTime) 
            VALUES (:walkKey, :memberKey, :memberID, :nickname, NOW())";
    $insParam = array(':walkKey' => $targetWalkKey, ':memberKey' => $_SESSION['user_key'],
                        ':memberID' => $_SESSION['user_id'], ':nickname' => $_SESSION['user_nickname']);

    $insQuery = $database -> prepare($insSql);
    foreach($insParam as $key => $value) {
        if(is_int($value)) {
            $insQuery -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $insQuery -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    
    execQuery($insQuery);
    $countQuery = $database -> prepare("UPDATE walk SET applyMemberCount = applyMemberCount + 1 WHERE walkKey = :walkKey");
    $countQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($countQuery);
    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>