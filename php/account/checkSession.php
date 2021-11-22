<?php
require_once("dbconfig.php");
session_start();
if($_SESSION[ 'userId' ]){
  $userId=$_SESSION[ 'userId' ];
  $sql="SELECT real_id FROM account WHERE real_id ='$userId'";
  $data=array();
  $res=$db->query($sql);
  for($i=0;$i<$res->num_rows;$i++){
    $row=$res->fetch_array(MYSQLI_ASSOC);
    array_push($data,$row);
  }
  echo json_encode($data);
}
?>
