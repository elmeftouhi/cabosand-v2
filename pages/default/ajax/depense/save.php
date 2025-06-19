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
		"libelle"			=>	addslashes($_POST["data"]["columns"]["depense_libelle"]),
		"montant"			=>	$_POST["data"]["columns"]["depense_montant"],
		"id_caisse"			=>	$_POST["data"]["columns"]["depense_caisse"],
		"id_category"		=>	$_POST["data"]["columns"]["depense_category"],
		"id_propriete"		=>	$_POST["data"]["columns"]["depense_propriete"],
		"notes"				=>	addslashes($_POST["data"]["columns"]["notes"]),
		"status"			=>	$_POST["data"]["columns"]["depense_status"],
		"UID"				=>	$_POST["data"]["columns"]["UID"],
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		$msg = addslashes($_POST["data"]["columns"]["depense_libelle"]) . " Mnt: " . $ob->format($_POST["data"]["columns"]["depense_montant"]);
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Depense","0"),$lastID, $msg);
	}else{
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		$msg = addslashes($_POST["data"]["columns"]["depense_libelle"]) . " Mnt: " . $ob->format($_POST["data"]["columns"]["depense_montant"]);
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Depense","1"),$lastID,$msg);
	}

	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


