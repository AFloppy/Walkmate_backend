<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];

$sql = "SELECT * FROM user WHERE ID = ?";
$query = $database->prepare($sql);
$query->execute(array($id));

$resArray = array('isSuccess'=>false, 'reason'=>"");
 
if($query->rowCount() === 0) {
    $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nick = $_POST['nickname'];
    $addr = $_POST['address'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    $sql = "INSERT INTO user(ID, password, gender, age, email, address, nickname) 
            VALUES(:id, :pw, :gender, :age, :email, :addr, :nick)";
    $query = $database->prepare($sql);
    $query->execute(
        array(':id' => $id, ':pw' => $pw, ':gender' => $gender, ':age' => $age, 
        ':email' => $email, ':addr' => $addr, ':nick' => $nick)
    );
    $resArray['isSuccess'] = true;
} else {
    $resArray['reason'] = "같은 아이디의 계정이 이미 존재합니다.";
}
echo json_encode($resArray, JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
unset($database);
?>
