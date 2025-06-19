<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");


if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['module'])){$response["msg"]="Error Data"; die(json_encode($response));}



$core = $_SESSION['CORE'];

$module = $_POST["module"];
switch ($module){
		
	case "client_by_complexe":
		$id_complexe = 	$_POST["id_complexe"];
		require_once($core."Contrat.php");
		$request = "
select client.first_name, client.last_name, client.societe_name as client , v_propriete.name as complexe_name, v_propriete.id_complexe,contrat.UID as UID
from client
join contrat on contrat.id_client = client.id
JOIN propriete_location on propriete_location.UID = contrat.UID AND propriete_location.source='contrat'
JOIN v_propriete on propriete_location.id_propriete = v_propriete.id
where v_propriete.id_complexe=".$id_complexe." group by client.societe_name order by client.societe_name";
		//echo $request;
		$data = $contrat->execute($request);
		
		$reaturned = '<option selected value="-1">-- Client --</option>';
		foreach($data as $k=>$v){
			$reaturned .= '<option value="'.$v["UID"].'">'.$v["client"].'</option>';
		}
		$response  = array("code"=>1, "msg"=>$reaturned);
	break;
		
	case "calendar":
		$contrat_UID = 	$_POST["UID"];
		require_once($core."Contrat.php");
		$data = $contrat->find("",array("conditions"=>array("UID="=>$contrat_UID)), "v_complexe_by_contrat");
		$reaturned = '<option selected value="-1">-- Complexe --</option>';
		foreach($data as $k=>$v){
			$reaturned .= '<option value="'.$v["id_complexe"].'">'.$v["complexe_name"].' ('.$v["nbr_app"].')</option>';
		}
		$response  = array("code"=>1, "msg"=>$reaturned);
	break;
		
	case "add":
		
		$IDS = $_POST["IDS"];
		$UID = $_POST["UID"];
		require_once($core."Propriete_Location.php");
		
		$data = $propriete_location->find(null,array("conditions"=>array("UID="=>$UID)),"v_contrat_periode");
		foreach($data as $k=>$v){
			foreach($IDS as $kk=>$vv){
				$data = array(
					"UID"			=>		$UID,
					"source"		=>		"contrat",
					"date_debut"	=>		$v["date_debut"],
					"date_fin"		=>		$v["date_fin"],
					"id_propriete"	=>		$vv,
					"id_periode"	=>		$v["id"],
					"status"		=>		$v["status"]
				);
				
				$code = $propriete_location->find(null,array("conditions"=>array("id="=>$vv)),"propriete");
				
				$propriete_location->save($data);
				
				
				$lastID = $propriete_location->getLastID();
				
				$msg = $v['date_debut'] . " " . $v['date_fin'] . " : " . $v['first_name'] . " " . $v['last_name']. " ";
				
				$msg .= (count($code)>0)? $code[0]["code"]: "";
				$propriete_location->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete_Location","1"),$lastID,$msg);
			}

		}
		
		$response  = array("code"=>1, "msg"=>"Saved");
		
	break;
		
	case "propriete":
		
		$request = (isset($_POST["request"]["code"]))? $_POST["request"]["code"]:"";
		$complexe = (isset($_POST["request"]["complexe"]))? $_POST["request"]["complexe"]:"";
		$UID = $_POST["UID"];
		
		require_once($core.'Propriete.php');
		$periodes = $propriete->find("",array("conditions"=>array("UID="=>$UID)),"contrat_periode");
		$listOfPropriete = array();
		$onePropriete = array();
		
		foreach($periodes as $k=>$v){	
			$params = array(
				"by_proprietaire"	=>	array(
						"date_debut"	=>	$v["date_debut"],
						"date_fin"		=>	$v["date_fin"],
						"code"			=>	$request,
						"complexe"		=>	$complexe
							)
			);
			$onePropriete = $propriete->getProprieteDisponible($params);
			
			if($k===0){
				$listOfPropriete = $onePropriete;
			}
			$listOfPropriete = array_intersect($listOfPropriete,$onePropriete);
		}
		
		$complexes = array();
		$proprietes = array();
		
		foreach($listOfPropriete as $k=>$v){
			$temp = json_decode($v,true);
			
			if(!array_key_exists($temp["complexe"],$complexes)){
				$complexes[$temp["complexe"]] = 1;
			}else{
				$complexes[$temp["complexe"]] = $complexes[$temp["complexe"]] + 1;
			}
			
			//var_dump($temp);
		}
		
		$data = "<div class='panel' style='width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Selectionnez Appartement : <span class='UID' style='font-size:10px'>". $UID ."</span><span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0; width:100%; z-index: 999999'>";
		$data .= "		<div class='row' style='padding:5px 2px'>";
		$data .= "			<div class='col_3-inline' style='padding:0'><input value='".$request."' type='text' id='_r' style='border-radius:0; border-right:0'></div>";
		$data .= "			<div class='col_5-inline' style='padding:0'>";
		$data .= "				<select id='_complexe'>";
		$data .= "					<option value='-1'>-- Complexe </option>";

		foreach($complexes as $k=>$v){
			if( $complexe !== "" ){
				$s = ($complexe == $k)? "selected":"";
				$data .= "				<option ".$s." value='".$k."'>".$k." ( " . $v . " )</option>";
			}else{
				$data .= "				<option value='".$k."'>".$k." ( " . $v . " )</option>";
			}
		}
		
		$data .= "				</select>";
		$data .= "			</div>";
		$data .= "			<div class='col_1-inline' style='padding:0'><button value='".$UID."' style='width:100%; margin:0; padding:8px; border-radius:0' class='btn btn-default __search'><i class='fas fa-search'></i></button></div>";
		$data .= "			<div class='col_3-inline' style='padding:0'><button value='".$UID."' style='width:100%; margin:0; padding:7px; border-radius:0' class='btn btn-green _select_this_propriete'>Select</button></div>";
		$data .= "		</div>";
		
		$data .= '			<table class="table">';
		$data .= '				<thead>';
		$data .= '					<tr>';
		$btn = '<span class="is_doing hide"><i class="fas fa-sync fa-spin"></i> ...</span><span class="do">Check All</span>';
		$data .= "						<th style='width:50px'><input type='checkbox' class='propriete_checked_all'></th><th>CODE</th><th>COMPLEXE ".count($listOfPropriete)."</th><th style='width:90px; max-width:90px; font-size:12px'><button style='padding:5px 5px;margin:0' class='btn btn-orange check_all'>".$btn."</button></th>";
		$data .= '					</tr>';
		$data .= '				</thead>';
		$data .= '				<tbody>';
		
		foreach($listOfPropriete as $k=>$v){
			$temp = json_decode($v,true);
			$data .= '					<tr>';
			$btn = '<span class="is_doing hide"><i class="fas fa-sync fa-spin"></i> ...</span><span class="do">Check </span>';
			$data .= "						<td><input type='checkbox' disabled class='propriete_checked' data-id='".$temp["id_propriete"]."'></td><td>".$temp["code"]."</td><td>".$temp["complexe"]."</td><td><button class='btn btn-default propriete_verify' style='padding:3px 2px' data-id='".$temp["id_propriete"]."' data-uid='".$UID."'>".$btn."</button></td>";
			$data .= '					</tr>';
		}
		
		$data .= '				</tbody>';
		$data .= '			</table>';
		//$data .= $propriete->drawTable($columns,$conditions, "v_propriete");
		
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
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' data-date-format='YYYY-MM-DD' placeholder='AAAA-MM-DD' id='periode_de' value='".date('Y')."-01-01'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' data-date-format='YYYY-MM-DD' placeholder='AAAA-MM-DD' id='periode_a' value='".date('Y')."-12-31'>";
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
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' data-date-format='YYYY-MM-DD' placeholder='AAAA-MM-DD' id='periode_de' value='".$d['date_debut']."'>";
		$data .= "  		</div>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<label for='periode_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
		$data .= "  			<input style='text-align:center; font-size:14px; font-weight:bold' type='date' data-date-format='YYYY-MM-DD' placeholder='AAAA-MM-DD' id='periode_a' value='".$d['date_fin']."'>";
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
		
		
	case "propriete_edit":
		
		require_once($core.'Propriete_Location.php');
		
		$propriete_location->id = $_POST["options"]["id"];
		$p_l = $propriete_location->read()[0];
		
		
		
		$d = $propriete_location->find("", array("conditions AND"=>array(
			"id_propriete="		=>	$p_l["id_propriete"],
			"UID="				=>	$p_l["UID"],
			"source="			=>	"contrat")), "propriete_location");
		//var_dump($d);
		//die();
		//require_once($core.'Caisse_Alimentation.php');
		
		$data = "<div class='panel' style='overflow:auto; width:100%; z-index: 999999'>";
		$data .= "	<div class='panel-header' style='padding-right:0'>Période <span style='font-size:10px; color:green'>" . $p_l["UID"] . "</span><span class='_close'><button class='btn btn-default btn-red'>Fermer</button></span></div>";
		$data .= "	<div class='panel-content' style='padding: 0'>";
				
		foreach($d as $k=>$v){
			$status = ($v["status"])? "checked":"";
			$data .= "  	<div class='row p_l' style='margin-top:20px; border:1px #FCC8C8 solid; border-radius:5px; margin:15px 5px 0px 5px; padding-bottom:10px; background-color:#FAF2F2'>";
			$data .= "  		<div><input type='checkbox' ".$status." class='p_l_status' data-id='".$v["id"]."'> </div>";
			$data .= "  		<div class='col_6-inline'>";
			$data .= "  			<label for='p_l_de'>De <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
			$data .= "  			<input style='text-align:center; font-size:12px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' class='p_l_de' id='p_l_de' value='".$v['date_debut']."'>";
			$data .= "  		</div>";
			$data .= "  		<div class='col_6-inline'>";
			$data .= "  			<label for='p_l_a'>A <span style='font-size:10px'>(JJ/MM/AAAA)</span></label>";
			$data .= "  			<input style='text-align:center; font-size:12px; font-weight:bold' type='date' placeholder='AAAA-MM-DD' class='p_l_a' id='p_l_a' value='".$v['date_fin']."'>";
			$data .= "  		</div>";
			$data .= "		</div>";
			
		}
		
		$data .= "  	<div class='row' style='margin-top:20px; padding:10px 0;background: #fafafa; border-top:#ccc 1px solid '>";
		$data .= "  		<div class='col_6-inline'>";
		$data .= "  			<button class='btn btn-green p_l_save' data-uid='".$p_l["UID"]."' value='".$_POST["options"]["id"]."'><i class='fas fa-save'></i> Enregistrer</button>";
		$data .= "  		</div>";
		$data .= "		</div>";
		
		$data .= "	</div>";
		$data .= "	</div>";
		
		$response  = array("code"=>1, "msg"=>$data);

	break;
		
	case "propriete_remove":
		
		require_once($core.'Propriete_Location.php');
		
		$propriete_location->id = $_POST["options"]["id"];
		$p_l = $propriete_location->read()[0];
		
		$con = array(
			"UID="			=>		$p_l["UID"],
			"id_propriete="	=>		$p_l["id_propriete"],
			"source="		=>		"contrat"
		);
		$d = $propriete_location->find(null,array("conditions AND"=>$con),"v_propriete_location");
		foreach($d as $k=>$v){
			
			$propriete_location->delete($v["id"]);
			
			$lastID = $v["id"];

			$msg = $v['date_debut'] . " -> " . $v['date_fin'] . " App : " . $v["code"];

			$propriete_location->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete_Location","-1"),$lastID,$msg);
			
		}
		
		
		$response  = array("code"=>1, "msg"=>"removed");

	break;
		
	case "propriete_check":
		$is_exist = false;
		
		$UID = $_POST["options"]["UID"];
		$id = $_POST["options"]["id"];
		
		require_once($core.'Propriete.php');
		$p_l = $propriete->find(null,array("conditions"=>array("UID="=>$UID)),"contrat_periode");
		foreach($p_l as $k=>$v){
			$params = array(
				"by_periode"	=>	array(
						"date_debut"	=>	$v["date_debut"],
						"date_fin"		=>	$v["date_fin"],
						"id_propriete"	=>	$id
							)
			);
			if(count($propriete->getProprieteDisponible($params)) >0) $is_exist=true;
			
		}
		$response = array("code"=>1, "msg"=>$is_exist);
		
	break;
		
}
/*
echo $response["msg"];
die();
*/
echo json_encode($response);
