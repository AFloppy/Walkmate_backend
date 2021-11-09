<?php
// Written by NamHyeok Kim

error_reporting(E_ALL);
ini_set("display_errors", 1); // For error debugging

require_once("dbAccount.php");

header("Content-Type:application/json");

try {
    $database = new PDO("mysql:host=localhost;dbname=walkmate;charset=utf8mb4", $__dbUserName, $__dbPassword);
} catch(PDOException $e) {
    echo "DB Error / {$e->getMessage()}";
}   

?>