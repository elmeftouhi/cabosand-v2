<?php session_start();

$core = $_SESSION["CORE"];
$host = $_SESSION["HOST"];
/*
if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}

$core = $_SESSION['CORE'];
$table_name = $_POST['data']['t_n'];
require_once($core.$table_name.".php");
$ob = new $table_name();

*/
$dS = DIRECTORY_SEPARATOR;

$filesDirectory = $core.'..'.$dS.'..'.$dS.'..'.$dS.'public_html/statics'.$dS.'produits'.$dS.$_POST['id_produit'].$dS;
//$filesDirectory = $core.'..'.$dS.'..'.$dS.'..'.$dS.'uploads'.$dS.'produits'.$dS.$_POST['id_produit'].$dS;
//$filesDirectory = $core.'..'.$dS.'..'.$dS.'..'.$dS.'public_html'.$dS.'statics'.$dS.'produits'.$dS.$_POST['id_produit'].$dS;

$nbr = 0;

if(file_exists($filesDirectory)){
	foreach(scandir($filesDirectory) as $k=>$v){

		if($v <> "." and $v <> ".." and strpos($v, '.') !== false){
			echo "<img class='showImage' style='width:100px; height:auto' src='http://www.aspi-confort.com/statics/produits/".$dS.$_POST['id_produit'].$dS.$v."'>";
			$nbr++;
		}

	}	
	
}


if($nbr==0){
	echo '<div class="info info-success"><div class="info-success-icon"><i class="fas fa-info-circle"></i></div><div class="info-message">Aucun image pour ce client <br>'.$filesDirectory .' </div></div>';
}
//var_dump(scandir($filesDirectory,1));


