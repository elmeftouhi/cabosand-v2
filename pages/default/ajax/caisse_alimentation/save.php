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
		

	}	
	$_now = date("Y-m-d H:i:s");
	$data = array(
		"created_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"			=>	$_now,
		"updated_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"source"			=>	addslashes($_POST["data"]["columns"]["c_a_source"]),
		"montant"			=>	$_POST["data"]["columns"]["c_a_montant"],
		"notes"				=>	addslashes($_POST["data"]["columns"]["c_a_notes"]),
		"id_caisse"			=>	$_POST["data"]["columns"]["c_a_id_caisse"]
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"], $data["id_caisse"]);
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Caisse","3"),$lastID);
	}else{
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Caisse","2"),$lastID);
	}

	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


