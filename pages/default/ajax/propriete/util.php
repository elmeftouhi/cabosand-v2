<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}

$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){

	case "complexe":
		
		if(isset($_POST["options"]) and !empty($_POST["options"])){
			$option = $_POST["options"];
			require_once($core.'Complexe.php');
			$complexe->setID($option);
			$data = $complexe->read();
			if(count($data)>0){
				$returned = $data[0]["ABR"]."-0000";
				$response["msg"] = $returned;
				$response["ville"] = $data[0]["ville"];
				$response["adresse"] = $data[0]["adresse"];
				$response["code"] = 1;
			}else{
				$returned = "not defined";
				$response["msg"] = $returned;
				$response["ville"] = $returned;
				$response["adresse"] = $returned;
				$response["code"] = 0;

			}
		}


	break;

		
	case "is_exist" :
		
		if(isset($_POST["code"])){
			$code = $_POST["code"];
			require_once($core.'Propriete.php');
			$data = $propriete->find(null,array("conditions"=>array("code="=>$code)),null);
			if(count($data)>0){
				$response  = array("code"=>1, "msg"=> $code . " Existe déjà!");
			}else{
				$response  = array("code"=>0, "msg"=> $code . " n\'existe pas!");
			}
		}

		break;
		
	case "proprietaire":
		$columns = array(
			array("column" => "name", "label"=>"PROPRIETAIRE"),
			array("column" => "ville", "label"=>"VILLE")
		);
		
		require_once($core.'Proprietaire.php');
		
		$request = (isset($_POST["request"])? $_POST["request"]:"");
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Client <span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= "		<div class='row' style='padding:5px 2px'>";
		$data .= "			<div class='col_10-inline' style='padding:0'><input value='".$request."' type='text' id='_r' style='border-radius:0; border-right:0'></div>";
		$data .= "			<div class='col_2-inline' style='padding:0'><button style='width:100%; margin:0; padding:8px; border-radius:0' class='btn btn-default _s'><i class='fas fa-search'></i></button></div>";
		$data .= "		</div>";
		
		if($request !== "")
			$conditions = array("conditions"=>array("LOWER(name) like "=>"%".strtolower($request)."%"), "order"=>"name");
		else
			$conditions = array("order"=>"name");
		
		$data .= $proprietaire->drawTable($columns,$conditions);
		
		$data .= "	</div>";
		$data .= "	<div class='panel-content'>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;

	case "location":
		$columns = array(
			array("column" => "name", "label"=>"PROPRIETAIRE"),
			array("column" => "ville", "label"=>"VILLE")
		);
		$current_year = date("Y");
		require_once($core.'Proprietaire.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Formulaire<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; padding-bottom:15px'>";
		$data .= "		<h3 style='margin-left:10px'>Appartement Pricing</h3>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_year'>Année </label>";
		$data .= "  			<select id='p_p_year'>";
		for($i=0; $i<10; $i++){
			if($current_year === (2019+$i)){
		$data .= "  				<option selected value='".$current_year."'>".$current_year."</option>";		
			}else{
		$data .= "  				<option value='".(2019+$i)."'>".(2019+$i)."</option>";	
			}
		}
		$data .= "  			</select>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12'>";
		$data .= "  			<label for='p_p_name'>Nom Tarif </label>";
		$data .= "  			<input id='p_p_name' type='text' placeholder='Nom Tarif'>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_montant'>Montant </label>";
		$data .= "  			<input type='text' placeholder='0.00' id='p_p_montant'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_type'>Type </label>";
		$data .= "  			<select id='p_p_type'>";
		$data .= "  				<option selected value='-1'></option>";
		$data .= "  				<option value='1'>Par Nuit</option>";
		$data .= "  				<option value='2'>Par Mois</option>";
		$data .= "  				<option value='3'>Forfait</option>";
		$data .= "  			</select>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12'>";
		$data .= "  			<div class='btn-group-radio'>";
		$data .= "  				<button class='btn btn-default p_p_periode' data='0' style='padding:5px 7px'>Période</button>";
		$data .= "  				<button class='btn btn-default checked p_p_periode' data='1' style='padding:5px 7px'>Toute l'Année</button>";
		$data .= "  			</div>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_periode_de'>De <span style='font-size:10px'>(AAAA-MM-JJ)</span></label>";
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_de' value='".date('Y')."-01-01'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_periode_a'>A <span style='font-size:10px'>(AAAA-MM-JJ)</span></label>";
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_a' value='".date('Y')."-12-31'>";
		$data .= "  		</div>";
		$data .= "		</div>";
	
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 165px'>";
		$data .= "					<div class='on_off off' id='p_p_status'></div>";
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Activé / Désactivé ";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
	
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green p_p_save' value='".$_POST["options"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
}



echo json_encode($response);
