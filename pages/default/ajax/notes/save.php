<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['notes'])){die("-2");}

$created = date("Y-m-d H:i:s");
$created_by = $_SESSION["CABOSANDE-MANAGER"]["USER"]["id"];
$notes = $_POST['notes'];

$data = array(
	'created'		=>	$created,
	'created_by'	=>	$created_by,
	'module'		=>	addslashes($notes["module"]),
	'id_module'		=>	$notes["id_module"],
	'notes'			=>	addslashes($notes["notes"])
);

if(isset($notes["id"])) {
	$data["id"] = $notes["id"];
	unset($data["created"]);
}
require_once($_SESSION['CORE']."Notes.php");
if($notes->save($data)) echo 1; else	echo 0;




