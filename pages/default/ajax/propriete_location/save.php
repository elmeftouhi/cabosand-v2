<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['t_n'])){die("-2");}
/*
var_dump($_POST);
die();
*/
if(!isset($_POST['columns'])){die("-3");}


$table_name = $_POST['t_n'];

if(file_exists($_SESSION['CORE'].$table_name.".php")){
	
	require_once($_SESSION['CORE'].$table_name.".php");
	$ob = new $table_name();
	$d = array();
	foreach($_POST["columns"] as $k=>$v){
		
		$ob->save(array(
			"date_debut"	=>	$v["p_l_de"],
			"date_fin"		=>	$v["p_l_a"],
			"status"		=>	$v["status"],
			"id"			=>	$v["id"]
		));
		
	}	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


