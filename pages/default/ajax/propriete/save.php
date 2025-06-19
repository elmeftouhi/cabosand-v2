<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['data'])){die("-2");}


if(!isset($_POST['data']['t_n'])){die("-3");}

$table_name = $_POST['data']['t_n'];
//var_dump($_POST["data"]);
//die();

if(file_exists($_SESSION['CORE'].$table_name.".php")){
	
	require_once($_SESSION['CORE'].$table_name.".php");
	$ob = new $table_name();
	foreach($_POST["data"]["columns"] as $k=>$v){

		if(strpos($k,"*")){
			$_POST["data"]["columns"][trim($k,"*")] = $_POST["data"]["columns"][$k];
			unset($_POST["data"]["columns"][$k]);
		}

		// don't forfet to remove slash by using : stripslashes($v)
		if ($k !== "propriete_options"){
			$_POST["data"]["columns"][trim($k,"*")] = addslashes ($v);
		}
		

	}	
	$is_for_sell = ($_POST["data"]["columns"]["propriete_isForSell"]=="true")? 1:0;
	$is_for_location = ($_POST["data"]["columns"]["propriete_isForLocation"]=="true")? 1:0;
	
	$_now = date("Y-m-d H:i:s");
	$data = array(
		"created_by"			=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"updated"				=>	$_now,
		"updated_by"			=>	$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],
		"code"					=>	$_POST["data"]["columns"]["propriete_code"],
		"id_complexe"			=>	$_POST["data"]["columns"]["propriete_complexe"],
		"zone_number"			=>	$_POST["data"]["columns"]["propriete_zone"],
		"bloc_number"			=>	$_POST["data"]["columns"]["propriete_bloc"],
		"appartement_number"	=>	$_POST["data"]["columns"]["propriete_numero"],
		"surface"				=>	$_POST["data"]["columns"]["propriete_surface"],
		"id_propriete_category"	=>	$_POST["data"]["columns"]["propriete_category"],
		"id_proprietaire"		=>	$_POST["data"]["columns"]["propriete_proprietaire_id"],		
		"id_propriete_status"	=>	$_POST["data"]["columns"]["propriete_status"],
		"id_propriete_type"		=>	$_POST["data"]["columns"]["propriete_type"],
		"nbr_chambre"			=>	$_POST["data"]["columns"]["propriete_chambre"],
		"etage_number"			=>	$_POST["data"]["columns"]["propriete_etage"],
		"notes"					=>	$_POST["data"]["columns"]["propriete_notes"],
		"maximum_person"		=>	$_POST["data"]["columns"]["propriete_max_person"],
		"UID"					=>	$_POST["data"]["columns"]["UID"],
		"is_for_sell"			=>	$is_for_sell,
		"is_for_location"		=>	$is_for_location,
		"id_propriete_modalite_paiement"			=>	0
	);
	
	if( isset($_POST["data"]["columns"]["id"]) ){	//IS EDIT
		
		$data["id"] = $_POST["data"]["columns"]["id"];
		unset($data["created_by"]);
		
		$t = $ob->find("",array("conditions"=>array("id_propriete="=>$_POST["data"]["columns"]["id"])),"options_in_propriete");
		$t = (is_null($t))? array():$t;
		foreach($t as $kk=>$vv){
			$ob->delete($vv["id"], "options_in_propriete");
		}
		
		$ob->save($data);
		$lastID = $_POST["data"]["columns"]["id"];
		
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete","0"),$lastID, $data["code"]);
		
	}else{	// IS ADD NEW
		
		unset($data["updated"], $data["updated_by"]);
		$ob->save($data);
		$lastID = $ob->getLastID();
		
		// propriete location

		$d = $ob->find("",array("conditions AND" => array("id_propriete="=>0, "created_by=" => $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"] )), "propriete_proprietaire_location");
		foreach($d as $k=>$v){
			$ob->save(array("id"=>$v["id"], "id_propriete" => $lastID), "propriete_proprietaire_location");
		}
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete","1"),$lastID, $data["code"]);
	}
	
		// propriete options
		if(isset($_POST["data"]["columns"]["propriete_options"])){
			for( $i = 0; $i < count($_POST["data"]["columns"]["propriete_options"]); $i++){
				$ob->save(array("id_propriete"=>$lastID, "id_propriete_options"=>$_POST["data"]["columns"]["propriete_options"][$i]), "options_in_propriete");
			}

		}

	

	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


