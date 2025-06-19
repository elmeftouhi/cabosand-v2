<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['data']['t_n'])){die("-2");}
if(!isset($_POST['data']['columns'])){die("-3");}


$table_name = $_POST['data']['t_n'];

if(file_exists($_SESSION['CORE'].$table_name.".php")){
	
	require_once($_SESSION['CORE'].$table_name.".php");
	$ob = new $table_name();
	
	foreach($_POST['data']["columns"] as $k=>$v){
		
		if(strpos($k,"*")){
			$_POST["data"]["columns"][trim($k,"*")] = $_POST["data"]["columns"][$k];
			unset($_POST["data"]["columns"][$k]);
		}
		
		
		$_POST['data']["columns"][$k] = addslashes ($v);
	}	
	

	$data = array(
					"date_debut"	=>	$_POST['data']["columns"]["periode_de"],
				 	"date_fin"		=>	$_POST['data']["columns"]["periode_a"],
					"nbr_nuite"		=>	$_POST['data']["columns"]["periode_nuite"],
					"status"		=>	$_POST['data']["columns"]["status"],
					"UID"			=>	$_POST['data']["columns"]["UID"]
				 );
	
	if(isset($_POST['data']["columns"]["id"])){
		
		// FIRST GET OLD PERIODES ALREADY SAVED
		$ob->id = $_POST['data']["columns"]["id"];
		$periode = $ob->read()[0];
		$conditions = array(
			"UID="			=>	$_POST['data']["columns"]["UID"], 
			"id_periode="	=>	$_POST['data']["columns"]["id"]);
		
		foreach($ob->find(null,array("conditions AND"=>$conditions),"propriete_location") as $k=>$v){
			$ob->save(array(
				"id"			=>	$v["id"],
				"date_debut"	=>	$_POST['data']["columns"]["periode_de"],
				"date_fin"		=>	$_POST['data']["columns"]["periode_a"],
				"status"		=>	$_POST['data']["columns"]["status"]
			),"propriete_location");
		}
		
		$_now = date("Y-m-d H:i:s");
		
		//$data["updated"] = $_now;
		//$data["updated_by"] = $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"];
		
		$data["id"] = $_POST['data']["columns"]["id"];
		$lastID = $data["id"];
		
		$ob->save($data);
				
		$data = $ob->find("",array("conditions"=>array("id="=>$lastID)),"v_contrat_periode");
		$msg = (count($data)>0)? $data[0]['date_debut'] . " " . $data[0]['date_fin'] . " : " . $data[0]['first_name'] . " " . $data[0]['last_name']:"";
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat_Periode","0"),$lastID,$msg);
		
	}else{
		//$data["created_by"] = $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"];
		$ob->save($data);
		$lastID = $ob->getLastID();
		
		$data = $ob->find("",array("conditions"=>array("id="=>$lastID)),"v_contrat_periode");
		$msg = (count($data)>0)? $data[0]['date_debut'] . " " . $data[0]['date_fin'] . " : " . $data[0]['first_name'] . " " . $data[0]['last_name']:"";
		
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat_Periode","1"),$lastID,$msg);	
		
		$appartmenent_list = array();
		
		
		foreach($ob->find(null,array("conditions"=>array("UID="=>$_POST['data']["columns"]["UID"])), "propriete_location") as $k=>$v){
			if( !in_array($v["id_propriete"], $appartmenent_list) ){
				$ob->save(array(
					"UID"				=>		$_POST['data']["columns"]["UID"],
					"id_propriete"		=>		$v["id_propriete"],
					"source"			=>		"contrat",
					"id_periode"		=>		$lastID,
					"date_debut"		=>		$_POST['data']["columns"]["periode_de"],
					"date_fin"			=>		$_POST['data']["columns"]["periode_a"],
					"status"			=>		$v["status"]
				),"propriete_location");	
				array_push($appartmenent_list,$v["id_propriete"]);
			}

		}
		
	}
	
	//var_dump($data);
	
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


