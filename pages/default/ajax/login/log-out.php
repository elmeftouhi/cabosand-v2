<?php session_start();
if (!isset($_POST["param"])){die(-1);}

if(isset($_POST["param"]["action"])){
	if($_POST["param"]["action"] == "login"){
		$USER = array("login"=>"elmeftouhi@gmail.com", "password"=>"123456789");
		$_SESSION["LOCATOR-APP"]["USER"] = $_POST["param"]["args"];
		echo 1;
	}else{
		unset($_SESSION["LOCATOR-APP"]["USER"]);
		echo 1;
	}
}else{
	echo -2;
}

