<?php 

session_start();
require_once("dbConfig.php");

$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $getKeysSql = "SELECT walkKey FROM memberlist WHERE memberKey = :memberKey";
    $getKeysQuery = $database -> prepare($getKeysSql);
    $getKeysQuery -> bindValue(':memberKey', $_SESSION['userKey'], PDO::PARAM_INT);

    execQuery($getKeysQuery);

    $joinKeys = $getKeysQuery -> fetchAll(PDO::FETCH_COLUMN);
    
    $joinKeysString = implode(', ', $joinKeys);
    $walksList = array();

    //echo $joinKeysString;

    if($getKeysQuery -> rowCount() > 0) {
        $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
        $getAddrQuery = $database -> prepare($getAddrSql);
        $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
        
        execQuery($getAddrQuery);
    
        $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);
    
        $getWalksSql = "SELECT walk.*, HAVERSINE(walk.depLatitude, walk.depLongitude, :lat, :long) AS distance, memberList.joinTime FROM walk INNER JOIN memberlist ON walk.walkKey = memberlist.walkKey WHERE walk.walkKey IN ({$joinKeysString}) ORDER BY walk.writeTime DESC";
        $getWalksQuery = $database -> prepare($getWalksSql);
    
        $getWalksQuery -> bindValue(':lat', $userAddr['addrLatitude'], PDO::PARAM_STR);
        $getWalksQuery -> bindValue(':long', $userAddr['addrLongitude'], PDO::PARAM_STR);

        execQuery($getWalksQuery);
        

        $walksList = $getWalksQuery -> fetchAll(PDO::FETCH_ASSOC);
    }

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getKeysQuery -> rowCount();
    $resArray['walks'] = $walksList;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>