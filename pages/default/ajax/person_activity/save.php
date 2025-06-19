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
		$_POST["data"]["columns"][trim($k,"*")] = addslashes ($v);
		

	}	
	
	$_now = date("Y-m-d H:i:s");
	$data = array(
		"first_name"		=>	$_POST["data"]["columns"]["person_first_name"],
		"last_name"			=>	$_POST["data"]["columns"]["person_last_name"],
		"id_profil"			=>	$_POST["data"]["columns"]["person_profile"],
		"email"				=>	$_POST["data"]["columns"]["person_email"],
		"telephone"			=>	$_POST["data"]["columns"]["person_telephone"],
		"status"			=>	$_POST["data"]["columns"]["status"],
		"UID"				=>	$_POST["data"]["columns"]["UID"],
		"created_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"			=>	$_now,
		"updated_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		
		$d = $ob->find("id",array( "conditions"=>array("id_person=" => $_POST["data"]["columns"]["id"] )),"person_login");
		if(count($d)>1){
			$ob->save(array("login"=>$_POST["data"]["columns"]["person_login"], "id"=>$d[0]["id"]), "person_login");
		}
		
		$ob->save($data);
		
	}else{
		
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		
		$lastID = $ob->getLastID();
		$password = md5($_POST["data"]["columns"]["person_password"]);
		
		$ob->save(array("login"=>$_POST["data"]["columns"]["person_login"], "password_"=>$password, "id_person"=>$lastID), "person_login");
	}
	
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


