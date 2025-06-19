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
		"forme_juridique"	=>	addslashes($_POST["data"]["columns"]["forme_juridique"]),
		"ABR"				=>	$_POST["data"]["columns"]["ABR"],
		"status"			=>	$_POST["data"]["columns"]["status"],
		"is_default"		=>	$_POST["data"]["columns"]["is_default"]
	);
	
	if( $data["is_default"] == 1 ){
		$dt = $ob->fetchAll("entreprise_forme_juridique");
		foreach($dt as $k=>$v){
			$ob->save(array("id"=>$v["id"],"is_default"=>0), "entreprise_forme_juridique");
		}
	}
	
	if( isset($_POST["data"]["columns"]["id"]) ){
		$data["id"] = $_POST["data"]["columns"]["id"];
		//unset($data["created_by"]);
		$ob->save($data);
		//$lastID = $_POST["data"]["columns"]["id"];
		//$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Caisse","0"),$lastID);
	}else{
		//unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		//$lastID = $ob->getLastID();
		//$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Caisse","1"),$lastID);
	}

	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


