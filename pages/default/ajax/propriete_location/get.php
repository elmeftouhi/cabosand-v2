<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}

$table_name = "Propriete_Location";
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	
	$args = array(
		"p_p"		=>	(isset($_POST['data']['p_p']))? $_POST['data']['p_p'] : null,
		"sort_by"	=>	(isset($_POST['data']['sort_by']))? $_POST['data']['sort_by'] : "created",
		"current"	=>	(isset($_POST['data']['current']))? $_POST['data']['current'] : null,
	);
	$UID = $_POST["data"]["UID"];
	$request =  isset($_POST['data']['request'])? strtolower($_POST['data']['request']):"";
	$conditions = array("conditions"=>array("UID="=>$UID));
	
	if($request !== ""){
		$conditions = array("conditions"=>array("LOWER(produit_category) like "=>$request."%"));
	}

	$ob->drawTable(null,$conditions,"v_propriete_location");

}else{
	echo -1;
}