<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
$code = $_SESSION['CORE'];
require_once($_SESSION['CORE'].'Helpers'.DIRECTORY_SEPARATOR.'ListView.php');

$ob = new ListView();
$ob->readAll();

$module = (!isset($_POST["module"]))? "" : $_POST["module"];
$name = (!isset($_POST["name"]))? "" : $_POST["name"];

$data = $ob->getByName($module); // return styles of module already saved!

$origins = array();
$selected_columns = array();


foreach($ob->getColumnsName($module) as $k=>$v){
	array_push($origins,$v["Field"]);
}

$returned = "empty !";
if($module !== ""){
	
	if($name === ""){
		$returned = "<div class='row' style='margin-bottom:10px; padding: 5px 5px 0 7px;'>";
		$returned .= "	<div class='col_6-inline' style='padding:5px 7px 0px 0px;'>";
		$returned .= "		<span style='font-size:16px; font-weight:bold;'>Styles</span>";
		$returned .= "	</div>";
		$returned .= "	<div class='col_6-inline' style='text-align:right; padding:0'>";
		$returned .= "		<div class='btn-group'>";
		$returned .= "			<button class='btn btn-red del'><i class='fas fa-minus-circle'></i></button>";
		$returned .= "			<button class='btn btn-orange edit'><i class='far fa-edit'></i></button>";
		$returned .= "			<button class='btn btn-green add'><i class='fas fa-plus'></i></button>";
		$returned .= "		</div>";
		$returned .= "	</div>";
		$returned .= "</div>";
		$returned .= "<ul class='unstyle'>";
		foreach($data as $k=>$v){
			$is_default = ($v["is_default"])? "<i class='far fa-check-square'></i>": "<i class='fas fa-square'></i>";
			$returned .= "<li> <a href='#list' class='listview_name' data-name='".$v["name"]."' data-module='".$module."'> ".$is_default." ".$v["name"]."</a></li>";
		}		
		$returned .= "</ul>";


		
		echo $returned;
	
		
	}elseif($name !== ""){
		
		$formats = array("date", "money", "on_off_default", "on_off","color");
		
		$returned = "<div class='row' style='margin-bottom:10px; padding: 5px 5px 0 7px;'>";
		$returned .= "	<div class='col_6-inline' style='padding:5px 7px 0px 0px;'>";
		$returned .= "		<span style='font-size:16px; font-weight:bold;'>Définitions</span>";
		$returned .= "	</div>";
		$returned .= "	<div class='col_6-inline' style='text-align:right; padding:0'>";
		$returned .= "		<div class='btn-group'>";
		$returned .= "			<button class='btn btn-green edit_column'><i class='far fa-save'></i> Enregistrer</button>";
		$returned .= "		</div>";
		$returned .= "	</div>";
		$returned .= "</div>";
		$returned .= "<ul class='unstyle definitions' style='margin:0; padding:0'>";
		
		$returned .= "<li class='columns_name'>";
		$returned .= "	<div class='row' style='padding:0; padding-bottom:10px'>";
		$returned .= "		<div class='col_1-inline' style='padding:0'><label><input checked style='display: table-cell;vertical-align: middle;' type='checkbox' id='columns_check_all'>Select</label></div>";
		$returned .= "		<div class='col_1-inline' style='padding:0'>Column</div>";
		$returned .= "		<div class='col_2-inline' style='padding:0'>Label</div>";
		$returned .= "		<div class='col_5-inline' style='padding:0'>Style</div>";
		$returned .= "		<div class='col_1-inline' style='padding:0'>format</div>";
		$returned .= "		<div class='col_2-inline' style='padding:0'>Actions</div>";
		$returned .= "	</div>";
		$returned .= "</li>";
		
		foreach($data as $k=>$v){
			if($v["name"] === $name){
				
				foreach($data[$k]["data"] as $kk=>$vv){
					$format = "<select class='_format' style='padding:3px 3px'>";
					$format .= "	<option value=''></option>";
					foreach($formats as $kkk=>$vvv){
						if(isset($vv["format"])){
							$format .= ($vv["format"] === $vvv)? "<option selected value='".$vvv."'>" . $vvv . "</option>": "<option value='".$vvv."'>" . $vvv . "</option>";
							
						}else{
							$format .= "<option value='".$vvv."'>" . $vvv . "</option>";
						}	
						
					}
					$format .= "</select>";
					//$format = (isset($vv["format"]))? $vv["format"]: "";
					if($vv["column"] !== "id"){
						$returned .= "<li>";
					}else{
						$returned .= "<li class='hide'>";
					}
						$returned .= "	<div class='row column' style='padding:0'>";
						$returned .= "		<div class='col_1-inline' style='padding:0'>";
						if($vv["display"]){
							$returned .= "			<input class='display' type='checkbox' checked>";	
						}else{
							$returned .= "			<input class='display' type='checkbox'>";
						}

						$returned .= "		</div>";

						$returned .= "		<div class='col_1-inline' style='padding:0'><input class='_column' type='text' readonly value='".$vv["column"]."'></div>";
						$returned .= "		<div class='col_2-inline' style='padding:0'><input class='_label' type='text' value='".$vv["label"]."'></div>";				
						$returned .= "		<div class='col_5-inline' style='padding:0'><input class='_style' type='text' value='".$vv["style"]."'></div>";
						$returned .= "		<div class='col_1-inline' style='padding:0'>".$format."</div>";
						$returned .= "		<div class='col_2-inline' style='padding:0'>";
						$returned .= "			<div class='btn-group'>";
						$returned .= "				<button class='btn btn-red column_delete'><i class='fas fa-minus-circle'></i></button>";
						$returned .= "				<button class='btn btn-default column_up'><i class='fas fa-chevron-up'></i></button>";
						$returned .= "				<button class='btn btn-default column_down'><i class='fas fa-chevron-down'></i></button>";				
						$returned .= "			</div>";
						$returned .= "		</div>";
						$returned .= "	</div>";
						$returned .= "</li>";						
					

				}				
			}
		}
		
		
		$returned .= "</ul>";
		
		$returned .= "<div class='row' style='margin:15px 0px'>";
		$returned .= "	<div class='col_12-inline' style='text-align:left; padding:0'>";
		$returned .= "		<button class='btn btn-orange column_add'><i class='fas fa-plus'></i> Ajouter Columns</button>";
		$returned .= "	</div>";
		$returned .= "</div>";
		
		echo $returned;

	}
	
}





