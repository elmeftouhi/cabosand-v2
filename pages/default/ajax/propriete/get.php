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
	
	unset($_SESSION["REQUEST"]);
	
	$_SESSION["REQUEST"] = [ $table_name	=> ["args"	=>	$args] ];
	
	$request =  isset($_POST['data']['filter']['request'])? addslashes( $_POST['data']['filter']['request'] ): "";

	$req = "";
	
	if($request !== ""){
		$req .= " (code like '%". strtolower($request) ."%'";
		$req .= " OR LOWER(CONVERT(proprietaire USING latin1)) like '%". strtolower($request) ."%' )";
	}
	
	if(isset($_POST['data']['filter'])){
		
		foreach($_POST['data']['filter'] as $k=>$v){
			
			if($k === "id_propriete_complexe")
				$req .= ($req === "")? " id_complexe = " . $v : " AND id_complexe = " . $v;
			
			if($k === "id_type")
				$req .= ($req === "")? " id_propriete_type = " . $v : " AND id_propriete_type = " . $v;

			if($k === "id_propriete_category")
				$req .= ($req === "")? " id_propriete_category = " . $v : " AND id_propriete_category = " . $v;
			
			if($k === "id_status")
				$req .= ($req === "")? " id_propriete_status = " . $v : " AND id_propriete_status = " . $v;
			
			if($k === "is_for_location")
				$req .= ($req === "")? " is_for_location = " . $v : " AND is_for_location = " . $v;
		}
		
	}
	
	if($req === "")
		$req = "SELECT * FROM v_propriete";
	else
		$req = "SELECT * FROM v_propriete WHERE " . $req;
	
	echo $ob->drawTable($args,$req, "v_propriete");

}else{
	echo -1;
}