<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['t_n'])){die("-2");}
if(!isset($_POST['columns'])){die("-3");}


$table_name = $_POST['t_n'];

if(file_exists($_SESSION['CORE'].$table_name.".php")){
	
	require_once($_SESSION['CORE'].$table_name.".php");
	$ob = new $table_name();
	
	$data = array();

	foreach($_POST["columns"] as $k=>$v){
		$_POST["columns"][$k] = addslashes ($v);
		$data[$k] =  addslashes ($v);

	}	

	if( isset($data["id"]) ){	//IS EDIT
		
		$_now = date("Y-m-d H:i:s");
		$data["updated"] = $_now;
		$data["updated_by"] = $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"];
		$ob->save($data);
		$lastID = $data["id"];
		
		$data = $ob->find("",array("conditions"=>array("id="=>$lastID)),"v_contrat");
		$msg = (count($data)>0)? $data[0]['first_name'] . " " . $data[0]['last_name']:"";
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","0"),$lastID,$msg);
		
	}else{	// IS ADD NEW
		
		$data["created_by"] = $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"];
		$ob->save($data);
		$lastID = $ob->getLastID();
		
		$data = $ob->find("",array("conditions"=>array("id="=>$lastID)),"v_contrat");
		$msg = (count($data)>0)? $data[0]['first_name'] . " " . $data[0]['last_name']:"";
		
		$ob->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","1"),$lastID,$msg);
	}
	
	echo "1";
	

}else{
	echo "File not exists : " . $_SESSION['CORE'].$table_name.".php";
}


