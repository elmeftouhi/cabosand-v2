<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');
require_once('Client.php');

class Contrat extends Modal{
	
	private $tableName = __CLASS__;
	
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
		
		$remove_sort = array("actions","nbr","nbr_nuite","total");
		$column_style = (isset($params['column_style']))? $params['column_style']: strtolower($this->tableName);
		
		$filters = (isset($params["filters"]))? $params["filters"]: [];
		
		$l = new ListView();
		$defaultStyleName = $l->getDefaultStyleName($column_style);
		$columns = $this->getColumns($column_style);
		
		
		$table = '
			<div class="table-container">
				<div class="d-flex space-between" style="padding:0 10px 10px 10px">
					<div style="font-size:16px; font-weight:bold">{{counter}}</div>
					<div class="text-green" style="font-size:16px; font-weight:bold">{{total}}</div>
				</div>
				<table>	
					<thead>	
						<tr>
							{{ths}}
						</tr>
						
					</thead>
					<tbody>
						{{trs}}
					</tbody>
				</table>
			</div>
		
		';
		
		/***********
			Columns
		***********/
		$ths = '';
		$trs_counter = 1;
		
		foreach($columns as $column){
			$is_sort = ( in_array($column["column"], $remove_sort) )? "" : "sort_by";
			$style = ""; 
			$is_display = ( isset($column["display"]) )? ($column["display"])? "" : "hide" : "";
			
			if($column['column'] === "actions"){
				$ths .= "<th class='". $is_display . "'>";
				$ths .= "	<button data-default='".$defaultStyleName."' value='".$column_style."' class='show_list_options'>";
				$ths .= "		<i class='fas fa-ellipsis-h'></i></button>";
				$ths .= "	</button>";
				$ths .=	"</th>";
			}else{
				$trs_counter += $is_display === "hide"? 0:1;
				$ths .= "<th class='".$is_sort." ". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='desc'>";
				$ths .=  "	<div class='d-flex'>";
				$ths .=  		$column['label'];
				$ths .= "		<i class='pl-5 fas fa-sort'></i> ";
				$ths .=  "	</div>";
				$ths .=	"</th>";
			}

		}
		
		/***********
			Conditions
		***********/
		
		$request = [];
		$sql = '';
		if(isset($params['request'])){
			if( $params['request'] !== "" ){
				if( isset($params['tags']) ){
					if( count( $params['tags'] ) > 0 ){
						foreach( $params['tags'] as $k=>$v ){
							$request[ 'LOWER(CONVERT(' . $v. ' USING latin1)) like '] = '%' . strtolower( $params['request'] ) . '%';
							
							$item = 'LOWER(CONVERT(' . $v. ' USING latin1)) like %' . strtolower( $params['request'] ) . '%';
							$sql .= $sql===''? $item.'<br>': ' AND '.$item.'<br>';
							
						}
					}
				}
			}
		}
		
		if( count($filters) > 0 ){
			foreach($filters as $k=>$v){
				if($v["value"] !== "-1"){

					if( $v["id"] === "Status" ){
						$request['status = '] = $v["value"];
						$item = 'status = ' . $v["value"];						
					}
					
					if( $v["id"] === "Mois" ){
						$request['MONTH(date_contrat) = '] = $v["value"];
						$item = 'MONTH(date_contrat) = ' . $v["value"];						
					}

					if( $v["id"] === "Années" ){
						$request['YEAR(date_contrat) = '] = $v["value"];
						$item = 'YEAR(date_contrat) = ' . $v["value"];						
					}
					$sql .= $sql===''? $item.'<br>': ' AND '.$item.'<br>';					
				}
				
			}

		}
		
		/***********
			Body
		***********/
		$use = (isset($params['use']))? strtolower($params['use']): strtolower($this->tableName);

		
		$conditions = [];
		
		if( count($request) === 1 ){
			$conditions['conditions'] = $request;
		}elseif( count($request) > 1 ){
			$conditions['conditions AND'] = $request;
		}
		
