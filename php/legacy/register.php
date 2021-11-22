<?php 
//Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];
$nickname = $_POST['nickname'];

$sql = "SELECT * FROM user WHERE ID = ? OR nickname = ?";
$query = $database->prepare($sql);
$result = $query->execute(array($id, $nickname));

$resArray = array('isSuccess'=>false, 'reason'=>"");
 

if($result) {
    if($query->rowCount() === 0) {
        $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $param = array(':id' => $id, ':pw' => $pw, ':gender' => $_POST['gender'], ':age' => $_POST['age'],
                ':email' => $_POST['email'], ':addr' => $_POST['address'], ':nick' => $nickname);

        $sql = "INSERT INTO user(ID, password, gender, age, email, address, nickname) 
                VALUES(:id, :pw, :gender, :age, :email, :addr, :nick)";
        $query = $database->prepare($sql);
        
        foreach($param as $key => $value) {
            if(is_int($value)) {
                $query -> bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $query -> bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $query->execute();
        $resArray['isSuccess'] = true;
    } else {
        $resArray['reason'] = "같은 아이디 또는 닉네임의 계정이 이미 존재합니다.";
    }
} else {
    $resArray['reason'] = "DB 오류";
}
echo json_encode($resArray, $__JSON_FLAGS);
unset($database);
?>
