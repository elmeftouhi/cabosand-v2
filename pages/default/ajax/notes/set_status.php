<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['notes'])){die("-2");}

$id = $_POST['notes']["id"];
$new_status = $_POST['notes']["status"];

$data = array(
	'id'			=>	$id,
	'status'		=>	$new_status
);

require_once($_SESSION['CORE']."Notes.php");
if($notes->save($data)) echo 1; else	echo 0;




