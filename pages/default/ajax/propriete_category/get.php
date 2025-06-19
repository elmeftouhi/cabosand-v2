<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}


$table_name = $_POST['data']['t_n'];
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	
	$args = array(
		"p_p"		=>	(isset($_POST['data']['p_p']))? $_POST['data']['p_p'] : null,
		"sort_by"	=>	(isset($_POST['data']['sort_by']))? $_POST['data']['sort_by'] : "created ASC",
		"current"	=>	(isset($_POST['data']['current']))? $_POST['data']['current'] : null,
	);
	
	$request =  $_POST['data']['request'];
	
	$conditions = array();

	if($request !== ""){
		$conditions["LOWER(propriete_category) like "] = "%". strtolower($request)."%";
	}
	
	if(count($conditions)>1){
		$conditions = array("conditions AND"=>$conditions);	
	}else{
		$conditions = array("conditions"=>$conditions);		
	}	
	
	/*
	var_dump($conditions);
	*/
	
	echo $ob->drawTable($args,$conditions, "");

}else{
	echo -1;
}