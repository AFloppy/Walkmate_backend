<?php
// Written by NamHyeok Kim

error_reporting(E_ALL);
ini_set("display_errors", 1); // 디버그 용 오류 echo 설정 (배포시 주석처리)

require_once("dbAccount.php"); // $__dsn, $__dbUserName, $__dbPassword 노출 방지

header("Content-Type:application/json");

$__JSON_FLAGS = JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK;

try {
    $database = new PDO($__dsn, $__dbUserName, $__dbPassword);
} catch(PDOException $e) {
    echo "DB Error / {$e -> getMessage()}";
}   

function execQuery($query) {
    if(!$query -> execute()) throw new Exception("DB 오류", 1);
}

function checkSession() {
    if(isset($_SESSION['userKey'])) return true;
    return false;
}

?>