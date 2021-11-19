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
    $confirmData = $_POST['confirmData'];
    
    $sql = "SELECT hostKey FROM walk WHERE walkKey = :walkKey";
    $getHostKeyQuery = $database -> prepare($sql);
    $getHostKeyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($getHostKeyQuery);

    if($getHostKeyQuery -> rowCount() < 1) {
        throw new Exception("없는 글", 3);
    }

    $hostKey = $getHostKeyQuery -> fetch(PDO::FETCH_COLUMN);

    if($hostKey !== $_SESSION['userKey']) {
        throw new Exception("권한 없음", 4);
    }

    $sql = "SELECT * FROM applyList WHERE walkKey = :walkKey";
    $getApplyQuery = $database -> prepare($sql);
    $getApplyQuery -> bindValue(":walkKey", $targetWalkKey, PDO::PARAM_INT);

    execQuery($getApplyQuery);

    if($getApplyQuery -> rowCount() < 1) {
        throw new Exception("신청한 사용자 없음", 4);
    }

    $applies = $getApplyQuery -> fetchAll(PDO::FETCH_ASSOC);

    $flag = false;
    foreach($applies as $app) {
        $app['memberKey'] = (int)$app['memberKey'];
        if($confirmData['userKey'] === $app['memberKey']) {
            if($confirmData['isAccept']) {
                unset($app['applyTime']);

                $confirmSql = "INSERT INTO memberList(walkKey, memberKey, memberID, nickname, joinTime)
                                VALUES (:walkKey, :memberKey, :memberID, :nickname, NOW())";
                $confirmQuery = $database -> prepare($confirmSql);
                foreach($app as $key => $value) {
                    if(is_int($value)) {
                        $confirmQuery -> bindValue(':'.$key, $value, PDO::PARAM_INT);
                    } else {
                        $confirmQuery -> bindValue(':'.$key, $value, PDO::PARAM_STR);
                    }
                }
                execQuery($confirmQuery);

                $changeCountSql = "UPDATE walk SET nowMemberCount = nowMemberCount + 1, applyMemberCount = applyMemberCount - 1 WHERE walkKey = :walkKey";
            } else {
                $changeCountSql = "UPDATE walk SET applyMemberCount = applyMemberCount - 1 WHERE walkKey = :walkKey";
            }

            $changeCountQuery = $database -> prepare($changeCountSql);
            $changeCountQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

            execQuery($changeCountQuery);
            
            $delApplySql = "DELETE FROM applylist WHERE walkKey = :walkKey AND memberKey = :memberKey";
            $delApplyQuery = $database -> prepare($delApplySql);

            $delApplyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
            $delApplyQuery -> bindValue(':memberKey', $app['memberKey'], PDO::PARAM_STR);
            
            execQuery($delApplyQuery);

            $resArray['isSuccess'] = true;
            $flag = true;
            break;
        }
    }

    if(!$flag) {
        throw new Exception("해당하는 신청자 없음", 4);
    }
} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>