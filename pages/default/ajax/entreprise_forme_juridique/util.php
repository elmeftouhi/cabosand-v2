<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){

	case "caisse_alementation":
		$columns = array(
			array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
			array("column" => "created", "label"=>"CREATION", "style"=>"min-width:80px; width:130px"),
			array("column" => "created_by", "label"=>"AJOUTE PAR"),
			array("column" => "montant", "label"=>"MONTANT", "style"=>"min-width:160px; width:160px; background-color:#DCEDC8; font-weight:bold; font-size:16px; text-align:right"),
			array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px")
		);
		
		require_once($core.'Caisse_Alimentation.php');

		$conditions = array("conditions"=>array("id_caisse =" => $_POST["options"]["id_caisse"] ), "order"=>"created desc");

		$data  = $caisse_alimentation->drawTable($columns,$conditions);
		
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
