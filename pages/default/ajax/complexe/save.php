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
	
	$data = array(
		"created_by"		=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"ABR"				=>	$_POST["data"]["columns"]["complexe_ABR"],
		"name"				=>	$_POST["data"]["columns"]["complexe_name"],
		"adresse"			=>	$_POST["data"]["columns"]["complexe_adresse"],
		"ville"				=>	$_POST["data"]["columns"]["complexe_ville"],
		"id_complexe_type"	=>	$_POST["data"]["columns"]["complexe_type"],
		"contact_1"			=>	$_POST["data"]["columns"]["complexe_contact_1"],
		"contact_2"			=>	$_POST["data"]["columns"]["complexe_contact_2"],
		"phone_1"			=>	$_POST["data"]["columns"]["complexe_phone_1"],
		"phone_2"			=>	$_POST["data"]["columns"]["complexe_phone_2"],
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		
		$t = $ob->find("",array("conditions"=>array("id_complexe="=>$_POST["data"]["columns"]["id"])),"facilities_in_complexe");
		$t = (is_null($t))? array():$t;
		foreach($t as $kk=>$vv){
			$ob->delete($vv["id"], "facilities_in_complexe");
		}
		
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		
	}else{
		$ob->save($data);
		
		$lastID = $ob->getLastID();
	}
	
	
	
	
	if(isset($_POST["data"]["columns"]["facilities"])){
		for( $i = 0; $i < count($_POST["data"]["columns"]["facilities"]); $i++){
			$ob->save(array("id_complexe"=>$lastID, "id_complexe_facilities"=>$_POST["data"]["columns"]["facilities"][$i]), "facilities_in_complexe");
		}

	}
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


