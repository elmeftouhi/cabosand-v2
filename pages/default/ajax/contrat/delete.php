<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, "v_contrat");
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	
	// DELETE CONTRAT APPARTEMENT
	$UID = $data[0]["UID"];
	
	$msg = $data[0]['first_name'] . " " . $data[0]['last_name'];
	
	$d = $ob->find(null,array("conditions"=>array("UID="=>$UID)),"contrat_periode");
	foreach($d as $k=>$v){
		$ob->delete($v["id"], "contrat_periode");
	}
		
	// DELETE APPARTEMENT LOCATION
	
	$d = $ob->find(null,array("conditions AND"=>array("UID="=>$UID, "source="=>"contrat")),"propriete_location");
	foreach($d as $k=>$v){
		$ob->delete($v["id"], "propriete_location");
	}
	
	echo $ob->delete($_POST["id"]);
	
	$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","-1"),$_POST["id"],$msg);
	
}else{
	echo -1;
}

