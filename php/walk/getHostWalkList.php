<?php 
// Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$resArray = array('isSuccess' => false);


try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
    $getAddrQuery = $database -> prepare($getAddrSql);
    $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
    
    execQuery($getAddrQuery);

    $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);

    $getWalksSql = "SELECT *, HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance 
                    FROM walk WHERE hostKey = :hostKey ORDER BY writeTime DESC";
    $getWalksQuery = $database -> prepare($getWalksSql);

    $getWalksQuery -> bindValue(':lat', $userAddr['addrLatitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':long', $userAddr['addrLongitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':hostKey', $_SESSION['userKey'], PDO::PARAM_INT);

    execQuery($getWalksQuery);

    $walkList = $getWalksQuery -> fetchAll(PDO::FETCH_ASSOC);

    foreach($walkList as $walk) {
        if(!isset($walkKeyString)) {
            $walkKeyString = $walk['walkKey'];
        } else {
            $walkKeyString .= ',' . $walk['walkKey'];
        }
    }

    //echo $walkKeyString;
    $getMemberSql = "SELECT * FROM memberlist WHERE walkKey IN ({$walkKeyString})";
    $getMemberQuery = $database -> prepare($getMemberSql);

    execQuery($getMemberQuery);
    if($getMemberQuery -> rowCount() > 0) {
        $memberList = $getMemberQuery -> fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    }

    $getApplySql = "SELECT * FROM applylist WHERE walkKey IN ({$walkKeyString})";
    $getApplyQuery = $database -> prepare($getApplySql);

    execQuery($getApplyQuery);
    if($getApplyQuery -> rowCount() > 0) {
        $applyList = $getApplyQuery -> fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    }

    foreach($walkList as $key => $value) {
        if(isset($memberList[$value['walkKey']]))
                $walkList[$key]['memberList'] = $memberList[$value['walkKey']];
        if(isset($applyList[$value['walkKey']]))
            $walkList[$key]['applyList'] = $applyList[$value['walkKey']];
    }

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getWalksQuery -> rowCount();
    $resArray['walks'] = $walkList;
} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>