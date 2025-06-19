<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Module extends Modal{
	
	private $tableName = 'modules';
	
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

					
					if( $v["id"] === "Client_Status" ){
						$request['id_status = '] = $v["value"];
						$item = 'id_status = ' . $v["value"];						
					}
					if( $v["id"] === "Category" ){
						$request['id_category = '] = $v["value"];
						$item = 'id_category = ' . $v["value"];						
					}
					if( $v["id"] === "Type" ){
						$request['id_type = '] = $v["value"];
						$item = 'id_type = ' . $v["value"];						
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
		$push['Obj']	=	new Client;
		$push['categories'] = $this->find('', [ 'order' => 'client_category DESC' ], 'client_category');
		$push['type'] = $this->find('', [ 'order' => 'client_type DESC' ], 'client_type');
		$push['statuss'] = $this->find('', [ 'order' => 'client_status DESC' ], 'client_status');
		$push['colors'] = $this->find('', [ 'order' => 'name DESC' ], 'colors');
		$view = new View("client.create");
		return $view->render($push);
	}
	
	public function Update($params){
		
		$push = [];
		$push['categories'] = $this->find('', [ 'order' => 'client_category DESC' ], 'client_category');
		$push['type'] = $this->find('', [ 'order' => 'client_type DESC' ], 'client_type');
		$push['statuss'] = $this->find('', [ 'order' => 'client_status DESC' ], 'client_status');
		$push['depenses'] = $this->find('', [ 'conditions' => ['id_client=' => $params['id'] ] ], 'depense');
		$push['colors'] = $this->find('', [ 'order' => 'name DESC' ], 'colors');
		$push['notess'] = $this->find('', [ 'conditions AND' => ['module='=>'client', 'id_module=' => $params['id'] ], 'order'=>'created DESC' ], 'notes');
		
		$push['Obj']	=	new Client;
		
		$client = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'v_client');		
		if( count($client) > 0 ){
			$push['client'] = $client[0];
		}
		
		
		$view = new View("client.create");
		return $view->render($push);
	}
	
	public function Store($params){
						
		$data = [
			'module_name'						=>	addslashes($params['name']),
		];
		
		if( isset($params["id"]) ){
			$data["id"] = $params["id"];
		}

		if($this->save($data)){
			if( !isset($params["id"]) ){
				foreach(['Add', 'Edit', 'Delete', 'Display All', 'Print', 'Export'] as $k=>$v){
					$this->save(['id_module'=>$this->getLastID(), 'module_action'=>$v], 'module_actions');
				}
			}
			return 1;
			
		}else{
			return $this->err;
		}		
		
	}
	
	public function Remove($params){
		$id = isset($params['id'])? $params['id']: 0;
		$id = count($this->find('', ['conditions'=>['id='=>$id]], 'modules'))>0? $id: 0;
		
		foreach($this->find('', ['conditions'=>['id_module='=>$id]], 'module_actions') as $k=>$v){
			$this->delete($v['id'], 'module_actions');
		}
		$this->delete($id);
		return 1;
	}
	
	public function Store_Action($params){
		$id_module = $params['id_module'];
		$data = [
			'id_module'			=>	$id_module,
			'module_action'		=>	addslashes($params['name']),
		];
		
		if(	isset($params["id"]) ) $data["id"] = $params["id"];
		if( $this->save($data, 'module_actions') ) return 1; else return $this->err;

	}
	
	public function Remove_Action($params){
		$id = isset($params['id'])? $params['id']: 0;
		$this->delete($id, 'module_actions');
		return 1;
	}
}
$module = new Module;