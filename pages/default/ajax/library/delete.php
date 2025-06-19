<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];
$cond = array("conditions" => array("id=" => $id) );
$data = $ob->find(null, $cond, null);
$dS = DIRECTORY_SEPARATOR;
if( count($data) > 0 ){
	//var_dump($data);
	
	
	//$filesDirectory = $core.'..'.$dS.'..'.$dS.'pages'.$dS.'uploads'.$dS.'produits'.$dS.$data[0]["UID"].$dS;
	$filesDirectory = $core.'..'.$dS.'..'.$dS.'..'.$dS.'public_html/statics'.$dS.'produits'.$dS.$data[0]["UID"];
	
	if (file_exists($filesDirectory)) {
		array_map('unlink', glob("$filesDirectory/*.*"));
		rmdir($filesDirectory);
		echo $ob->delete($_POST["id"]);
	}else{
		echo "folder not exists : " . $filesDirectory;
	}
	
	
}else{
	
}

