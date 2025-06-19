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
		"created_by"			=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"				=>	$_now,
		"updated_by"			=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],	
		"id_forme_juridique"	=>	$_POST["data"]["columns"]["forme_juridique"],
		"raison_social"			=>	addslashes($_POST["data"]["columns"]["raison_social"]),
		"slogon"				=>	addslashes($_POST["data"]["columns"]["slogon"]),
		"capital"				=>	$_POST["data"]["columns"]["capital"],
		"adresse"				=>	addslashes($_POST["data"]["columns"]["adresse"]),
		"telephone_1"			=>	addslashes($_POST["data"]["columns"]["telephone_1"]),
		"telephone_2"			=>	$_POST["data"]["columns"]["telephone_2"],
		"fax_1"					=>	addslashes($_POST["data"]["columns"]["fax_1"]),
		"fax_2"					=>	addslashes($_POST["data"]["columns"]["fax_2"]),
		"site_internet"			=>	$_POST["data"]["columns"]["site_internet"],
		"email"					=>	addslashes($_POST["data"]["columns"]["email"]),
		"cnss"					=>	addslashes($_POST["data"]["columns"]["cnss"]),
		"patente"				=>	$_POST["data"]["columns"]["patente"],
		"identification_fiscale"=>	addslashes($_POST["data"]["columns"]["identification_fiscale"]),
		"registre_commerce"		=>	addslashes($_POST["data"]["columns"]["registre_commerce"]),
		"ice"					=>	$_POST["data"]["columns"]["ice"],
		"notes"					=>	addslashes($_POST["data"]["columns"]["notes"]),
		"status"			=>	$_POST["data"]["columns"]["status"],
		"is_default"		=>	$_POST["data"]["columns"]["is_default"],

	);
	
	if( $data["is_default"] == 1 ){
		$dt = $ob->fetchAll("entreprise");
		foreach($dt as $k=>$v){
			$ob->save(array("id"=>$v["id"],"is_default"=>0), "entreprise");
		}
	}
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Entreprise","0"),$lastID);
	}else{
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Entreprise","1"),$lastID);
	}

	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


