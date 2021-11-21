<?php 

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount'];
$walkListCount = $_POST['walkListCount'];
$requestTime = $_POST['requestTime'];
$limitDistance = $_POST['limitDistance'];

$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
    $getAddrQuery = $database -> prepare($getAddrSql);
    $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
    execQuery($getAddrQuery);

    if($getAddrQuery -> rowCount() < 1) {
        throw new Exception("잘못된 사용자", 4);
    }

    $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);

    $getListSql = "SELECT * FROM (SELECT *, HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance 
                FROM walk WHERE writeTime <= STR_TO_DATE(:reqTime, '%Y-%m-%d %T')) AS X WHERE distance <= :limitDist ORDER BY distance LIMIT :reqCount OFFSET :walkListCount";
    $getListQuery = $database -> prepare($getListSql);

    $getListParam = array(':lat' => $userAddr['addrLatitude'], ':long' => $userAddr['addrLongitude'], ':reqTime' => $requestTime,
                        ':limitDist' => $limitDistance, ':reqCount' => $requireCount, ':walkListCount' => $walkListCount);

    foreach($getListParam as $key => $value) {
        if(is_int($value)) {
            $getListQuery -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $getListQuery -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }        

    execQuery($getListQuery);

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getListQuery -> rowCount();
    $resArray['walks'] = $getListQuery -> fetchAll(PDO::FETCH_ASSOC);
    
    
} catch(Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
