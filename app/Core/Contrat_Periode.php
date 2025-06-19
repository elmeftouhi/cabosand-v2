<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');
require_once('Propriete.php');

class Contrat_Periode extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
		array("column" => "created", "label"=>"CREATION", "style"=>"display:none", "display"=>0),
		array("column" => "nbr", "label"=>"#N", "style"=>"min-width:40px; width:40px; font-weight:bold"),
		array("column" => "date_debut", "label"=>"DATE DEBUT", "style"=>"min-width:90px; width:90px; font-weight:bold; background-color:rgba(0, 255, 0,0.2)"),
		array("column" => "date_fin", "label"=>"DATE FIN", "style"=>"min-width:90px; width:90px; font-weight:bold; background-color:rgba(255,0,0,0.2)"),
		array("column" => "nbr_nuite", "label"=>"NUITES", "style"=>"color:#E91E63; font-size:20px; padding-left:10px"),
		array("column" => "status", "label"=>"STATUS", "style"=>"min-width:105px; width:105px"),
		array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px;")
	);
	
	private $tableName = "Contrat_Periode";
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
			
	public function getColumns(){
		
		if ( isset($this->columns) ){
			return $this->columns;
		}else{
			$columns = array();
			//var_dump($this->getColumnsName("client"));
			foreach($this->getColumnsName(strtolower($this->tableName)) as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px") );
			return $columns;
		}
		
	}
	
	public function drawTable($args = null, $conditions = null, $useTableName = null){

		$showPerPage = array("20","50","100","200","500","1000");
		$status = array("<div class='label label-red'>Désactivé</div>", "<div class='label label-green'>Activé</div>");
		$remove_sort = array("actions","nbr","date_debut","date_fin","nbr_nuite","status");
		
		
		$p_p = (isset($args['p_p']))? $args['p_p']: $showPerPage[0];
		$current = (isset($args['current']))? $args['current']: 0;
		$sort_by = (isset($args['sort_by']))? $args['sort_by']: "created";
		$temp = explode(" ", $sort_by );
		$order = "";
		if(count( $temp ) > 1 ){ $order =  $temp[1]; }
		
		$values = array("Error : " . $this->tableName);
		$t_n = ($useTableName===null)? strtolower($this->tableName): $useTableName;
		
		if($conditions === null){
			$values = $this->find(null,array("order"=>$sort_by,"limit"=>array($current*$p_p,$p_p)),$t_n);
			$totalItems = $this->getTotalItems();
		}else{
			$conditions["order"] = $sort_by;
			$totalItems = count($this->find(null,$conditions,$t_n));
			$conditions["limit"] = array($current*$p_p,$p_p);
			$values = $this->find(null,$conditions,$t_n);
		}
		
		$returned = '<div class="col_12" style="padding: 0">';	
	
		$returned .= '	<div class="panel" style="overflow: auto;">';
		$returned .= '		<div class="panel-content" style="padding: 0">';
		
		$returned .= '{{nbr}}';
		
		$returned .= '			<table class="table">';
		$returned .= '				<thead>';
		$returned .= '					<tr>';
		
		$columns = $this->getColumns();
	
		

		foreach($columns as $key=>$value){

			$style = ""; 
			$is_sort = ( in_array($value["column"], $remove_sort) )? "" : "sort_by";
			$is_display = ( isset($value["display"]) )? "hide" : "";

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
			$returned .= '					<tr class="periode edit" data-page="'.$_t.'" data-id="'.$v["id"].'">';
			foreach($columns as $key=>$value){
				
				$style = (isset($columns[$key]["style"]))? $columns[$key]["style"]:"";
				
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "id"){
						$returned .= "<td style='".$style."'><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
					}elseif($columns[$key]["column"] == "status"){
						$returned .= "<td style='".$style."'>".$status[$v["status"]]."</td>";
					}elseif($columns[$key]["column"] == "date_contrat"){
						$dt = explode(" ",$v[ $columns[$key]["column"] ]);	
						if($v["notes"] !== "" and isset($v["notes"])){
							$returned .= "<td style='".$style."'>" . $dt[0] . " <span style='color:blue; font-size:12px'><i class='fas fa-info-circle'></i></span></td>";
						}else{
							$returned .= "<td style='".$style."'>" . $dt[0] . "</td>";
						}
					}elseif($columns[$key]["column"] == "montant"){
						$returned .= "<td style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
					}elseif($columns[$key]["column"] == "first_name"){
						$returned .= "<td style='".$style."'>" . $v["first_name"] . " " . $v["last_name"] . "</td>";
					}else{
						$returned .= "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
					}											
				}else{
					if($columns[$key]["column"] == "actions"){
						$returned .=   "<td style='".$style."'><button style='margin-right:10px' data-page='".$_t."' class='btn btn-red periode remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$_t."' class='btn btn-orange periode edit' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
					}elseif($columns[$key]["column"] == "nbr"){
						$returned .=  "<td style='".$style."'>".($i+1)."</td>";
					}else{
						$returned .=  "<td style='".$style."'></td>";
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
		$returned .= '</div>';
		
		$returned = str_replace("{{nbr}}","<span class='periode_nbr hide' data-nbr='".$i."' style='color:red'>".$i."</span>",$returned);
		
		echo $returned;

	}

	public function Create($params){
		$UID = $params["UID"];
		$view = new View("contrat_periode.create");
		return $view->render(['UID'=>$UID, 'year'=>date('Y')]);
	}
	
	public function Store($params){
		
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		$data = array(
			"created"						=>	$created,
			"nbr_nuite"						=>	$params["nbr_nuite"],
			"date_debut"					=>	$params["date_debut"],
			"date_fin"						=>	$params["date_fin"],
			"status"						=>	$params["status"],
			"UID"							=>	$params["UID"],
		);
	
		if( isset( $params["id"]) ){
			$data["id"] = $params["id"];
		}	
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "de: " . $data["date_debut"] . " a: " . $data["date_fin"];
				$this->saveActivity("fr", $created_by, ['Contrat_Periode', 0], $data["id"], $msg);				
			}else{
				$msg = "de: " . $data["date_debut"] . " a: " . $data["date_fin"];
				$this->saveActivity("fr", $created_by, ['Contrat_Periode', 1], $this->getLastID(), $msg);
			}

			return 1;
			
		}else{
			return $this->err;
		}
		
	}
	
	public function Update($params){
		
		$push = [];
		
		$periode = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'contrat_periode');		
		if( count($periode) > 0 ){
			$push['periode'] = $periode[0];
			$push['UID'] = $periode[0]["UID"];
		}
		
		
		$view = new View("contrat_periode.create");
		return $view->render($push);
	}
	
	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				
				if( count($this->find('', ['conditions AND' => ['source='=>'contrat', 'id_periode='=>$params['id']] ], 'propriete_location') ) === 0 ){
					
					$data = $data[0];
					$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
					$msg = "De : " & $data["date_debut"] & " A : " & $data["date_fin"];
					$this->delete($params["id"]);

					$this->saveActivity("fr", $created_by, ['Contrat_Periode', -1], $data["id"], $msg);
					return 1;					

				}else{
					return 0;
				}
				
				

			}else{
				return 0;
			}

		}else{
			return 0;
		}
	}
	
	public function Table($params = []){
		
		$ppl = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'created DESC'], 'v_propriete_proprietaire_location');
		
		$template = '
		<div id="popup" class="pb-20" style="width:520px; margin:50px auto">
			<div class="popup-header d-flex space-between">
				<div class="">Liste des Contrat</div>
				<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
			</div>
			<div class="popup-content ppl">
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
								<th>DATE</th>
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
		</div>
		';
		
		$trs = '';
		$type = 'Par Nuit';
		
		foreach($ppl as $k=>$v){
			
			$type = ($v["id_propriete_location_type"] === "1")? $type: ($v["id_propriete_location_type"] === "2"? "Par Mois": "Forfait");
			$status = ($v["status"] === "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs .= '
							<tr>
								<td>'. explode(" ", $v["created"])[0].'</td>
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
		return str_replace("{{trs}}", $trs, $template);
	}
	
	public function TableShort($params = []){
		
		$periodes = $this->find('', [ 'conditions' => ['UID=' => $params['UID'] ] ], 'contrat_periode');

		$trs = '';
		
		foreach($periodes as $k=>$v){

			$status = ($v["status"] === "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$active = $k===0? "active": "";
			$trs .= '<div data-id="'.$v["id"].'" data-date_debut="'.$v["date_debut"].'" data-date_fin="'.$v["date_fin"].'" class="show_this_periode item d-flex space-between '.$active.'">
						<div class="d-flex">
							<div class="dates d-flex">
								<div class="date_debut">'.$v["date_debut"] .'</div>
								<div class="nbr_jours">'. $v["nbr_nuite"]  .'</div>
								<div class="date_fin">'. $v["date_fin"]  .'</div>
							</div>
							<div class="status">'.$status.'</div>						
						</div>
						<div>
							<button class="periode_update" value="' . $v["id"] . '"><i class="fas fa-ellipsis-h"></i></button>
						</div>
					</div>
			';
		}
		$empty = '
						<div class="d-flex text-left">
							<div class="info info-success">
								<div class="info-message"> 
								Aucune période n\'est enrégistrée
								</div>
							</div>
						</div>
		';
		return $trs===''? $empty:$trs;
	}
	
	public function Table_To_Select_Periode($params = []){
		$id_client = isset($params['id_client'])? $params['id_client']:0;
		$contrats = $this->find('', ['conditions AND' =>['status='=>1, 'id_client='=>$id_client] ], 'contrat');
		$contrat_periodes = [];
		foreach($contrats as $contrat){
			$c_p = $this->find('', ['conditions AND'=>['status='=>1, 'UID='=>$contrat['UID']] ], 'contrat_periode');
			foreach($c_p as $contrat_periode){
				$contrat_periodes[] = $contrat_periode;
			}
		}


		$propriete = new Propriete;
		$id_propriete = isset($params['id_propriete'])? $params['id_propriete']:0;
		//$periodes = $this->find('', [ 'conditions' => ['UID=' => $params['UID'] ] ], 'contrat_periode');

		$trs = '';
		
		
		foreach($contrat_periodes as $k=>$v){
			$isDisponible = $propriete->IsHasProprietaireContrat(['id_propriete'=>$id_propriete, 'date_debut'=>$v["date_debut"], 'date_fin'=>$v["date_fin"]]);
			
			if($isDisponible){
				if ($propriete->IsDisponibleOnThisPeriode(['id_propriete'=>$id_propriete, 'date_debut'=>$v["date_debut"], 'date_fin'=>$v["date_fin"]]) ){
					$btn = '
							<button data-id_periode="'.$v["id"].'" data-UID="'.$params['UID'].'" data-id_propriete="'.$id_propriete.'" data-date_debut="'.$v["date_debut"].'" data-date_fin="'.$v["date_fin"].'"  class="transparent py-1 px-2 add_this_propriete_to_this_contrat">
								<i class="fas fa-check"></i> 
							</button>';
				}else{
					$btn = '
							<button  class="blue">
								<i class="fas fa-ban"></i> Réservé
							</button>';
				}
			}else{
				$btn = '
						<button  class="red">
							<i class="fas fa-ban"></i> 
						</button>';				
			}
			
			
			$status = ($v["status"] == "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
			$trs .= '<div data-id="'.$v["id"].'" data-date_debut="'.$v["date_debut"].'" data-date_fin="'.$v["date_fin"].'" class="item d-flex space-between">
						<div class="d-flex">
							<div class="dates d-flex">
								<div class="date_debut">'.$v["date_debut"] .'</div>
								<div class="nbr_jours">'. $v["nbr_nuite"]  .'</div>
								<div class="date_fin">'. $v["date_fin"]  .'</div>
							</div>
							<div class="status">'.$status.'</div>						
						</div>
						<div>'.$btn.'</div>
					</div>
			';
		}
		$empty = '
						<div class="d-flex text-left">
							<div class="info info-success">
								<div class="info-message"> 
								Aucune période n\'est enrégistrée
								</div>
							</div>
						</div>
		';
		return $trs===''? $empty:$trs;
	}
}

$contrat_periode = new Contrat_Periode;