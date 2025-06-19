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
		"sort_by"			=>	(isset($_POST['data']['sort_by']))? $_POST['data']['sort_by'] : "created",
		"current"			=>	(isset($_POST['data']['current']))? $_POST['data']['current'] : null,
		"column_name"		=>		"v_depense"
	);
	
	$conditions = [];
	
	if( isset($_POST['data']['request']) ){
		if($_POST['data']['request'] !== '')
			$conditions["code like "] = "%". strtoupper($_POST['data']['request'])."%";
	}

	if(isset($_POST['data']['filter'])){
		foreach($_POST['data']['filter'] as $k=>$v){
			if($k === "complexe") $conditions["id_complexe = "] = $v;
			if($k === "caisse") $conditions["id_caisse = "] = $v;
			if($k === "category") $conditions["id_category = "] = $v;
			if($k === "utilisateur") $conditions["created_by = "] = $v;
		}
	}
	
	if( count($conditions) > 1 )
		$conditions = ['conditions AND' => $conditions];
	else
		$conditions = ['conditions' => $conditions];
	
	echo $ob->drawTable($args,$conditions, "v_depense");

}else{
	echo -1;
}
