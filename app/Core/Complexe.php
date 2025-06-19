<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Complexe extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "name", "label"=>"COMPLEXE", "width"=>150),
		array("column" => "ville", "label"=>"VILLE", "width"=>80),
		array("column" => "complexe_type", "label"=>"CATEGORY"),
		array("column" => "nbr_propriete", "label"=>"APPART.", "width"=>50),
		array("column" => "facilities", "label"=>"FACILITIES"),
		array("column" => "contact", "label"=>"CONTACT"),
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
		foreach($l->getDefaultStyle($style, $columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
	
	public function FindBy($params){
		
		$code = addslashes( strtolower($params['request']) );
		$data = $this->find('', ['conditions'=>['LOWER(CONVERT(societe_name USING latin1))='=>$code] ], '');
		return count( $data ) === 1? $data[0]: 0;
		
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

					if( $v["id"] === "Type" ){
						$request['id_complexe_type = '] = $v["value"];
						$item = 'id_complexe_type = ' . $v["value"];						
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
			$conditions['order'] = 'name desc';
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
				}else{
					if($columns[$key]["column"] == "actions"){
						$trs .=   "<td style='width:55px; text-align: center'><button data-controler='". $this->tableName ."' class='update' value='".$v["id"]."'><i class='fas fa-ellipsis-v'></i></button></td>";	
					
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
							$trs .= "<td class='".$is_display."' style='".$style."'>" . "NaN" . "</td>";
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
		$push['Obj']	=	new Complexe;
		$push['types'] = $this->find('', [ 'order' => 'complexe_type DESC' ], 'complexe_type');
		$view = new View("complexe.create");
		return $view->render($push);
	}
	
	public function Update($params){
		
		$push = [];
		$push['types'] = $this->find('', [ 'order' => 'complexe_type DESC' ], 'complexe_type');		
		$push['Obj']	=	new Complexe;
		
		$complexe = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'v_complexe');		
		if( count($complexe) > 0 ){
			$push['complexe'] = $complexe[0];
		}
		
		
		$view = new View("complexe.create");
		return $view->render($push);
	}
	
	public function Store($params){
				
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$data = [
			'created'					=>	$created,
			'created_by'				=>	$created_by,
			'updated'					=>	$created,
			'id_complexe_type'			=>	$params['columns']['id_complexe_type'],
			'name'						=>	addslashes($params['columns']['name']),
			'ABR'						=>	addslashes($params['columns']['ABR']),
			'contact_1'					=>	addslashes($params['columns']['contact_1']),
			'contact_2'					=>	addslashes($params['columns']['contact_2']),
			'phone_1'					=>	addslashes($params['columns']['phone_1']),
			'phone_2'					=>	addslashes($params['columns']['phone_2']),
			'adresse'					=>	addslashes($params['columns']['adresse']),
			'ville'						=>	addslashes($params['columns']['ville']),
			'status'					=>	$params['columns']['status']
		];
		
		if( isset($params['columns']["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["id"] = $params['columns']["id"];
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "Complexe: " . $data["name"];
				$this->saveActivity("fr", $created_by, ['Complexe', 0], $data["id"], $msg);				
			}else{
				$msg = "Complexe: " . $data["name"];
				$this->saveActivity("fr", $created_by, ['Complexe', 1], $this->getLastID(), $msg);
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
				if( count($this->find('', ['conditions' => ['id_complexe='=>$params['id']] ], 'propriete') ) === 0 ){

					$data = $data[0];
					$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
					$msg = "Complexe: " . $data["name"];
					$this->delete($params["id"]);
					$this->saveActivity("fr", $created_by, ['Complexe', -1], $data["id"], $msg);
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

	
}
$complexe = new Complexe;