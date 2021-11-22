<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount'];
$walkListCount = $_POST['walkListCount'];
$requestTime = $_POST['requestTime'];

$resArray = array('isSuccess' => false);

try {
    $param = array(':reqTime' => $requestTime, ':requireCount' => $requireCount, ':walkListCount' => $walkListCount);
    
    $sql = "SELECT *";
    if(isset($_SESSION['userKey'])) {
        $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
        $getAddrQuery = $database -> prepare($getAddrSql);
        $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
        execQuery($getAddrQuery);

        if($getAddrQuery -> rowCount() === 1) {
            $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);
            $sql = $sql . ", HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance";
            $param = array_merge($param, array(':lat' => $userAddr['addrLatitude'], ':long' => $userAddr['addrLongitude']));
        }
    }
    $sql = $sql . " FROM walk WHERE writeTime <= STR_TO_DATE(:reqTime, '%Y-%m-%d %T') ORDER BY walkKey DESC LIMIT :requireCount OFFSET :walkListCount";

    $query = $database -> prepare($sql);

    foreach($param as $key => $value) {
        if(is_int($value)) {
            $query -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $query -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    execQuery($query);
    
    $walkArray = $query -> fetchAll(PDO::FETCH_ASSOC);

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
