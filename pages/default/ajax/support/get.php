<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}


$table_name = $_POST['data']['t_n'];
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	
	$data = $ob->find("",array("order"=>"created DESC"),"v_support");
	$returned = "";
	$colors = array("#FFF59D","#EDE7F6", "#E1BEE7", "#FFCDD2", "#E0F2F1", "#F9FBE7", "#FFFF00");
	$c_of_u = array();
	
	$usr = $ob->fetchAll("person");
	$i = 0;
	
	foreach($usr as $k=>$v){
		$c_of_u[$v["id"]] = $colors[$i];
		$i++;
	}
	
	foreach($data as $k=>$v){
		$returned .= '<div class="item" style="background-color:'.$c_of_u[$v["created_by"]].'">';
		$returned .= '	<div class="msg">';
		$returned .= 		$v["message"];
		$returned .= '	</div>';

		$returned .= '	<div class="row auth">';
		$returned .= '		<div class="col_6-inline">';
		$returned .= '			<div class="d"><div class="label label-blue">'.$v["created"].'</div></div>';
		$returned .= '		</div>';
		$returned .= '		<div class="col_6-inline">';
		$returned .= '			<div class="a">'.$v["first_name"].'</div>';
		$returned .= '		</div>';
		$returned .= '	</div>';
		$returned .= '</div>';
	}
	echo $returned;

}else{
	echo -1;
}