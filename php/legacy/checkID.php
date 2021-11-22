<?php 
//Written by NamHyeok Kim

require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$desireID = $_POST['id'];

$sql = "SELECT id FROM user WHERE ID = ?";
$query = $database -> prepare($sql);
$result = $query -> execute(array($desireID));

$resArray = array('isSuccess' => false, 'reason' => "", 'canUseID' => false);

if($result) {
    $resArray['isSuccess'] = true;
    if($query -> rowCount() === 0) {
        $resArray['canUseID'] = true;
    }
} else {
    $resArray['reason'] = "DB 오류";
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);
?>