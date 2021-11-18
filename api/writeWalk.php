<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['user_key'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $hostKey = $_SESSION['user_key'];
    $hostID = $_SESSION['user_id'];
    $hostNick = $_SESSION['user_nickname'];

    $title = $_POST['title'];
    $depLocation = json_encode($_POST['depLocation'], $__JSON_FLAGS);
    $maxMemberCount = $_POST['maxMemberCount'];
    $requireList = json_encode(array('require' => $_POST['require']), $__JSON_FLAGS);
    $description = $_POST['description'];
    $depTime = $_POST['depTime'];

    $param = array(':hostKey' => $hostKey, ':hostID' => $hostID, ':hostNickname' => $hostNick, ':title' => $title,
                    ':depLocation' => $depLocation, ':maxMemberCount' => $maxMemberCount,
                    ':requireList' => $requireList, ':description' => $description, ':depTime' => $depTime);

    $insSql = "INSERT INTO walk (hostKey, hostID, hostNickname, title, depLocation, nowMemberCount, maxMemberCount, 
                                applyMemberCount, requireList, description, depTime, writeTime) 
            VALUES (:hostKey, :hostID, :hostNickname, :title, :depLocation, 1, :maxMemberCount, 
                                0, :requireList, :description, :depTime, NOW())";

    $insQuery = $database->prepare($insSql);
    foreach($param as $key => $value) {
        if(is_int($value)) {
            $insQuery->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $insQuery->bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    execQuery($insQuery);

    $walkKey = $database -> query("SELECT LAST_INSERT_ID()") -> fetch(PDO::FETCH_COLUMN);
    $memberSql = "INSERT INTO memberList (walkKey, memberKey, memberID, nickname, joinTime) 
            VALUES (:walkKey, :memberKey, :memberID, :nickname, NOW())";

    $memberParam = array(':walkKey' => $walkKey, ':memberKey' => $hostKey, ':memberID' => $hostID, ':nickname' => $hostNick);
    $memberQuery = $database -> prepare($memberSql);

    foreach($memberParam as $key => $value) {
        if(is_int($value)) {
            $memberQuery -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $memberQuery -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    execQuery($memberQuery);
    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>