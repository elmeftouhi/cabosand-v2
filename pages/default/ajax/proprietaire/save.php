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
		"created_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"			=>	$_now,
		"updated_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"name"				=>	$_POST["data"]["columns"]["proprietaire_name"],
		"cin"				=>	$_POST["data"]["columns"]["proprietaire_cin"],
		"passport"			=>	$_POST["data"]["columns"]["proprietaire_passport"],
		"adresse"			=>	$_POST["data"]["columns"]["proprietaire_adresse"],
		"ville"				=>	$_POST["data"]["columns"]["proprietaire_ville"],
		"email"				=>	$_POST["data"]["columns"]["proprietaire_email"],
		"phone_1"			=>	$_POST["data"]["columns"]["proprietaire_contact_1"],
		"phone_2"			=>	$_POST["data"]["columns"]["proprietaire_contact_2"],		
		"agence_1"			=>	$_POST["data"]["columns"]["proprietaire_agence_1"],
		"agence_2"			=>	$_POST["data"]["columns"]["proprietaire_agence_2"],
		"rib_1"				=>	$_POST["data"]["columns"]["proprietaire_rib_1"],
		"rib_2"				=>	$_POST["data"]["columns"]["proprietaire_rib_2"],
		"notes"				=>	$_POST["data"]["columns"]["proprietaire_notes"],
		"status"			=>	$_POST["data"]["columns"]["proprietaire_status"]
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		$ob->save($data);
		$lastID = $data["id"];
		$msg = $data["name"];
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Proprietaire","0"),$lastID,$msg);
	}else{
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		$msg = $data["name"];
		
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Proprietaire","1"),$lastID,$msg);
	}
	
	
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


