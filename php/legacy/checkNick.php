<?php
//Written by NamHyeok Kim

require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$desireNick = $_POST['nickname'];

$sql = "SELECT nickname FROM user WHERE nickname = ?";
$query = $database -> prepare($sql);
$result = $query -> execute(array($desireNick));

$resArray = array('isSuccess' => false, 'reason' => "", 'canUseNick' => false);

if($result) {
    $resArray['isSuccess'] = true;
    if($query -> rowCount() === 0) {
        $resArray['canUseNick'] = true;
    }
} else {
    $resArray['reason'] = "DB 오류";
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>
