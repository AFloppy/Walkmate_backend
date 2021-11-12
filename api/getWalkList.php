<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount'];
$walkListCount = $_POST['walkListCount'];
$firstWalkKey = $_POST['firstWalkKey'];

if($firstWalkKey === -1) {
    $q = "SELECT MAX(walkKey) FROM walk";
    $keyQuery = $database -> query($q);
    $firstWalkKey = $keyQuery->fetch(PDO::FETCH_COLUMN);
}

$sql = "SELECT walkKey, title, location, nowMemberCount, maxMemberCount, requireList, description, hostID, time 
        FROM walk WHERE walkKey <= :firstWalkKey ORDER BY walkKey DESC LIMIT :requireCount OFFSET :walkListCount";
$query = $database -> prepare($sql);

$param = array(':firstWalkKey' => $firstWalkKey, ':requireCount' => $requireCount, ':walkListCount' => $walkListCount);
foreach($param as $key => $value) {
    if(is_int($value)) {
        $query -> bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $query -> bindValue($key, $value, PDO::PARAM_STR);
    }
}
$result = $query -> execute();

$resArray = array('isSuccess' => false);

if($result) {
    $walkArray = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($walkArray as $row) {
        $nickQuery = $database->prepare("SELECT ID, nickname FROM user WHERE ID = ?");
        $nickQuery->execute(array($row['hostID']));
        $userRow = $nickQuery->fetch(PDO::FETCH_ASSOC);
        $row['requireList'] = json_decode($row['requireList'], true);
        $row['hostNickName'] = $userRow['nickname'];
        $resArray['walks'][] = $row;
    }
    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $query->rowCount();
} else {
    $resArray['reason'] = "DB 오류 또는 접근 오류";
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
