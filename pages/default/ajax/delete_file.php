<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['link'])){die("-2");}

$link = $_POST['link'];
require_once($_SESSION['CORE']."Contrat.php");

$temp = explode("/",$link);

if($temp[count($temp)-3] === "contrat"){
	$msg = $temp[count($temp)-1];
	$contrat->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","3"),0,$msg);
}elseif($temp[count($temp)-3] === "propriete"){
	$msg = $temp[count($temp)-1];
	$contrat->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete","3"),0,$msg);
}else{
	$msg = $link;
	$contrat->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","3"),0,$msg);
}



echo unlink($link);
//var_dump($_POST);
