<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['data'])){die("-2");}


if(!isset($_POST['data']['t_n'])){die("-3");}

$table_name = $_POST['data']['t_n'];


if(file_exists($_SESSION['CORE'].$table_name.".php")){
	
	require_once($_SESSION['CORE'].$table_name.".php");
	$ob = new $table_name();
	
	foreach($_POST["data"]["columns"] as $k=>$v){

		if(strpos($k,"*")){
			$_POST["data"]["columns"][trim($k,"*")] = $_POST["data"]["columns"][$k];
			unset($_POST["data"]["columns"][$k]);
		}

		// don't forfet to remove slash by using : stripslashes($v)
		if ($k !== "facilities"){
			$_POST["data"]["columns"][trim($k,"*")] = addslashes ($v);
		}
		

	}	
	$_now = date("Y-m-d H:i:s");
	$data = array(
		"created_by"					=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"						=>	$_now,
		"updated_by"					=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"montant"						=>	$_POST["data"]["columns"]["p_p_montant"],
		"id_propriete_location_type"	=>	$_POST["data"]["columns"]["p_p_type"],
		"de"							=>	$_POST["data"]["columns"]["periode_de"],
		"a"								=>	$_POST["data"]["columns"]["periode_a"],
		"status"						=>	$_POST["data"]["columns"]["_status"],
		"id_propriete"					=>	$_POST["data"]["columns"]["p_p_id_propriete"]
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
	}else{
		unset($data["updated"], $data["updated_by"]);
	}
	
	$ob->save($data);
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


