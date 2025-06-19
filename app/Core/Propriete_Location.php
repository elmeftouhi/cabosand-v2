<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Propriete_Location extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
		array("column" => "created", "label"=>"CREATION", "style"=>"display:none", "display"=>0),
		array("column" => "code", "label"=>"CODE", "style"=>"background-color:rgb(92, 183, 243,0.1); font-weight:bold; min-width:105px; width:125px", "display"=>1),
		array("column" => "name", "label"=>"COMPLEXE", "style"=>"font-weight:bold", "display"=>1),
		array("column" => "proprietaire", "label"=>"PROPRIETAIRE", "style"=>"font-weight:bold", "display"=>1),
		array("column" => "periode", "label"=>"PERIODE(s)", "style"=>"font-weight:bold", "display"=>1),
		array("column" => "status", "label"=>"STATUS", "style"=>"min-width:80px; width:80px", "display"=>1),
		array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px", "display"=>1)
	);
	
// construct
	private $tableName = "Propriete_Location";
	
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
		foreach($l->getDefaultStyle($style, $columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
	
	public function Table($params = []){
		
		$column_style = (isset($params['column_style']))? $params['column_style']: strtolower($this->tableName);
		
		$filters = (isset($params["filters"]))? $params["filters"]: [];
		
		$orderBy = isset($params['sort'])? $params['sort'] :"created desc";

		$l = new ListView();
		$defaultStyleName = $l->getDefaultStyleName($column_style);
		$columns = $this->getColumns($column_style);
		
		
		$table = '
				<table id="proprieteLocationtable">	
					<thead>	
						<tr>
							{{ths}}
						</tr>
						
					</thead>
					<tbody>
						{{trs}}
					</tbody>
				</table>		
		';
		
		/***********
			Columns
		***********/
		$ths = '';
		$trs_counter = 1;
		foreach($columns as $column){

			$style = ""; 
			$is_display = isset($column["display"])? ($column["display"]? "" : " hidden") : "";

			if($column['column'] === "actions"){
				$ths .= "<th class='". $is_display . "'>";
				$ths .= "	<button data-default='".$defaultStyleName."' value='".$column_style."' class='show_list_options'>";
				$ths .= "		<i class='fas fa-ellipsis-h'></i></button>";
				$ths .= "	</button>";
				$ths .=	"</th>";
			}else{
				$trs_counter += $is_display == "hidden"? 0:1;
				
				if($column['column'] == explode(" ", $orderBy)[0]){
					if(explode(" ", $orderBy)[1] == "asc"){
						$ths .= "<th class='sort_by ". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='desc'>";
						$ths .=  "	<div class='d-flex'>";
						$ths .=  		$column['label'];
						$ths .= "		<i class='pl-5 fa-solid fa-sort-down'></i> ";
						$ths .=  "	</div>";
						$ths .=	"</th>";
					}else{
						$ths .= "<th class='sort_by ". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='asc'>";
						$ths .=  "	<div class='d-flex'>";
						$ths .=  		$column['label'];
						$ths .= "		<i class='pl-5 fa-solid fa-sort-up'></i> ";
						$ths .=  "	</div>";
						$ths .=	"</th>";
					}
				}else{
					$ths .= "<th class='sort_by". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='desc'>";
					$ths .=  "	<div class='d-flex'>";
					$ths .=  		$column['label'];
					$ths .=  "	</div>";
					$ths .=	"</th>";
				}
				
			}

		}
		
		/***********
			Conditions
		***********/
		
		$request = [];
		$sql = '';
		$code = 0;
		if(isset($params['request'])){
			if( $params['request'] !== "" ){
				if( isset($params['tags']) ){
					if( count( $params['tags'] ) > 0 ){
						$code = '%' . strtolower( $params['request'] ) . '%';
						/*
						foreach( $params['tags'] as $k=>$v ){
							$request[ 'LOWER(CONVERT(' . $v. ' USING latin1)) like '] = '%' . strtolower( $params['request'] ) . '%';
						}
						*/
					}
				}
			}
		}

		$id_complexe = 0;
		$id_client = 0;

		if( count($filters) > 0 ){
			foreach($filters as $k=>$v){
				if($v["value"] !== "-1"){

					if( $v["id"] === "Complexe" ){
						$id_complexe = $v["value"];					
					}
					
					if( $v["id"] === "Client" ){
						$id_client = $v["value"];				
					}
					
				}
				
			}

		}
		
		/***********
			Body
		***********/
		$use = (isset($params['use']))? strtolower($params['use']): strtolower($this->tableName);
		
		$pp = isset( $params['pp'] ) ? $params['pp']: 50;
		$current = isset( $params['current'] ) ? $params['current']: 0;

		$where = '';
		if(isset($params['dates'])){
			$where = " pl.date_debut>='".$params['dates'][0]."' AND pl.date_fin<='".$params['dates'][1]."'";
		}

		if($id_complexe)
			$where .= $where==''? " app.id_complexe=".$id_complexe: " AND app.id_complexe=".$id_complexe;

		if($id_client)
			$where .= $where==''? " client.id=".$id_client: " AND client.id=".$id_client;

		if($code)
			$where .= $where==''? " LOWER(CONVERT(code USING latin1)) like '" . $code."'": " AND LOWER(CONVERT(code USING latin1)) like '".$code."'";

		$where = $where==''? '': 'WHERE '.$where;
		$request = "
			SELECT 
				pl.created as created,
				contrat.UID as UID,
				app.code AS code,
				client.first_name AS first_name,
				client.last_name AS last_name,
				client.societe_name AS societe_name,
				client.id AS id_client,
				client.first_name as first_name,
				client.last_name as last_name,
				client.id_status as id_status,
				client.id as id_client,
				complexe.name as complexe_name,
				pl.id as id,
				pl.date_debut as date_debut,
				pl.date_fin as date_fin,
				TO_DAYS(pl.date_fin) - TO_DAYS(pl.date_debut) AS nbr_days
			FROM propriete_location pl

			LEFT JOIN propriete app on app.id = pl.id_propriete
			LEFT JOIN complexe on complexe.id = app.id_complexe
			LEFT JOIN contrat_periode on contrat_periode.id = pl.id_periode
			LEFT JOIN contrat on contrat.UID = contrat_periode.UID
			LEFT JOIN client on client.id = contrat.id_client

			".$where."

			ORDER BY ".$orderBy."

			Limit ".$current.", ".$pp."
			
		";

		//die($request);
		/*
		$conditions = [];
		
		if( count($request) === 1 ){
			$conditions['conditions'] = $request;
		}elseif( count($request) > 1 ){
			$conditions['conditions AND'] = $request;
		}
		
		if(isset($params['sort'])){
			$conditions['order'] = $params['sort'];
		}else{
			$conditions['order'] = 'created desc';
		}
		
		// Counter
		$counter = 0;
		foreach($this->find('', $conditions, $use) as $k=>$v){
			$counter++;
		}
		
		$pp = isset( $params['pp'] ) ? $params['pp']: 20;
		$current = isset( $params['current'] ) ? $params['current']: 0;
		$conditions['limit'] = [$current,$pp];
		*/
		//$data = $this->find('', $conditions, $use);
		$data = $this->execute($request);
		$trs = '';

		
		foreach($data as $k=>$v){
			
			$background = isset($v["all_ligne"])? ($v["all_ligne"]? $v["hex_string"]: ""): "";
			$trs .= '<tr class="" style="background-color:'.$background.'" data-page="'.$use.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
				$is_display = (!$columns[$key]["display"])? "hide": "" ;

				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "name"){
						$code = $v["code"] === ""? "": "<div style='font-size:10px; font-weight:bold'>" . $v["code"] . "</div>";
						$complexe = $v["name"] === ""? "": $v["name"] . $code;
						$trs .= "<td class='".$is_display."' style='".$style."'>" . $complexe . "</td>";
					}elseif($columns[$key]["column"] == "first_name"){
						if($v['societe_name'] == '')
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $v['societe_name'] . "</td>";
						else
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $v['first_name']. " " . $v['last_name'] . "</td>";		
					}elseif($columns[$key]["column"] == "nbr_days"){
						$trs .= "
							<td class='".$is_display."' style='".$style."'>
								<div class='bg-green-50 border border-green-200 rounded-lg py-1 px-2 shadow w-12 mx-auto text-xl'>".$v['nbr_days']."</div>
							</td>";
					}else{
					
						if(isset($columns[$key]["format"])){
							if($columns[$key]["format"] === "money"){
								$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
							}else if($columns[$key]["format"] === "on_off"){
								$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
							}else if($columns[$key]["format"] === "color"){
								$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
							}else if($columns[$key]["format"] === "date"){
								$date = explode(" ", $v[ $columns[$key]["column"] ]);
								if(count($date)>1){
									$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
								}else{
									$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
								}
								$trs .= "<td class='".$is_display."' style='".$style.";'>".$_date."</td>";

							}else{
								$trs .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]. "</td>";
							}
						}else{
							$trs .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]."</td>";
						}
					}	
				}else{
					if($columns[$key]["column"] == "actions"){
						$trs .=   "<td style='width:55px; text-align: center'><button data-controler='". $this->tableName ."' class='update' value='".$v["id"]."'><i class='fas fa-ellipsis-v'></i></button></td>";	
					}else{
						
						if($columns[$key]["format"] === "money"){
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
						}else if($columns[$key]["format"] === "on_off"){
							$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
						}else if($columns[$key]["format"] === "color"){
							$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
						}else if($columns[$key]["format"] === "date"){
							$date = explode(" ", $v[ $columns[$key]["column"] ]);
							if(count($date)>1){
								$_date = "<div style='min-width:105px'>check<i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
							}else{
								$_date = "<div>check<i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
							}
							
							$trs .= "<td class='".$is_display."' style='".$style.";'>".$_date."</td>";
						}else{
							$trs .= "<td class='".$is_display."' style='".$style."'></td>";
						}						
					}

				}


			}
			$trs .= '</tr>';
			
		}
		
		if(count($data) === 0)
			$trs = '<tr><td colspan="'.$trs_counter.'">No Data to Display!</td></tr>';

		return str_replace(["{{ths}}", "{{trs}}"], [$ths, $trs], $table);
		
	}
	
	//	Draw Table
	public function drawTable($args = null, $conditions = null, $useTableName = null){

		$showPerPage = array("20","50","100","200","500","1000");
		$status = array("<div class='label label-red'>Désactivé</div>", "<div class='label label-green'>Activé</div>");
		$remove_sort = array("actions","nbr","periode");
		
		
		$p_p = (isset($args['p_p']))? $args['p_p']: $showPerPage[5];
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
		
		$returned .= '			<table class="table">';
		$returned .= '				<thead>';
		$returned .= '					<tr>';
		$returned .= '						<th style="display:none">#ID</th> <th style="max-width:180px; width:180px; background-color: rgba(50, 115, 220, 0.3);">APPARTEMENT</th> <th>PERIODES</th><th style="width:105px"></th>';
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
			$returned .= '					<tr>';

			$periodes = count(explode(" ", $v["debuts"]));
			$debuts = explode(" ", $v["debuts"]);
			$fins = explode(" ", $v["fins"]);

			$strings = "";

			for( $p=0; $p < $periodes; $p++ ){

				if($p===0){
					$strings .= "<div class='dashed' style='display:inline-block; padding:5px'><span class='label label-default'>" . $debuts[$p] . "</span> <i class='fas fa-angle-double-right'></i> <span class='label label-green'>" . $fins[$p] . "</span></div>";
				}else{
					$strings .= "<div class='dashed' style='display:inline-block; margin-top:10px; margin-left:10px; padding:5px'><span class='label label-default'>" . $debuts[$p] . "</span> <i class='fas fa-angle-double-right'></i> <span class='label label-green'>" . $fins[$p] . "</span></div>";
				}

			}


				
			
			$returned .= "<td style='display:none'><span class='id-ligne'>" . $v['id'] . "</span></td>";
			$returned .= "<td style='background-color: rgba(50, 115, 220, 0.1); font-weight:bold; font-size:14px'>" . $v['code'] . "</td>";
			$returned .= "<td style=''>" . $strings . "</td>";
			$returned .= "<td style=''><button style='margin-right:10px' data-page='".$_t."' class='btn btn-red remove_ligne_propriete_location' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$_t."' class='btn btn-orange edit_ligne_propriete_location' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";
			
			$returned .= '					</tr>';
		$i++	;
		}
	
		if($i == 0){
			$returned .= "<tr><td colspan='4'>".$content."</td></tr>";
		}
		
	
		$returned .= '				</tbody>';
		$returned .= '			</table>';
		$returned .= '		</div>';
		$returned .= '	</div>';
		$returned .= '</div>';
		echo $returned;

	}
	
	public function Store($params){
		
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$data = [
			"UID"			=>	$params['UID'],
			"created"		=>	$created,
			"id_propriete"	=>	$params['id_propriete'],
			"source"		=>	"contrat",
			"id_periode"	=>	$params['id_periode'],
			"date_debut"	=>	$params['date_debut'],
			"date_fin"		=>	$params['date_fin'],
			"status"		=>	1
		];
		
		$this->save($data);
		
		$lastID = $this->getLastID();

		$msg = $params['date_debut'] . " " . $params['date_fin'];

		$this->saveActivity("fr", $created_by, array("Propriete_Location","1"), $lastID, $msg);
		
		return 1;
		
	}
	
	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				
				$data = $data[0];
				$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
				$msg = ""; // "periode: " . $data["de"] . " / " . $data["a"];

				$this->delete($params["id"]);

				$this->saveActivity("fr", $created_by, ['Propriete_Location', -1], $data["id"], $msg);

				return 1;
				

			}else{
				return 0;
			}

		}else{
			return 0;
		}
	}
	
	public function Create($params){
		$p_l = $this->find('', [ 'conditions'=>['id='=>$params['id']] ], 'v_propriete_location_1');
		
		if(count($p_l)>0){
			$push['Obj']	=	new Propriete_Location;
			$push['PL']	=	$p_l[0];			
		}

		
		$view = new View("propriete_location.create");
		return $view->render($push);
		
	}

	public function Add_Propriete_To_Periode($params = []){
		
		$year = date("Y");

		$request = "
			SELECT contrat.UID as UID,
				client.first_name as first_name,
				client.last_name as last_name,
				client.id_status as id_status,
				client.id as id_client,
				client.societe_name as societe_name
			FROM contrat
			LEFT JOIN contrat_periode on contrat.UID = contrat_periode.UID
			LEFT JOIN client on client.id = contrat.id_client
			WHERE YEAR(contrat_periode.created) = ".intval($year)."
			GROUP BY id_client
		";


		$client = $this->execute($request);

		$push = [];
		$push['id_propriete']  = $params["id_propriete"];
		if(count($client)>0) $push['clients'] = $client;
		
		$view = new View("propriete_location.propriete_to_periode");
		return $view->render($push);	
	}
	

	public function ByPropriete($params = []){
		
		$year = $params['year'] != "-1"? $params['year']: "";
		
		
		if($year != "")
			$cl_location = $this->find('', ['conditions AND'=>['id_propriete=' => $params['id_propriete'], 'YEAR(created)=' => $year ], 'order'=>'date_debut DESC'], 'v_propriete_location_1');
		else
			$cl_location = $this->find('', ['conditions'=>['id_propriete=' => $params['id_propriete'] ], 'order'=>'date_debut DESC'], 'v_propriete_location_1');
		
		//return count($cl_location) . " - " . $params['id_propriete'];

		$template = '
		<div class="popup-content ppc  shadow-lg">
			<div class="header d-flex space-between mb-10">
				<div class="title" style="font-weight:bold; padding-top:7px">Contrats envers Client</div>
				<div class="">
					<button class="add green" value="'.$params['id_propriete'].'"><i class="fas fa-plus"></i> Ajouter</button>
					<button class="ppc_abort hide py-2" style="background-color:#f00; color:white"><i class="far fa-times-circle"></i> Annuler</button>
				</div>
			</div>

			<div class="ppc-add-container"></div>
			<div class="body border border-blue">
				<table>
					<thead>
						<tr>
							<th class="bg-blue text-white">DEBUT</th>
							<th class="bg-blue text-white">CLIENT</th>
							<th class="bg-blue text-white text-center">STATUS</th>
							<th class="bg-blue text-white"></th>
						</tr>
					</thead>

					<tbody>
						{{trs}}
					</tbody>
				</table>
			</div>
		</div>
		';
		
		$trs_location = '';
		
		
		foreach($cl_location as $k=>$v){

			$status = ($v["status"] == "1")? "<div class='label label-green'>Activé</div>": "<div class='label label-red'>Archivé</div>";
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
								<td class="text-center w-24">
									<div data-id_location="'.$v['id'].'" class="supprimer_location justify-center rounded-lg bg-red text-white py-2 px-3 border border-red-600 cursor-pointer">	
										Supprimer
									</div>
								</td>
							</tr>
			';
		}

		$empty = '
						<tr>
							<td colspan="3">
								<div class="flex justify-center text-blue text-lg py-4 show_alert">
									Appartement n\'est pas encore assignée !
								</div>
							</td>
						</tr>
		';

		$trs_location =  $trs_location == ''? $empty: $trs_location ;

		return str_replace(["{{trs}}"], [$trs_location], $template);

	}
	
}
$propriete_location = new Propriete_location;