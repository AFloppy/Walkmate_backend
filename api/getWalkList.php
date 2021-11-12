<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount'];

$sql = "SELECT walkKey, title, location, nowMemberCount, maxMemberCount, requireList, description, hostID, time FROM walk ORDER BY walkKey DESC LIMIT :requireCount";
$query = $database -> prepare($sql);
$query->bindValue(':requireCount', $requireCount, PDO::PARAM_INT);
$result = $query -> execute();

$resArray = array('isSuccess' => false, 'reason' => "", 'walksCount' => 0, 'walks' => array());

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
    //$resArray['walks'] = json_encode($resArray['walks'], $__JSON_FLAGS|JSON_FORCE_OBJECT);
} else {
    $resArray['reason'] = "DB 오류 또는 접근 오류";
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
