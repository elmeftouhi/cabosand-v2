<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];

	switch ($module){

		case "is_exist" :

			if(isset($_POST["name"])){

				$name = $_POST["name"];
				
				require_once($core.'Proprietaire.php');
				
				$data = $proprietaire->find(null,array("conditions AND"=>array("name="=>$name)),null);
				if(count($data)>0){
					$response  = array("code"=>1, "msg"=> $name . " Existe déjà!");
				}else{
					$response  = array("code"=>0, "msg"=> $name . " n\'existe pas!");
				}
			}

			break;


	}



echo json_encode($response);
