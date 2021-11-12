<?php

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$resBody = array('isSuccess' => false);

if(isset($_SESSION['user_id'])){
    $nickname = $_POST['nickname'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    $sql = "UPDATE user SET nickname = :nickname, 
            address = :address, gender = :gender, age = :age WHERE ID = :id";
    $param = array(':id' => $_SESSION['user_id'], ':nickname' => $nickname,
                    ':address' => $address, ':gender' => $gender, ':age' => $age);
    $query = $database -> prepare($sql);
    foreach($param as $key => $value) {
        if(is_int($value)){
            $query -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $query -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    if($query->execute()){
        $resBody['isSuccess'] = true;
    }
} else {
    $resBody['reason'] = "세션 오류";
}

echo json_encode($resBody, $__JSON_FLAGS);
unset($database);

?>