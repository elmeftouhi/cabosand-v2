<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){
	unset( $_SESSION["CABOSAND-MANAGER"]["USER"] );
	die(json_encode($response));
}
if(!isset($_POST['controler'])){die(json_encode($response));}
if(!isset($_POST['function'])){die(json_encode($response));}

/********************/

$core = $_SESSION['CORE'];
$controler = str_replace('.', DIRECTORY_SEPARATOR, addslashes($_POST['controler']) );
$function = addslashes($_POST['function']);



if(file_exists($core.$controler.".php")){
	require_once($core.$controler.".php");
	$controler = count( explode(DIRECTORY_SEPARATOR, $controler) ) > 1? explode(DIRECTORY_SEPARATOR, $controler)[1]: $controler;
	$ob = new $controler();
	if(method_exists($ob, $function)){
		if(isset($_POST['params'])){
			$response  = array("code"=>1, "msg"=>$ob->$function($_POST['params']));
		}else{
			$response  = array("code"=>1, "msg"=>$ob->$function());
		}
		
	}else{
		$response  = array("code"=>0, "msg"=>"Function Not Found");
	}
}else{
	$response  = array("code"=>0, "msg"=>"Controler Not Found");
}

echo json_encode($response);