<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['action'])){die();}
if(!isset($_POST['i'])){die();}

$core = $_SESSION['CORE'];
require_once($core."Menu.php");


$action = $_POST["action"];
$id = $_POST["i"];
$next = $_POST["next"];
$preview = $_POST["preview"];
$order = $_POST["order"];
var_dump( $links->editOrder($id, $action, $preview, $next, $order) );


