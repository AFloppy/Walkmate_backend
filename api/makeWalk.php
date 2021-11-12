<?php 
//Written by NamHyeok Kim
session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$hostID = $_SESSION['user_id'];
$hostNick = $_SESSION['user_nickname'];

$title = $_POST['title'];
$location = $_POST['location'];
$maxMember = $_POST['maxMember'];
$require = json_encode(array('require' => $_POST['require']), $__JSON_FLAGS);
$description = $_POST['description'];
$time = $_POST['time'];

$memberList = json_encode(array('0'=>array('ID'=>$hostID, 'nickName'=>$hostNick)), 
                $__JSON_FLAGS|JSON_FORCE_OBJECT);
$applyList = "{}";

$param = array(':title' => $title, ':location' => $location, ':memberList' => $memberList, ':nowMemberCount' => 1,
    ':maxMemberCount' => $maxMember, ':applyList' => $applyList, ':requireList' => $require,
    ':description' => $description, ':hostID' => $hostID, ':time' => $time);

$sql = "INSERT INTO walk (title, location, nowMemberCount, memberList, maxMemberCount, applyList, requireList, description, hostID, time) VALUES (:title, :location, :nowMemberCount, :memberList, :maxMemberCount, :applyList, :requireList, :description, :hostID, :time)";

$query = $database->prepare($sql);
foreach($param as $key => $value) {
    if(is_int($value)) {
        $query->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $query->bindValue($key, $value, PDO::PARAM_STR);
    }
}
$result = $query->execute();

if($result) {
    echo json_encode(array('isSuccess' => true, 'reason' => ""), $__JSON_FLAGS);
} else {
    echo json_encode(array('isSuccess' => false, 'reason' => "DB 오류"), $__JSON_FLAGS);
}

unset($database);
?>