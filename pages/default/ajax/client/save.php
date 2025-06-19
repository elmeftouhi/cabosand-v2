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
		"first_name"		=>	$_POST["data"]["columns"]["first_name"],
		"last_name"			=>	$_POST["data"]["columns"]["last_name"],
		"societe_name"		=>	$_POST["data"]["columns"]["societe_name"],
		"id_category"		=>	$_POST["data"]["columns"]["client_category"],
		"id_type"			=>	$_POST["data"]["columns"]["client_type"],
		"id_status"			=>	$_POST["data"]["columns"]["client_status"],
		"cin"				=>	$_POST["data"]["columns"]["client_cin"],
		"passport"			=>	$_POST["data"]["columns"]["client_passport"],
		"adresse"			=>	$_POST["data"]["columns"]["client_adresse"],
		"ville"				=>	$_POST["data"]["columns"]["client_ville"],
		"email"				=>	$_POST["data"]["columns"]["client_email"],
		"phone_1"			=>	$_POST["data"]["columns"]["client_contact_1"],
		"phone_2"			=>	$_POST["data"]["columns"]["client_contact_2"],		
		"notes"				=>	$_POST["data"]["columns"]["client_notes"],
		"id_color"			=>	$_POST["data"]["columns"]["id_color"]
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		
		$msg = $_POST["data"]["columns"]["first_name"] . " " . $_POST["data"]["columns"]["last_name"];
		
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Client","0"),$lastID, $msg);
		
	}else{
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		$msg = $_POST["data"]["columns"]["first_name"] . " " . $_POST["data"]["columns"]["last_name"];
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Client","1"),$lastID, $msg);
	}
	

	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


