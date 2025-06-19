<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Propriete_Proprietaire_Location extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "name", "label"=>"NOM"),
		array("column" => "annee", "label"=>"ANNEE", "width"=>120),
		array("column" => "id_propriete_location_type", "label"=>"TYPE", "width"=>90),
		array("column" => "montant", "label"=>"MONTANT", "width"=>90),
		array("column" => "status", "label"=>"STATUS", "width"=>90)
	);
	
	private $tableName = __CLASS__;
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
	
	public function getColumns($style = null){
		
		$style = (is_null($style))? strtolower($this->tableName): $style;
		
		$columns = array();
		$l = new ListView();
		foreach($l->getDefaultStyle($style, $this->columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
	
//	Draw Table
	public function drawTable($args = null, $conditions = null, $useTableName = null){

		$status = array("<div class='label label-red'>Désactivé</div>", "<div class='label label-green'>Activé</div>");		
		
		
		$values = array("Error : " . $this->tableName);
		$t_n = ($useTableName===null)? strtolower($this->tableName): $useTableName;
		
		$values = $this->find(null,$conditions,$t_n);
		
		$returned = '<div class="row">';
		$returned .= '	<div class="col_12" style="padding: 0">';
		$returned .= '			<table class="table">';
		$returned .= '				<thead>';
		$returned .= '					<tr>';
		
		$columns = $this->getColumns();

		foreach($columns as $key=>$value){

			$style = ""; 
			$is_sort = "";
			$is_display = ( $value["display"] )? "" : "hide";

			$returned .= "<th class='".$is_sort. " ". $is_display . "' data-sort='" . $value['column'] . "'>" . $value['label'] . "</th>";

		}
		$returned .= '					</tr>';
		$returned .= '				</thead>';
		$returned .= '				<tbody>';
		
		
		$content = '<div class="info info-success"><div class="info-success-icon"><i class="fa fa-info" aria-hidden="true"></i> </div><div class="info-message">Liste vide ...</div></div>';
		$i = 0;
		
		$t = explode("_",$this->tableName);
		$_t = "";
		foreach ($t as $k=>$v){
			$_t .= ($_t==="")? ucfirst($v): "_".ucfirst($v) ;
		}
		
		foreach($values as $k=>$v){
			$returned .= '					<tr class="edit_ligne_p_p_l" data="'.$v["id"].'" data-page="'.$_t.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
				
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "id"){
						$returned .= "<td style='".$style."'><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
					}elseif($columns[$key]["column"] == "status"){
						$returned .= "<td style='".$style."'>".$status[$v["status"]]."</td>";
					}elseif($columns[$key]["column"] == "montant"){
						$returned .=  "<td style='".$style."'>" . $this->format($v["montant"]) . "</td>";
					}elseif($columns[$key]["column"] == "id_propriete_location_type"){
						if($v["id_propriete_location_type"] === "1"){
							$returned .=  "<td style='".$style."'>NUITE</td>";
						}elseif($v["id_propriete_location_type"] === "2"){
							$returned .=  "<td style='".$style."'>MOIS</td>";
						}else{
							$returned .=  "<td style='".$style."'>FORFAIT</td>";
						}
					}else{
						$returned .= "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
					}											
				}else{
					if($columns[$key]["column"] == "actions"){
						$returned .=   "<td style='".$style."'><button style='margin-right:10px' data-page='".$_t."' class='btn btn-red remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$_t."' class='btn btn-orange edit_ligne_p_p_l' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
					}elseif($columns[$key]["column"] == "nbr"){
						$returned .=  "<td style='".$style."'>0</td>";
					}elseif($columns[$key]["column"] == "total"){
						if($v["id_propriete_location_type"] === "1"){
							$returned .=  "<td style='".$style."'>" . $this->format($v["nbr_nuite"] * $v["montant"]) . "</td>";
						}else{
							$returned .=  "<td style='".$style."'>" . $this->format($v["montant"]) . "</td>";
						}
						
					}else{
						$returned .=  "<td style='".$style."'>---</td>";
					}

				}


			}
			$returned .= '					</tr>';
		$i++	;
		}
	
		if($i == 0){
			$returned .= "<tr><td colspan='" . (count($columns)+1) . "'>".$content."</td></tr>";
		}
		
	
		$returned .= '				</tbody>';
		$returned .= '			</table>';
		$returned .= '		</div>';
		$returned .= '	</div>';

		echo $returned;

	}

	public function Create($params){
		$id_propriete = $params["id_propriete"];
		$view = new View("propriete_proprietaire_location.create");
		return $view->render(['id_propriete'=>$id_propriete, 'year'=>date('Y')]);
	}
	
	public function Store($params){
		
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		$data = array(
			"created_by"					=>	$created_by,
			"created"						=>	$created,
			"updated"						=>	$created,
			"updated_by"					=>	$created_by,
			"montant"						=>	$params["montant"],
			"id_propriete_location_type"	=>	$params["id_propriete_location_type"],
			"de"							=>	$params["de"],
			"a"								=>	$params["a"],
			"status"						=>	$params["status"],
			"id_propriete"					=>	$params["id_propriete"]
		);
	
		if( isset( $params["id"]) ){
			$data["id"] = $params["id"];
			unset($data["created_by"]);
		}else{
			unset($data["updated"], $data["updated_by"]);
		}
	
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "de: " . $data["de"] . " a: " . $data["a"];
				$this->saveActivity("fr", $created_by, ['Propriete_Proprietaire', 0], $data["id"], $msg);				
			}else{
				$msg = "de: " . $data["de"] . " a: " . $data["a"];
				$this->saveActivity("fr", $created_by, ['Propriete_Proprietaire', 1], $this->getLastID(), $msg);
			}

			return 1;
			
		}else{
			return $this->err;
		}
		
	}
	
	public function Update($params){
		
		$push = [];
		
		$ppl = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'v_propriete_proprietaire_location');		
		if( count($ppl) > 0 ){
			$push['ppl'] = $ppl[0];
		}
		
		
		$view = new View("propriete_proprietaire_location.create");
		return $view->render($push);
	}
	
	
	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				
				$data = $data[0];
				$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
				$msg = "periode: " . $data["de"] . " / " . $data["a"];

				$this->delete($params["id"]);

				$this->saveActivity("fr", $created_by, ['Propriete_Proprietaire', -1], $data["id"], $msg);

				return 1;
				

			}else{
				return 0;
			}

		}else{
			return 0;
		}
	}
	
	public function Table($params = []){
		
		
		$ppl = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'created DESC'], 'v_propriete_proprietaire_location');
		$cl_location = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'date_debut DESC'], 'v_propriete_location_1');
		$template = '
		<div id="popup" class="pb-20" style="width:520px; margin:50px auto">
			<div class="popup-header d-flex space-between">
				<div class="">Liste des Contrat</div>
				<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
			</div>
			<div class="ppl_wrapper" style="overflow:auto; max-height:450px">
				<div class="popup-content ppl" style="padding-bottom:0px">
					<div class="header d-flex space-between mb-10">
						<div class="title" style="font-weight:bold; padding-top:7px">Contrats envers Propriétaire</div>
						<div class="">
							<button class="add green" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> Ajouter</button>
							<button class="refresh hide" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> ref</button>
						</div>
					</div>
					<div class="ppl-add-container"></div>
					<div class="body">
						<table>
							<thead>
								<tr>
									<th>PERIODE</th>
									<th>TYPE</th>
									<th>MONTANT</th>
									<th>STATUS</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								{{trs}}
							</tbody>
						</table>
					</div>

				</div>


				<div class="popup-content ppc" style="padding-bottom:10px">
					<div class="header d-flex space-between mb-10">
						<div class="title" style="font-weight:bold; padding-top:7px">Contrats envers Client</div>
						<div class="">
							<button class="add green" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> Ajouter</button>
							<button class="ppc_abort hide"><i class="far fa-times-circle"></i> Annuler</button>
						</div>
					</div>
					<div class="ppc-add-container"></div>
					<div class="body">
						<table>
							<thead>
								<tr>
									<th>DEBUT</th>
									<th>CLIENT</th>
									<th>STATUS</th>
								</tr>
							</thead>

							<tbody>
								{{trs_location}}
							</tbody>
						</table>
					</div>

				</div>			
			</div>

			
		</div>
		';
		
		$trs = '';
		
		
		foreach($ppl as $k=>$v){
			$type = 'Par Nuit';
			$type = ($v["id_propriete_location_type"] === "1")? $type: ($v["id_propriete_location_type"] === "2"? "Par Mois": "Forfait");
			$status = ($v["status"] == "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs .= '
							<tr>
								<td>
									<div class="d-flex ppl-periode">
										<div>'.$v["de"].'</div>
										<div class="pl-5 pr-5 text-red" style="font-size:16px">[ '.$v["nbr_nuite"].' ]</div>
										<div>'.$v["a"].'</div>
									</div>
								</td>
								<td>'.$type.'</td>
								<td style="text-align:right; font-weight:bold;">'. $this->format( $v["montant"] ).'</td>
								<td class="text-center">'.$status.'</td>
								<td style="width:50px; text-align:center">
									<button class="ppl-update" value="' . $v["id"] . '">
										<i class="fas fa-ellipsis-v"></i>
									</button>
								</td>
							</tr>
			';
		}
		
		
		$trs_location = '';
		
		
		foreach($cl_location as $k=>$v){

			$status = ($v["status"] === "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs_location .= '
							<tr>
								<td>
									<div class="d-flex ppl-periode">
										<div>'.$v["date_debut"].'</div>
										<div class="pl-5 pr-5 text-red" style="font-size:16px">[ '.$v["nbr_nuite"].' ]</div>
										<div>'.$v["date_fin"].'</div>
									</div>
								</td>
								<td>'.$v["client_first_name"]. " " . $v["client_last_name"].'</td>
								<td class="text-center">'.$status.'</td>
							</tr>
			';
		}
		
		
		return str_replace(["{{trs}}", "{{trs_location}}"], [$trs, $trs_location], $template);
	}
	
	public function TableShort($params = []){
		
		$ppl = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'created DESC'], 'v_propriete_proprietaire_location');
		
		$trs = '';
		$type = 'Par Nuit';
		
		foreach($ppl as $k=>$v){
			
			$type = ($v["id_propriete_location_type"] === "1")? $type: ($v["id_propriete_location_type"] === "2"? "Par Mois": "Forfait");
			$status = ($v["status"] === "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs .= '
							<tr>
								<td>
									<div class="d-flex ppl-periode">
										<div>'.$v["de"].'</div>
										<div class="pl-5 pr-5 text-red" style="font-size:16px">[ '.$v["nbr_nuite"].' ]</div>
										<div>'.$v["a"].'</div>
									</div>
								</td>
								<td>'.$type.'</td>
								<td style="text-align:right; font-weight:bold;">'. $this->format( $v["montant"] ).'</td>
								<td class="text-center">'.$status.'</td>
								<td style="width:50px; text-align:center">
									<button class="ppl-update" value="' . $v["id"] . '">
										<i class="fas fa-ellipsis-v"></i>
									</button>
								
								</td>
							</tr>
			';
		}
		return $trs;
	}

	public function ByPropriete($params = []){
		
		$year = $params['year'] != "-1"? $params['year']: "";


		if($year != "")
			$ppl = $this->find('', ['conditions AND'=>['id_propriete=' => $params['id_propriete'], 'YEAR(created)=' => $year ], 'order'=>'created DESC'], 'v_propriete_proprietaire_location');
		else
			$ppl = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'created DESC'], 'v_propriete_proprietaire_location');
		
			$template = '
			<div class="ppl_wrapper shadow-lg">
				<div class="popup-content ppl">
					<div class="header d-flex space-between mb-10">
						<div class="title" style="font-weight:bold; padding-top:7px">Contrats envers Propriétaire</div>
						<div class="">
							<button class="add green" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> Ajouter</button>
							<button class="refresh hide" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> ref</button>
						</div>
					</div>
					<div class="ppl-add-container"></div>
					<div class="body border border-red">
						<table>
							<thead>
								<tr>
									<th class="bg-red text-white">PERIODE</th>
									<th class="bg-red text-white">TYPE</th>
									<th class="bg-red text-white">MONTANT</th>
									<th class="bg-red text-white">STATUS</th>
									<th class="bg-red text-white"></th>
								</tr>
							</thead>

							<tbody>
								{{trs}}
							</tbody>
						</table>
					</div>
				</div>			
			</div>
		';
		
		$trs = '';
		
		
		foreach($ppl as $k=>$v){
			$type = 'Par Nuit';
			$type = ($v["id_propriete_location_type"] === "1")? $type: ($v["id_propriete_location_type"] === "2"? "Par Mois": "Forfait");
			$status = ($v["status"] == "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs .= '
							<tr>
								<td>
									<div class="d-flex ppl-periode">
										<div>'.$v["de"].'</div>
										<div class="pl-5 pr-5 text-red" style="font-size:16px">[ '.$v["nbr_nuite"].' ]</div>
										<div>'.$v["a"].'</div>
									</div>
								</td>
								<td>'.$type.'</td>
								<td style="text-align:right; font-weight:bold;">'. $this->format( $v["montant"] ).'</td>
								<td class="text-center">'.$status.'</td>
								<td style="width:50px; text-align:center">
									<button class="ppl-update" value="' . $v["id"] . '">
										<i class="fas fa-ellipsis-v"></i>
									</button>
								</td>
							</tr>
			';
		}		
		
		$empty = '
						<tr>
							<td colspan="3">
								<div class="flex justify-center text-red text-lg py-4 show_alert">
									Pas de périodes enregistré envers le propriétaire
								</div>
							</td>
						</tr>
		';

		$trs =  $trs == ''? $empty: $trs ;

		return str_replace(["{{trs}}"], [$trs], $template);
	}
	
}
$propriete_proprietaire_location = new Propriete_Proprietaire_Location;