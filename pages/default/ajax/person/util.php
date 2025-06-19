<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data (2)"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){

	case "person":
		
		if(isset($_POST["options"]) and !empty($_POST["options"])){
			$option = $_POST["options"];
			require_once($core.'Person.php');
			
			$data = $person->find("", array("conditions" => array("id_person=" => $option["id_person"]) ), "person_login");
			if(count($data) > 0){
				$person->save(array("id" => $data[0]["id"], "password_" => md5($option["person_password"]) ), "person_login");
				$response["msg"] = "Modifié";
				$response["code"] = 1;
			}else{
				$response["msg"] = "Non Modifié";
				$response["code"] = 0;
			}

		}


	break;

	
}



echo json_encode($response);
