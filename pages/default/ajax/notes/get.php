<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['module'])){die("-2");}
if(!isset($_POST['id'])){die("-3");}

require_once($_SESSION['CORE']."Notes.php");

echo $notes->Get_As_Table_By_Module($_POST['module'], $_POST['id']);


