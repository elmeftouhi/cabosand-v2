<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){
		
	case "add":
		$response  = array("code"=>1, "msg"=>"success");
	break;
		
	case "propriete":
		$columns = array(
			array("column" 	=> "code", "label"=>"CODE"),
			array("column" 	=> "name", "label"=>"COMPLEXE"),
			"option"		=>	1
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
		$data .= "	<div class='panel-header' style='padding-right:0'>Selectionnez Appartement ". $_POST["UID"] ."<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
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
		$data .= "			<div class='col_2-inline' style='padding:0'><button style='width:100%; margin:0; padding:8px; border-radius:0' class='btn btn-default __search'><i class='fas fa-search'></i></button></div>";
		$data .= "		</div>";
		
		if(!empty($cond))
			$conditions = array("conditions"=>$cond, "order"=>"code, id_complexe");
		else
			$conditions = array("order"=>"code, id_complexe");
		
		$data .= $propriete->drawTable($columns,$conditions, "v_propriete");
		
		$data .= "	</div>";
		$data .= "	<div class='panel-content'>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;

	case "periode":
		
		//require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Période<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<h3 style='margin-left:10px'>Contrat Période</h3>";
				
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_de'>De <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_de' value='".date('Y')."-01-01'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_a' value='".date('Y')."-12-31'>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "  			<label for='periode_nuite'>Nombre de Nuité</label>";
		$data .= "				<input style='text-align:center; font-size:14px; font-weight:bold' id='periode_nuite' type='number' placeholder='0' value=''>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 165px'>";
		$data .= "					<div class='on_off off' id='periode_status'></div>";
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Activé / Désactivé ";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green periode save' value='".$_POST["options"]["id"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
		
	case "periode_edit":
		
		require_once($core.'Contrat_Periode.php');
		$contrat_periode->id = $_POST["options"]["id"];
		$d = $contrat_periode->read()[0];
		
		//require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Période<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<h3 style='margin-left:10px'>Contrat Période</h3>";
				
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_de'>De <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_de' value='".$d['date_debut']."'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_a' value='".$d['date_fin']."'>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "  			<label for='periode_nuite'>Nombre de Nuité</label>";
		$data .= "				<input style='text-align:center; font-size:14px; font-weight:bold' id='periode_nuite' type='number' placeholder='0' value='".$d['nbr_nuite']."'>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 165px'>";
		$on_off = "off";
		if($d['status']) $on_off = "on";
		$data .= "					<div class='on_off ".$on_off."' id='periode_status'></div>";
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Activé / Désactivé ";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green periode save edit' data-uid='".$d['UID']."' value='".$_POST["options"]["id"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
}



echo json_encode($response);
