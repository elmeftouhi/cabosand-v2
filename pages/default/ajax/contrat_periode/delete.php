<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, "v_contrat_periode");
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	
	$msg = $data[0]['date_debut'] . " " . $data[0]['date_fin'] . " : " . $data[0]['first_name'] . " " . $data[0]['last_name'];
	
	$conditions = array(
					"UID="			=>	$data[0]["UID"], 
					"id_periode="	=>	$data[0]["id"],
					"source="		=>	"contrat"
	);

	foreach($ob->find(null,array("conditions AND"=>$conditions),"propriete_location") as $k=>$v){
		$ob->delete($v["id"],"propriete_location");
	}
	echo $ob->delete($_POST["id"]);
	
	$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat_Periode","-1"),$_POST["id"],$msg);
}else{
	echo -1;
}

