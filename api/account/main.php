<?php
require_once("dbconfig.php");
session_start();
if($_SESSION[ 'userId' ]){
  echo $_SESSION[ 'userId' ];
}
?>
