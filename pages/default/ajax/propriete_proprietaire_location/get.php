<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}


$table_name = $_POST['data']['t_n'];
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	
	$id_propriete = $_POST['data']['id_propriete'];	
	$conditions = array("conditions"=>array("id_propriete=" => $id_propriete));
	echo $ob->drawTable("",$conditions, "v_propriete_proprietaire_location");
	

}else{
	echo -1;
}