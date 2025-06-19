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

	case "proprietaire":
		$columns = array(
			array("column" => "name", "label"=>"PROPRIETAIRE"),
			array("column" => "ville", "label"=>"VILLE")
		);
		
		require_once($core.'Proprietaire.php');
		
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Client <span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
		$data .= $proprietaire->drawTable($columns);
		$data .= "	</div>";
		$data .= "	<div class='panel-content'>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;

	case "location":

		$is_edit = false;
		$current_year = date("Y");
		$start_year = 2018;
		
		if(isset($_POST["options"]) and !empty($_POST["options"])){
			require_once($core.'Propriete_Proprietaire_Location.php');
			$ob = new Propriete_Proprietaire_Location;
			$ob->setID($_POST["options"]["id"]);
			$d = $ob->find("",array("conditions"=>array("id="=>$_POST["options"]["id"])),"v_propriete_proprietaire_location");
			if(count($d)>0){
				$is_edit=true;
			}
		}
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Formulaire<span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; padding-bottom:15px'>";
		$data .= "		<h3 style='margin-left:10px'>Appartement Pricing</h3>";
		
		/*
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_year'>Année </label>";
		$data .= "  			<select id='p_p_year'>";
		for($i=0; $i<10; $i++){
			
			if(!$is_edit){
				
				if(intval($current_year) === intval(($start_year+$i))){
			$data .= "  				<option selected value='".$current_year."'>".$current_year."</option>";		
				}else{
			$data .= "  				<option value='".($start_year+$i)."'>".($start_year+$i)."</option>";	
				}
				
			}else{
				
				if(intval($d[0]["annee"]) === intval(($start_year+$i))){
			$data .= "  				<option selected value='".$d[0]["annee"]."'>".$d[0]["annee"]."</option>";
				}else{
			$data .= "  				<option value='".($start_year+$i)."'>".($start_year+$i)."</option>";
				}
				
			}

		}
		$data .= "  			</select>";
		$data .= "			</div>";
		$data .= "		</div>";
		*/
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_de'>De <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$de = (!$is_edit)? date('Y')."-01-01'" : $d[0]["de"];
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_de' value='". $de ."'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$a = (!$is_edit)? date('Y')."-12-31'" : $d[0]["a"];
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' id='periode_a' value='".$a."'>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "  			<label for='periode_nuite'>Nombre de Nuité</label>";
		$nbr_nuite = (!$is_edit)? "" : $d[0]["nbr_nuite"];
		$data .= "				<input style='text-align:center; font-size:14px; font-weight:bold' id='periode_nuite' type='number' placeholder='0' value='".$nbr_nuite."'>";
		$data .= "			</div>";					
		$data .= "		</div>";
		
		/*
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_12'>";
		$data .= "  			<label for='p_p_name'>Nom Tarif </label>";
		
		if($is_edit)
		$data .= "  			<input id='p_p_name' type='text' placeholder='Nom Tarif' value='".$d[0]['name']."'>";
		else
		$data .= "  			<input id='p_p_name' type='text' placeholder='Nom Tarif'>";
		
		$data .= "			</div>";
		$data .= "		</div>";
		*/
		
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_montant'>Montant </label>";
		
		if($is_edit)
		$data .= "  			<input type='text' placeholder='0.00' id='p_p_montant' value='".$d[0]['montant']."'>";
		else
		$data .= "  			<input type='text' placeholder='0.00' id='p_p_montant'>";
		
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_type'>Type </label>";
		$data .= "  			<select id='p_p_type'>";
		
		if(!$is_edit){
		$data .= "  				<option selected value='-1'></option>";
		$data .= "  				<option value='1'>Par Nuit</option>";
		$data .= "  				<option value='2'>Par Mois</option>";
		$data .= "  				<option value='3'>Forfait</option>";
		}else{
			if($d[0]["id_propriete_location_type"] === "1")
		$data .= "  				<option selected value='1'>Par Nuit</option>";
			else
		$data .= "  				<option value='1'>Par Nuit</option>";
			
		if($d[0]["id_propriete_location_type"] === "2")
		$data .= "  				<option selected value='2'>Par Mois</option>";
			else
		$data .= "  				<option value='2'>Par Mois</option>";
		
			if($d[0]["id_propriete_location_type"] === "3")
		$data .= "  				<option selected value='1'>Forfait</option>";
			else
		$data .= "  				<option value='3'>Forfait</option>";	
		}
		
		$data .= "  			</select>";
		$data .= "			</div>";
		$data .= "		</div>";
		
		/*
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
		
		if($is_edit)
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_de' value='".$d[0]["de"]."'>";
		else
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_de' value='".date('Y')."-01-01'>";
		
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='p_p_periode_a'>A <span style='font-size:10px'>(AAAA-MM-JJ)</span></label>";
		
		if($is_edit)
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_a' value='".$d[0]["a"]."'>";
		else
		$data .= "  			<input type='text' placeholder='AAAA-MM-DD' id='p_p_periode_a' value='".date('Y')."-12-31'>";
		
		$data .= "  		</div>";
		$data .= "		</div>";
		*/
		
		$data .= "		<div class='row' style='margin-top: 20px'>";
		$data .= "			<div class='col_6-inline'>";
		$data .= "				<div style='position: relative; width: 165px'>";
		if($is_edit){
			if($d[0]["status"] === "1"){
		$data .= "					<div class='on_off on' id='p_p_status'></div>";
			}else{
		$data .= "					<div class='on_off off' id='p_p_status'></div>";
			}
		}else{
		$data .= "					<div class='on_off off' id='p_p_status'></div>";	
		}
		
		$data .= "					<span style='position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px'>";
		$data .= "				  		Activé / Désactivé";
		$data .= "					</span>";
		$data .= "				</div>";
		$data .= "			</div>";					
		$data .= "		</div>";
	
		$data .= "  	<div class='row' style='margin-top:20px'>";
		$data .= "  		<div class='col_6-inline'>";
		if($is_edit)
		$data .= "  			<button data='".$d[0]['id_propriete']."' class='btn btn-green p_p_save edit' value='".$d[0]['id']."'><i class='fas fa-save'></i> Enregistrer</button>";
		else
		$data .= "  			<button data='".$_POST["options"]["id_propriete"]."' class='btn btn-green p_p_save'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
}



echo json_encode($response);
