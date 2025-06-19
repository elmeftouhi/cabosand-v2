<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Caisse extends Modal{

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
		foreach($l->getDefaultStyle($style, $columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
		
	public function Table($params = []){
		
		
		$column_style = (isset($params['column_style']))? $params['column_style']: strtolower($this->tableName);
		
		$filters = (isset($params["filters"]))? $params["filters"]: [];
		
		$l = new ListView();
		$defaultStyleName = $l->getDefaultStyleName($column_style);
		$columns = $this->getColumns($column_style);
		
		
		$table = '
				<div class="d-flex space-between" style="padding:0 10px 10px 10px">
					<div style="font-size:16px; font-weight:bold">{{counter}}</div>
					<div class="d-flex" style="font-size:16px; font-weight:bold">
						<div style="font-size:12px; padding:7px 10px; border-radius:5px; background-color:rgba(244,240,230,1); margin-right:15px">Total Alimentation : {{ttlAlimentation}}</div>
						<div style="font-size:12px; padding:7px 10px; border-radius:5px; background-color:rgba(255,182,185,1)">Total Dépense : {{ttlDepense}}</div>
					</div>
				</div>
				<table id="tablecaisse">	
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
			$is_display = ( isset($column["display"]) )? ($column["display"])? "" : "hide" : "";
			
			if($column['column'] == "actions"){
				$ths .= "<th class='". $is_display . "'>";
				$ths .= "	<button data-default='".$defaultStyleName."' value='".$column_style."' class='show_list_options'>";
				$ths .= "		<i class='fas fa-ellipsis-h'></i></button>";
				$ths .= "	</button>";
				$ths .=	"</th>";
			}else{
				$trs_counter += $is_display == "hide"? 0:1;
				$ths .= "<th class='sort_by ". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='desc'>";
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
							$sql .= $sql==''? $item.'<br>': ' AND '.$item.'<br>';
							
						}
					}
				}
			}
		}
		
		/***********
			Body
		***********/
		$use = (isset($params['use']))? strtolower($params['use']): strtolower($this->tableName);
		
		$conditions = [];
		
		if( count($request) == 1 ){
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
		$ttlAlimentation = 0;
		$ttlDepense = 0;
		$counter = 0;
		foreach($this->find('', $conditions, $use) as $k=>$v){
			$counter++;
			$ttlAlimentation += $v["ttlAlimentation"];
			$ttlDepense += $v["ttlDepense"];
		}
		
		$pp = isset( $params['pp'] ) ? $params['pp']: 20;
		$current = isset( $params['current'] ) ? $params['current']: 0;
		$conditions['limit'] = [$current,$pp];
		
		$data = $this->find('', $conditions, $use);
		$trs = '';

		
		foreach($data as $k=>$v){
			
			$background = isset($v["all_ligne"])? $v["all_ligne"]? $v["hex_string"]: "": "";
			$trs .= '<tr style="background-color:'.$background.'" data-page="'.$use.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
				$is_display = (!$columns[$key]["display"])? "hide": "" ;

				if(isset($v[ $columns[$key]["column"] ])){
					if(isset($columns[$key]["format"])){
						if($columns[$key]["format"] === "money"){
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
						}else if($columns[$key]["format"] === "on_off"){
							$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
						}else if($columns[$key]["format"] === "on_off_default"){
							$trs .= ($v[ $columns[$key]["column"] ] == 0)? "<td style='".$style."'></td>": "<td style='".$style."; font-size:10px; color:green'> <i class='fas fa-check'></i> <span>Par Défaut</span></td>";
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
				}else{
					
					if($columns[$key]["column"] == "solde"){
						$trs .= "<td style='".$style."'>". $this->format(($v['ttlAlimentation'] + $v['solde_initial']) - $v['ttlDepense']) ."</td>";
					}elseif($columns[$key]["column"] === "nbr"){
						$trs .=  "<td class='".$is_display."' style='".$style."'>0</td>";
					}elseif($columns[$key]["column"] == "actions"){
						$trs .=   "
								<td style='min-width:100px;width:100px; text-align: center'>
									<button data-controler='". $this->tableName ."' class='caisse_mouvement green' value='".$v["id"]."'><i class='fas fa-retweet'></i></button>
									<button data-controler='". $this->tableName ."' class='update' value='".$v["id"]."'><i class='fas fa-ellipsis-v'></i></button>
								</td>";	
					
					}elseif($columns[$key]["column"] == "total"){
						$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format(0) . "</td>";
					}else{
						
						if($columns[$key]["format"] === "money"){
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
						}else if($columns[$key]["format"] === "on_off"){
							$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
						}else if($columns[$key]["format"] === "on_off_default"){
							$trs .= ($v[ $columns[$key]["column"] ] == 0)? "<td style='".$style."'></td>": "<td style='".$style."; font-size:10px; color:green'> <i class='fas fa-check'></i> <span>Par Défaut</span></td>";
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
		return str_replace(["{{ths}}", "{{trs}}", "{{sql}}", "{{ttlDepense}}", "{{ttlAlimentation}}", "{{counter}}"], [$ths, $trs, $sql, $this->format($ttlDepense), $this->format($ttlAlimentation), $counter], $table);
		
	}
	
	public function Create($params = []){
		$push = [];
		$push['Obj']	=	new Caisse;
		$view = new View("caisse.create");
		return $view->render($push);
	}
	
	public function Update($params){
		
		$push = [];	
		$push['Obj']	=	new Caisse;
		
		$caisse = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'caisse');		
		if( count($caisse) > 0 ){
			$push['caisse'] = $caisse[0];
		}
		
		
		$view = new View("caisse.create");
		return $view->render($push);
	}
	
	public function Store($params){
				
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$data = [
			'created'					=>	$created,
			'created_by'				=>	$created_by,
			'updated'					=>	$created,
			'name'						=>	addslashes($params['columns']['name']),
			'solde_minimum'				=>	$params['columns']['solde_minimum'],
			'solde_initial'				=>	$params['columns']['solde_initial'],
			'status'					=>	$params['columns']['status'],
			'is_default'				=>	$params['columns']['is_default'],
			'notes'						=>	""
		];
		
		if( isset($params['columns']["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["id"] = $params['columns']["id"];
		}
		
		if($params['columns']['is_default'] === "1"){
			foreach($this->fetchAll() as $k=>$v){
				$this->save( ['id'=>$v['id'], 'is_default'=>0] );
			}
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = $data["name"];
				$this->saveActivity("fr", $created_by, ['Caisse', 0], $data["id"], $msg);				
			}else{
				$msg = $data["name"];
				$this->saveActivity("fr", $created_by, ['Caisse', 1], $this->getLastID(), $msg);
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
				if( count($this->find('', ['conditions' => ['id_caisse='=>$params['id']] ], 'caisse_alimentation') ) === 0 ){
					if( count($this->find('', ['conditions' => ['id_caisse='=>$params['id']] ], 'depense') ) === 0 ){
						$data = $data[0];
						$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
						$msg = "Caisse: " . $data["name"];
						$this->delete($params["id"]);
						$this->saveActivity("fr", $created_by, ['Caisse', -1], $data["id"], $msg);
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
	
	public function Mouvement($params){
		
		$push = [];	
		$push['Obj']	=	new Caisse;
		$push['id_caisse'] = $params['id_caisse'];
		
		$mouvements = $this->find('', [ 'conditions'=>[ 'id_caisse='=>$params['id_caisse'] ], 'order'=>'created DESC' ], 'caisse_alimentation');		
		if( count($mouvements) > 0 ){
			$push['mouvements'] = $mouvements;
		}else{
			$push['mouvements'] = [];
		}
		
		
		$view = new View("caisse.mouvement");
		return $view->render($push);
	}
	
	public function GetListAsSelect(){
		$select = '<select class="bulk_change_type_selected">';
		$select .= '	<option value="-1">-- Caisses </option>';
		foreach($this->fetchAll() as $item){
			$select .= '	<option value="'.$item["id"].'">' . ucfirst($item["name"]) . '</option>';
		}
		$select .= '</select>';
		return $select;
	}

}
$caisse = new Caisse;