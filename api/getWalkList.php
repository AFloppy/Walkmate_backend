<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount'];
$walkListCount = $_POST['walkListCount'];
$firstWalkKey = $_POST['firstWalkKey'];

$resArray = array('isSuccess' => false);

try {
    if($firstWalkKey === -1) {
        $q = "SELECT MAX(walkKey) FROM walk";
        $keyQuery = $database -> query($q);
        $firstWalkKey = $keyQuery->fetch(PDO::FETCH_COLUMN);
    }

    $sql = "SELECT * FROM walk WHERE walkKey <= :firstWalkKey ORDER BY walkKey DESC LIMIT :requireCount OFFSET :walkListCount";
    $query = $database -> prepare($sql);

    $param = array(':firstWalkKey' => $firstWalkKey, ':requireCount' => $requireCount, ':walkListCount' => $walkListCount);
    foreach($param as $key => $value) {
        if(is_int($value)) {
            $query -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $query -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    execQuery($query);
    
    $walkArray = $query -> fetchAll(PDO::FETCH_ASSOC);
    foreach($walkArray as $key => $value) {
        $walkArray[$key]['depLocation'] = json_decode($value['depLocation'], true);
        $walkArray[$key]['requireList'] = json_decode($value['requireList'], true);
    }
    
    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $query->rowCount();
    $resArray['walks'] = $walkArray;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
