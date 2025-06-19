<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, null);
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	echo $ob->delete($_POST["id"]);
	$msg = $data[0]["first_name"] . " " . $data[0]["last_name"];
	$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Client","-1"),$_POST["id"], $msg);
	
}else{
	
}

