<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){

	case "propriete":
		$columns = array(
			array("column" => "code", "label"=>"CODE"),
			array("column" => "name", "label"=>"COMPLEXE")
		);
		
		require_once($core.'Propriete.php');
		
		$request = (isset($_POST["request"]["code"])? $_POST["request"]["code"]:"");
		
		$cond = array();
		if(isset($_POST["request"]["code"])){
			$cond["LOWER(code) like "]  = "%".strtolower($_POST["request"]["code"])."%";
		}
		  if(isset($_POST["request"]["complexe"])){
			$cond["id_complexe ="]  = $_POST["request"]["complexe"];
		}
		
		$data = "<div class='panel' style='width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Selectionnez Appartement <span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; width:100%; z-index: 999999'>";
		$data .= "		<div class='row' style='padding:5px 2px'>";
		$data .= "			<div class='col_5-inline' style='padding:0'><input value='".$request."' type='text' id='_r' style='border-radius:0; border-right:0'></div>";
		$data .= "			<div class='col_5-inline' style='padding:0'>";
		$data .= "				<select id='_complexe'>";
		$data .= "					<option value='-1'>-- Complexe </option>";
		
		$p = $propriete->find("", array("order"=>"name"), "complexe");
		foreach($p as $k=>$v){
			if(isset($_POST["request"]["complexe"])){
				$s = ($_POST["request"]["complexe"] == $v["id"])? "selected":"";
				$data .= "				<option ".$s." value='".$v['id']."'>".$v['name']."</option>";
			}else{
				$data .= "				<option value='".$v['id']."'>".$v['name']."</option>";
			}
		}
		
		$data .= "				</select>";
		$data .= "			</div>";
		$data .= "			<div class='col_2-inline' style='padding:0'><button style='width:100%; margin:0; padding:8px; border-radius:0' class='btn btn-default _search'><i class='fas fa-search'></i></button></div>";
		$data .= "		</div>";
		
		if(!empty($cond))
			$conditions = array("conditions"=>$cond, "order"=>"code, id_complexe");
		else
			$conditions = array("order"=>"code, id_complexe");
		
		$data .= $propriete->Search(["conditions"=>$conditions, "use"=>"v_propriete"]);

		
		
		/*
		$data .= "		<table class='table'><thead><tr><th style='width:120px'>CODE</th><th>COMPLEXE</th><th style='width:69px; max-width:69px'></th></tr></thead><tbody>";
		
		foreach($propriete->find(null,$conditions,'v_propriete') as $k=>$v){
			$data .= "		<tr><td style='background-color:rgba(0,255,0,0.1); font-weight:bold; font-size:12px'>".$v['code']."</td><td>".$v['name']."</td><td><button data-numero='".$v['appartement_number']."' data-id='".$v['id']."' data-zone='".$v['zone_number']."' data-bloc='".$v['bloc_number']."' data-complexe='".$v['name']."' data-code='".$v['code']."' style='width:100%;padding:5px' class='btn btn-green select_this_propriete'><i class='fas fa-check'></i> </button></td><tr>";
		}
		$data .= "		</tbody></table>";
		
		*/
		
		//$data .= $propriete->drawTable($columns,$conditions, "v_propriete");
		
		$data .= "	</div>";
		$data .= "	<div class='panel-content'>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;

	case "add":
		
		require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Formulaire<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; padding-bottom:15px'>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='c_a_montant'>Montant </label>";
		$data .= "  			<input type='number' id='c_a_montant' placeholder='Montant' value='0.00' style='font-size:14px; text-align:center'>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "			<div class='col_12'>";
		$data .= "  			<label for='c_a_source'>Source </label>";
		$data .= "  			<input type='text' id='c_a_source' placeholder='Source' >";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "  		<div class='col_12'>";
		$data .= "  			<label for='c_a_notes'>Notes </label>";
		$data .= "  			<textarea id='c_a_notes' style='max-width:100%; height:120px'></textarea>";
		$data .= "			</div>";
		$data .= "		</div>";
	
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green c_a_save' value='".$_POST["options"]["id"]."' data='".$_POST["options"]["id_caisse"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
		
	case "edit":
		
		require_once($core.'Caisse_Alimentation.php');
		$caisse_alimentation->id = $_POST["options"]["id"];
		$d = $caisse_alimentation->read()[0];
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Formulaire<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; padding-bottom:15px'>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='c_a_montant'>Montant </label>";
		$data .= "  			<input type='number' id='c_a_montant' placeholder='Montant' value='".$d["montant"]."' style='font-size:14px; text-align:center'>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "			<div class='col_12'>";
		$data .= "  			<label for='c_a_source'>Source </label>";
		$data .= "  			<input type='text' id='c_a_source' placeholder='Source' value='".$d["source"]."' >";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "  		<div class='col_12'>";
		$data .= "  			<label for='c_a_notes'>Notes </label>";
		$data .= "  			<textarea id='c_a_notes' style='max-width:100%; height:120px'>".$d["notes"]."</textarea>";
		$data .= "			</div>";
		$data .= "		</div>";
	
		$data .= "  	<div class='row' style='margin-top:10px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green c_a_save edit' value='".$_POST["options"]["id"]."' data='0'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
}



echo json_encode($response);
