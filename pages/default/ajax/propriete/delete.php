<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, null);
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	$code = $data[0]["code"];
	$data = $ob->find("",array("conditions"=>array("id_propriete="=>$id)),"options_in_propriete");
	foreach($data as $k=>$v){
		$ob->delete($v["id"], "options_in_propriete");
	}
	
	$data = $ob->find("",array("conditions"=>array("id_propriete="=>$id)),"propriete_proprietaire_location");
	foreach($data as $k=>$v){
		$ob->delete($v["id"], "propriete_proprietaire_location");
	}
	
	$ob->setID($id);
	$UID = $ob->read()[0]["UID"];
	$upload_folder = $_SESSION["UPLOAD_FOLDER"];
	$filesDirectory = $upload_folder.'propriete'.$dS.$UID.$dS;
		
	if (file_exists($filesDirectory)) {
		array_map('unlink', glob("$filesDirectory/*.*"));
		rmdir($filesDirectory);
		
	}
	
	
	
	echo $ob->delete($_POST["id"]);
	$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete","-1"),$_POST["id"],$code);
}else{
	
}