		if(isset($params['sort'])){
			$conditions['order'] = $params['sort'];
		}else{
			$conditions['order'] = 'date_contrat desc';
		}
		
		$pp = isset( $params['pp'] ) ? $params['pp']: 20;
		$current = isset( $params['current'] ) ? $params['current']: 0;
		
		
		// Counter
		$counter = count($this->find('', $conditions, $use));
		
		$conditions['limit'] = [$current,$pp];
		
		$data = $this->find('', $conditions, $use);
		$trs = '';

		
		foreach($data as $k=>$v){
						
			$background = isset($v["all_ligne"])? $v["all_ligne"]? $v["hex_string"]: "": "";
			$trs .= '<tr style="background-color:'.$background.'" data-page="'.$use.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
								
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "date_contrat"){
						$dt = explode(" ",$v[ $columns[$key]["column"] ]);	
						if($v["notes"] !== "" and isset($v["notes"])){
							$trs .= "<td style='".$style."'>" . $dt[0] . " <span style='color:blue; font-size:12px'><i class='fas fa-info-circle'></i></span></td>";
						}else{
							$trs .= "<td style='".$style."'>" . $dt[0] . "</td>";
						}
					}elseif($columns[$key]["column"] == "montant"){
						$trs .= "<td style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
					}elseif($columns[$key]["column"] == "first_name"){
						$trs .= "<td style='".$style."'>" . $v["first_name"] . " " . $v["last_name"] . "</td>";
					}elseif($columns[$key]["column"] == "nbr_periode2"){
						$trs .= "<td style='".$style."'>" . $v["nbr_periode2"] . " / <span style='font-size:12px'>" . $v["nbr_periode"] . "</span></td>";
					}elseif($columns[$key]["column"] == "nbr_nuite2"){
						$trs .= "<td style='".$style."'>" . $v["nbr_nuite2"] . " / <span style='font-size:12px'>" . $v["nbr_nuite"] . "</span></td>";
					}elseif($columns[$key]["column"] == "nbr_appartement"){
						$trs .= "<td style='".$style."'>" . $v["nbr_appartement2"] . " / <span style='font-size:12px'>" . $v["nbr_appartement"] . "</span></td>";
					}else{

						if(isset($columns[$key]["format"])){
							if($columns[$key]["format"] === "money"){
								$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
							}else if($columns[$key]["format"] === "on_off"){
								if($v[ $columns[$key]["column"] ] === "0")
									$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
								else
									$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-green'>Activé</div></td>";
							}else if($columns[$key]["format"] === "color"){
								$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
							}else if($columns[$key]["format"] === "date"){
								$date = explode(" ", $v[ $columns[$key]["column"] ]);
								if(count($date)>1){
									$_date = "<div style='min-width:105px'><i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
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
						$trs .=   "<td style='width:95px; text-align: center'>";	
						$trs .=   "<button class='show_periode' data-id='".$v["id"]."' value='".$v["UID"]."'><i class='far fa-calendar-alt'></i></button>";
						$trs .=   "<button data-controler='". $this->tableName ."' class='update' value='".$v["id"]."'><i class='fas fa-ellipsis-v'></i></button>";
						$trs .=   "</td>";
					
					}elseif($columns[$key]["column"] == "total"){
						$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($total) . "</td>";
					}else{
						
						if($columns[$key]["format"] === "money"){
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
						}elseif($columns[$key]["column"] == "nbr_nuite"){
							$trs .= "<td style='".$style."'><button class='show_propriete_proprietaire' data-id='".$v['id']."'>" . $nbr_nuite . "</button></td>";
						}else if($columns[$key]["format"] === "on_off"){
							$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
						}else if($columns[$key]["format"] === "color"){
							$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
						}else if($columns[$key]["format"] === "date"){
							$date = explode(" ", $v[ $columns[$key]["column"] ]);
							if(count($date)>1){
								$_date = "<div style='min-width:105px'><i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
							}else{
								$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
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
		
		$counter = $counter . " Operations";
		return str_replace(["{{ths}}", "{{trs}}", "{{sql}}", "{{counter}}"], [$ths, $trs, $sql, $counter], $table);
		
	}

	public function Create($params = []){
		$push = [];
		$push['societe'] = $this->find('', [ 'order' => 'raison_social' ], 'entreprise');
		$push['Obj']	=	new Contrat;
		
		$view = new View("contrat.create");
		return $view->render($push);
	}
	
	public function Update($params){
		
		$push = [];
		$push['depenses'] = $this->find('', [ 'conditions' => ['id_contrat=' => $params['id'] ] ], 'depense');
		$push['societe'] = $this->find('', [ 'order' => 'raison_social' ], 'entreprise');
		$push['notess'] = $this->find('', [ 'conditions AND' => ['module='=>'contrat', 'id_module=' => $params['id'] ], 'order'=>'created DESC' ], 'notes');
		
		$push['Obj']	=	new Contrat;
		
		$contrat = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], '');		
		if( count($contrat) > 0 ){
			$client = $this->find('', ['conditions' => ['id=' => $contrat[0]['id_client'] ] ], 'client');
			$push['contrat'] = $contrat[0];
			if(count($client) > 0)
				$push['client'] = $client[0];
		}
		
		
		$view = new View("contrat.create");
		return $view->render($push);
	}
	
	public function Periode($params){
		
		$push['locations'] = [];
		$push['periodes'] = $this->find('', [ 'conditions' => ['UID=' => $params['UID'] ] ], 'contrat_periode');
		if(count($push['periodes'])>0){
			$push['locations'] = $this->find('', [ 'conditions AND' => ['source='=>'contrat', 'id_periode=' => $push['periodes'][0]['id'] ] ], 'v_propriete_location_1');
		}
		
		
		$push['Obj']	=	new Contrat;
		$push['UID']	=	$params['UID'];
		
		$view = new View("contrat.periode");
		return $view->render($push);
	}

	public function PeriodeBy($params){
		
		$items = '';
		$empty = '
						<div class="d-flex text-left">
								<div class="info info-success">
									<div class="info-message"> 
									Aucun Appartement n\'est trouvé pour cette periode
									</div>
								</div>				
						</div>
		';
		
		$status = [
						0 =>	'<div class="label label-red">Désactivé</div>',
						1 =>	'<div class="label label-green">Activé</div>'
					];
		
		$data = $this->find('', [ 'conditions AND' => ['source='=>'contrat', 'id_periode=' => $params['id'] ] ], 'v_propriete_location_1');
		
		foreach($data as $k=>$p){
			
		$start = new DateTime($p["date_debut"]);
		$interval = $start->diff(new DateTime($p["date_fin"]));

		$nbr =  $interval->format('%r%a');
			
			
			$items .= '
						<div class="item d-flex space-between app">
							<div class="d-flex">
								<div class="dates d-flex">
									<div class="code">'. $p["code"].'</div>
									<div class="proprietaire">'. $p["proprietaire"].'</div>
									<div class="date_debut">'. $p["date_debut"].'</div>
									<div class="nbr_jours">' . $nbr . '</div>
									<div class="date_fin">'. $p["date_fin"].'</div>
								</div>
								<div class="status">'. $status[$p["status"]].'</div>						
							</div>
							<div>
								<button class="remove_this_propriete_from_periode transparent" style="color:red" value="'.$p["id"].'"><i class="far fa-trash-alt"></i></button>
							</div>
						</div>

			';	
		}
		
		$items = $items === ''? $empty: $items;
		return $items;
	}
	
	public function Store($params){
				
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$client = new Client;
		$id_client = 0;
		
		$data = [
			'UID'						=>	addslashes($params['columns']['UID']),
			'created'					=>	$created,
			'created_by'				=>	$created_by,
			'updated'					=>	$created,
			'id_societe'				=>	$params['columns']['id_societe'],
			'date_contrat'				=>	$params['columns']['date_contrat'],
			'nbr_appartement'			=>	$params['columns']['nbr_appartement']===''? 0: $params['columns']['nbr_appartement'],
			'montant'					=>	$params['columns']['montant']===''? 0: $params['columns']['montant'],
			'nbr_nuite'					=>	$params['columns']['nbr_nuite']===''? 0: $params['columns']['nbr_nuite'],
			'nbr_periode'				=>	$params['columns']['nbr_periode']===''? 0: $params['columns']['nbr_periode'],
			'notes'						=>	addslashes($params['columns']['notes']),
			'status'					=>	$params['columns']['status']
		];
		
		$client_data = [ 'columns' => [
			'id_type'					=>	0,
			'id_category'				=>	0,
			'id_status'					=>	0,
			'id_color'					=>	0,
			'first_name'				=>	addslashes($params['columns']['client_first_name']),
			'last_name'					=>	addslashes($params['columns']['client_last_name']),
			'societe_name'				=>	addslashes($params['columns']['client_societe_name']),
			'cin'						=>	addslashes($params['columns']['client_cin']),
			'passport'					=>	addslashes($params['columns']['client_passport']),
			'phone_1'					=>	0,
			'phone_2'					=>	0,
			'adresse'					=>	"",
			'ville'						=>	addslashes($params['columns']['client_ville']),
			'email'						=>	'',
			'notes'						=>	'',
			'status'					=>	1				
							]
						];		
		if($params["columns"]["id_client"] !== ""){
			$client->setID($params['columns']['id_client']);
			$prop = $client->read();
			if(count($prop) > 0){
				$client_data["columns"]["id"] = $params['columns']['id_client'];
				
				$client_data["columns"]["id_type"] = $prop[0]["id_type"];
				$client_data["columns"]["id_category"] = $prop[0]["id_category"];
				$client_data["columns"]["id_status"] = $prop[0]["id_status"];
				$client_data["columns"]["id_color"] = $prop[0]["id_color"];
				$client_data["columns"]["phone_1"] = $prop[0]["phone_1"];
				$client_data["columns"]["phone_2"] = $prop[0]["phone_2"];
				$client_data["columns"]["adresse"] = $prop[0]["adresse"];
				$client_data["columns"]["email"] = $prop[0]["email"];
				$client_data["columns"]["notes"] = $prop[0]["notes"];
				
				if($prop[0]["UID"] === "0" || $prop[0]["UID"] === ""){
					$client_data["columns"]["UID"] = md5( uniqid('auth', true) );
				}else{
					$client_data["columns"]["UID"] = $prop[0]["UID"];
				}
				$client->Store($client_data);	
				$id_client = $params['columns']['id_client'];
			}
		}else{
			$client_data["columns"]["UID"] = md5( uniqid('auth', true) );
			$client->Store($client_data);
			$id_client = $client->getLastID();
		}
		
		$data["id_client"] = $id_client;
		
		if( isset($params['columns']["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["id"] = $params['columns']["id"];		
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "Contrat: " . $client_data["columns"]["societe_name"];
				$this->saveActivity("fr", $created_by, ['Contrat', 0], $data["id"], $msg);				
			}else{
				
				$msg = "Contrat: " . $client_data["columns"]["societe_name"];
				$this->saveActivity("fr", $created_by, ['Contrat', 1], $this->getLastID(), $msg);
			}

			return 1;
			
		}else{
			return $this->err;
		}		
		
	}
	
	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				
				if( count($this->find('', ['conditions' => ['UID='=>$data[0]['UID']] ], 'contrat_periode') ) === 0 ){
					if( count($this->find('', ['conditions AND' => [ 'UID='=>$data[0]['UID'], 'source='=>'contrat' ] ], 'propriete_location') ) === 0 ){

						$data = $data[0];
						$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
						$msg = "Date: " . $data["date_contrat"];
						
						foreach($this->find('', ['conditions AND'=>['module='=>'contrat', 'id_module='=>$params['id']] ], 'notes') as $k=>$v){
							$this->delete($v["id"], 'notes');
						}
						
						$this->delete($params["id"]);

						$this->saveActivity("fr", $created_by, ['Contrat', -1], $data["id"], $msg);

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

		}else{
			return 0;
		}
	}
	
	public function drawTable($args = null, $conditions = null, $useTableName = null){

		$showPerPage = array("20","50","100","200","500","1000");
		$status = array("<div class='label label-red'>Désactivé</div>", "<div class='label label-green'>Activé</div>");
		$remove_sort = array("actions","nbr");
		
		
		$p_p = (isset($args['p_p']))? $args['p_p']: $showPerPage[0];
		$current = (isset($args['current']))? $args['current']: 0;
		$sort_by = (isset($args['sort_by']))? $args['sort_by']: "id";
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
	
		$returned .= '	<div style="display: flex; flex-direction: row">';
		$returned .= '		<div style="flex: auto; padding: 15px 0 10px 5px; margin: 0; color: rgba(118,17,18,1.00)">';
		$returned .= '			Total : ('.count($values).' / '.$totalItems.') <span class="current hide">'.$current.'</span>';
		$returned .= '		</div>';
		$returned .= '		<div style="width: 10rem">';
		$returned .= '		<div style="flex-direction: row; display: flex">';
		$returned .= '			<div style="flex: 1">';
		$returned .= '				<select id="showPerPage">';
		
		foreach($showPerPage as $kk => $vv)
			$returned .= '				<option value="'.$vv.'" ' . ( $p_p == $vv ? "selected" : "") .'>'.$vv.'</option>';
		
		
		$returned .= '				</select>';
		$returned .= '					<span class="hide ' . $order . '" id="sort_by">'.$sort_by.'</span>';
		$returned .= '			</div>';
		$returned .= '			<div style="flex: 1; text-align: center">';
		$returned .= '				<div class="btn-group">';
		$returned .= '					<a style="padding: 12px 12px" id="btn_passive_preview"  title="Précédent"><i class="fa fa-chevron-left"></i></a>';
		$returned .= '					<a style="padding: 12px 12px" id="btn_passive_next" title="Suivant"><i class="fa fa-chevron-right"></i></a>';
		$returned .= '				</div>';
		$returned .= '			</div>';
		$returned .= '		</div>';
		$returned .= '		</div>';
		$returned .= '	</div>';	
	
		$returned .= '	<div class="panel" style="overflow: auto;">';
		$returned .= '		<div class="panel-content" style="padding: 0">';
		
		$returned .= '			<table class="table">';
		$returned .= '				<thead>';
		$returned .= '					<tr>';
		
		$columns = $this->getColumns();

		foreach($columns as $key=>$value){

			$style = ""; 
			$is_sort = ( in_array($value["column"], $remove_sort) )? "" : "sort_by";
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
			$returned .= '					<tr class="_edit_ligne" data-page="'.$_t.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
				
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
					}elseif($columns[$key]["column"] == "nbr_periode2"){
						$returned .= "<td style='".$style."'>" . $v["nbr_periode2"] . " / <span style='font-size:12px'>" . $v["nbr_periode"] . "</span></td>";
					}elseif($columns[$key]["column"] == "nbr_nuite2"){
						$returned .= "<td style='".$style."'>" . $v["nbr_nuite2"] . " / <span style='font-size:12px'>" . $v["nbr_nuite"] . "</span></td>";
					}elseif($columns[$key]["column"] == "nbr_appartement"){
						$returned .= "<td style='".$style."'>" . $v["nbr_appartement2"] . " / <span style='font-size:12px'>" . $v["nbr_appartement"] . "</span></td>";
					}else{
						$returned .= "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
					}											
				}else{
					if($columns[$key]["column"] == "actions"){
						$returned .=   "<td style='".$style."'><button style='margin-right:10px' data-page='".$_t."' class='btn btn-red remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$_t."' class='btn btn-orange _edit_ligne' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
					}elseif($columns[$key]["column"] == "nbr"){
						$returned .=  "<td style='".$style."'>0</td>";
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
		
		echo $returned;

	}

	public function FindBy($params){
		
		$code = addslashes( strtolower($params['request']) );
		$data = $this->find('', ['conditions'=>['lower(UID)='=>$code] ], '');
		return count( $code ) === 1? $data[0]: 0;
		
	}
	
	public function GetFiles($params){
		
		$statics = $_SESSION["STATICS"];
		
		$folder = $_SESSION["UPLOAD_FOLDER"].$params["folder"].DIRECTORY_SEPARATOR.$params["UID"].DIRECTORY_SEPARATOR;
		
		$dS = DIRECTORY_SEPARATOR;
		
		$icons = [
			'doc'	=>	$statics."public/images/icon_word.png",
			'docx'	=>	$statics."public/images/icon_word.png",
			'pdf'	=>	$statics."public/images/icon_pdf.png",
			'jpg'	=>	$statics."public/images/images.png",
			'jpeg'	=>	$statics."public/images/images.png",
			'png'	=>	$statics."public/images/images.png",
			'gif'	=>	$statics."public/images/images.png",
			'bmp'	=>	$statics."public/images/images.png"
		];
		
		$default_src = $statics."/public/images/images.png";
		
		$array_src = [];
						
		if(file_exists($folder)){

			foreach(scandir($folder) as $k=>$v){
				if($v <> "." and $v <> ".." and strpos($v, '.') !== false){
					
					$ext = explode(".",$v);
					$file_name = $ext[1];
					
					if( isset( $icons[$ext[1]] ) ){
						$file = [
							'file_name'	=>	$v,
							'file_icon'	=>	$icons[$ext[1]],
							
							'file_src'	=>	$statics.$params["folder"]."/".$params["UID"]."/".$v,
							'file_link'	=>	$folder.$v
						];
						array_push( $array_src, $file ) ;
					}
				}
			}	
		}
		
		return $array_src;
	}
	
	public function GetFilesAsList($params){
		//sleep(3);
		$images = $this->GetFiles($params);
		
		$template = '
			
			<div class="list-image">
				<ul>
					{{li}}
				</ul>
			</div>
		
		';
		$lis = '';
		
		$empty = '
			<li>
				<div style="width:100%; height:150px">
					<button style="width:100%; height:100%; font-size:96px; color:grey" class="upload_btn" data-target="upload"><i class="fas fa-folder-plus"></i></button>
				</div>
			</li>
		
		';
		
		foreach($images as $image){

			$lis .= '
					<li>
						<div class="image">
							<img class="download_file" data-link="' . $image["file_src"] . '" src="'.$image["file_icon"].'">
						</div>
						<div class="info" style="flex:1; text-align:left">
							<div class="name">' . $image["file_name"] . '</div>
						
						</div>
						<div class="image_actions">
							<button class="red remove-file" data-uid="' . $params["UID"] . '" data-folder="' . $params["folder"] . '" data-controler="Propriete" data-function="DeleteFile" data-filename="' . $image["file_name"] . '"><i class="far fa-trash-alt"></i></button>
						</div>
					</li>
			
			';
		}
		$lis = $lis===''? $empty: $lis;
		return str_replace(["{{li}}"], [$lis], $template);
	}
	
	public function DeleteFile($params){
		$created_by = $_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$this->saveActivity("fr",$created_by,array("Propriete","3"),0,"Fichier : " . $params["file_name"]);
		$folder = $_SESSION["UPLOAD_FOLDER"].$params["folder"].DIRECTORY_SEPARATOR.$params["UID"].DIRECTORY_SEPARATOR.$params["file_name"];
		if(file_exists($folder)){
			return unlink($folder)? 1:0;
		}else{
			return 0;
		}

	}
	
	
}

$contrat = new Contrat;