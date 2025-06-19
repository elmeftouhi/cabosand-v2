<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, null);
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	$data = $ob->find("",array("conditions"=>array("id_complexe="=>$id)),"facilities_in_complexe");
	foreach($data as $k=>$v){
		$ob->delete($v["id"], "facilities_in_complexe");
	}
	echo $ob->delete($_POST["id"]);
}else{
	
}

