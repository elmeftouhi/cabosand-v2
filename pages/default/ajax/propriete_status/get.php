<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}


$table_name = $_POST['data']['t_n'];
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	
	$args = array(
		"p_p"				=>	(isset($_POST['data']['p_p']))? $_POST['data']['p_p'] : null,
		"sort_by"			=>	(isset($_POST['data']['sort_by']))? $_POST['data']['sort_by'] : "propriete_status",
		"current"			=>	(isset($_POST['data']['current']))? $_POST['data']['current'] : null,
		"column_name"		=>		"v_caisse"
	);
	
	$request =  isset($_POST['data']['request'])? strtolower($_POST['data']['request']):"";
	$conditions = null;
	
	if($request !== ""){
		$conditions = array("conditions"=>array("LOWER(propriete_status) like "=>$request."%"));
	}

	echo $ob->drawTable($args,$conditions, "v_propriete_status");

}else{
	echo -1;
}