<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core."Notes.php");
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $notes->find(null, $cond, null);
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	echo $notes->delete($_POST["id"]);
	$msg = $data[0]["notes"];
	$notes->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Notes","-1"),$_POST["id"], $msg);
	
}else{
	echo 0;
}

