<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");


if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];

switch ($module){
		
	case "get_module":
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		$data = '';
		foreach($l->readAll() as $k=>$v){
			$data .= "<li> <a href='#list' class='listview_module' data-module='".$k."'> <i class='far fa-caret-square-right'></i> ".$k."</a></li>";
		}
		$response  = array("code"=>1, "msg"=>$data);
	break;
		
	case "del_module":
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		
		$module_name = $_POST['options']['module'];
		if($l->deleteModule($module_name)){
			$response  = array("code"=>1, "msg"=>"success");
		}
		
	break;
		
	case "select":
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		$selected = $_POST["options"]["selected"];
		$styles = $l->getStylesByModule($_POST["options"]["module"]);
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>List Style<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<h3 style='margin-left:10px'>Style</h3>";
				
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12-inline'><table class='table'><tbody>";
		
		
		foreach($styles as $k=>$v){
			$data .= "  		<tr><td style='padding:10px 5px'>";
			$data .= "  			<label>";
			if($v["name"] === $selected)
				$data .= "  			<input checked name='list' type='radio' value='" . $v["name"] . "'>";
			else
				$data .= "  			<input name='list' type='radio' value='" . $v["name"] . "'>";
			$data .= "  			". strtoupper( $v["name"] ) ."</label>";
			$data .= "  		</td></tr>";
		}
		
		$data .= "  		</tbody></table></div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green listview_save_' data-module='".$_POST["options"]["module"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);
		
		
		
		//$list_module = $_POST["options"]["module"];
		
		//$response  = array("code"=>1, "msg"=>"Saved");
		break;
		
	case "save":
		
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		$columns = array();
		
		foreach($l->getColumnsName($_POST["options"]["module"]) as $k=>$v){
			if($v["Field"] === "id")
				array_push($columns, array("column"=>$v["Field"],"label"=>$v["Field"],"display"=>0,"format"=>"","style"=>""));
			else
				array_push($columns, array("column"=>$v["Field"],"label"=>$v["Field"],"display"=>1,"format"=>"","style"=>""));
		}
		//var_dump($_POST["options"]);
		//var_dump($l->getColumnsName($_POST["options"]["module"]));
		//die();
		$data = array(
			"name" 			=> 		$_POST["options"]["data"]["name"],
			"is_default" 	=> 		$_POST["options"]["data"]["is_default"],
			"data"			=>		$columns
		);
		
		$l->addStyle($_POST["options"]["module"], $data);
		$response  = array("code"=>1, "msg"=>"Saved");
	break;
		
	case "edit_column":
		
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		$data = array(
			"name" 			=> 		$_POST["options"]["name"],
			"columns" 		=> 		$_POST["options"]["columns"]
		);
		
		$l->editColumns($_POST["options"]["module"], $data);
		$response  = array("code"=>1, "msg"=>"Saved");
	break;
		
	case "save_edit":
		
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();

		$data = array(
			"name" 			=> 		$_POST["options"]["data"]["name"],
			"name_temp" 	=> 		$_POST["options"]["data"]["name_temp"],
			"is_default" 	=> 		$_POST["options"]["data"]["is_default"]
		);
		
		$l->editStyle($_POST["options"]["module"], $data);
		$response  = array("code"=>1, "msg"=>"Saved");
	break;
		
	case "delete":
		
		require_once($_SESSION['CORE']."Helpers". DIRECTORY_SEPARATOR ."ListView.php");
		$l = new ListView();
		$l->deleteStyle($_POST["options"]["module"], $_POST["options"]["name"]);

		$response  = array("code"=>1, "msg"=>"Saved");
	break;

	case "edit":
		
		//require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>List Style<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<h3 style='margin-left:10px'>Style</h3>";
				
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12-inline'>";
		$data .= "  			<label for='list_name'>Name : </label>";
		$data .= "  			<input type='text' placeholder='name' id='list_name' value='" . $_POST["options"]["name"] . "'>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 125px'>";
		if($_POST["options"]["is_default"]){
		$data .= "					<div class='on_off on' id='is_default'></div>";			
		}else{
		$data .= "					<div class='on_off off' id='is_default'></div>";	
		}
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Par Défaut";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green listview_save edit' data-name='" . $_POST["options"]["name"] . "' value='".$_POST["options"]["module"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);
		
	break;
		
	case "add":
		
		//require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>List Style<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<h3 style='margin-left:10px'>Style</h3>";
				
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12-inline'>";
		$data .= "  			<label for='list_name'>Name : </label>";
		$data .= "  			<input type='text' placeholder='name' id='list_name'>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 125px'>";
		$data .= "					<div class='on_off off' id='is_default'></div>";
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Par Défaut";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green listview_save' value='".$_POST["options"]["module"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);
		
	break;
		
}
/*
echo $response["msg"];
die();
*/
echo json_encode($response);
