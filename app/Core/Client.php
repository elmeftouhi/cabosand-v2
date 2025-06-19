<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Client extends Modal{

	private $columns_ = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "client", "label"=>"CLIENT"),
		array("column" => "societe_name", "label"=>"SOCIETE"),
		array("column" => "created", "label"=>"DATE"),
		array("column" => "client_category", "label"=>"CATEGORIE"),
		array("column" => "client_type", "label"=>"TYPE"),
		array("column" => "contact", "label"=>"TELEPHONE"),
		array("column" => "ville", "label"=>"VILLE"),
		array("column" => "client_status", "label"=>"STATUS", "width"=>90)
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
		
	public function drawTable($args = null, $conditions = null, $useTableName = null){

		$showPerPage = array("20","50","100","200","500","1000");
		$is_default = array(
			0	=>	"", 
			1	=>	"<div style='background-color:#3E2723; color:white; border-radius:5px; padding:3px 7px 2px 5px; width:70px; font-size:10px'> <i class='fas fa-dot-circle'></i> Default </div>");
		
		$status = array(
			0	=>	"<div class='label label-red'>Désactivé</div>", 
			1	=>	"<div class='label label-green'>Activé</div>");
		
		$remove_sort = array("actions","nbr");
		
		
		$p_p = (isset($args['p_p']))? $args['p_p']: $showPerPage[0];
		$current = (isset($args['current']))? $args['current']: 0;
		$sort_by = (isset($args['sort_by']))? $args['sort_by']: "created";
		
		$temp = explode(" ", $sort_by );
		$order = "";
		if(count( $temp ) > 1 ){ $order =  $temp[1]; }
		
		$values = array("Error : " . $this->tableName);
		$t_n = ($useTableName===null)? strtolower($this->tableName): $useTableName;
		$column_style = (isset($args['column_style']))? $args['column_style']: $useTableName;
		
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
		
		$l = new ListView();
		$defaultStyleName = $l->getDefaultStyleName($column_style);
		
		$columns = $this->getColumns($column_style);

		foreach($columns as $key=>$value){

			$style = ""; 
			$is_sort = ( in_array($value["column"], $remove_sort) )? "" : "sort_by";
			$is_display = ( isset($value["display"]) )? ($value["display"])? "" : "hide" : "";
			
			$label = ($value['column'] === "actions")? "<button data-default='".$defaultStyleName."' value='".$column_style."' class='show_list_options' style='float:right; background:none; border:none; color:white; '><i class='fas fa-ellipsis-h'></i></button>": $value['label'];
			
			if($is_sort === ""){
				$returned .= "<th class='".$is_sort. " ". $is_display . "' data-sort='" . $value['column'] . "'> " . $label. "</th>";
			}else{
				$returned .= "<th class='".$is_sort. " ". $is_display . "' data-sort='" . $value['column'] . "'> <i class='fas fa-sort'></i> " . $label . "</th>";
			}

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
			$returned .= '					<tr data-page="'.$_t.'">';
			foreach($columns as $key=>$value){
				$is_display = ( isset($value["display"]) )? ($value["display"])? "" : "hide" : "";
				$style = (isset($columns[$key]["style"]))? $columns[$key]["style"]:"";
				
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "id"){
						$returned .= "<td class='".$is_display."' style='".$style."'><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
					}elseif($columns[$key]["column"] == "status"){
						$returned .= "<td class='".$is_display."' style='".$style."'>" . $status[ $v["status"] ] . "</td>";
					}elseif($columns[$key]["column"] == "is_default"){
						$returned .= "<td class='".$is_display."' style='".$style."'>" . $is_default[ $v["is_default"] ] . "</td>";
					}elseif($columns[$key]["column"] == "first_name"){
						$returned .= "<td class='".$is_display."' style='".$style."'><span style='margin-right:10px;padding:5px 15px;background-color:".$v["hex_string"]."'></span>".$v["first_name"] . "</td>";
					}else{
						if(isset($columns[$key]["format"])){
							if($columns[$key]["format"] === "money"){
								$returned .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
							}else if($columns[$key]["format"] === "on_off"){
								$returned .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
							}else{
								$returned .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]. " " . $columns[$key]["format"] . "</td>";
							}
						}else{
							$returned .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]."</td>";
						}
					}										
				}else{
					if($columns[$key]["column"] === "actions"){
						$returned .=   "<td style='".$style."'><button style='margin-right:10px' data-page='".$_t."' class='btn btn-red remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button data-page='".$_t."' class='btn btn-orange edit_ligne' value='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
					}elseif($columns[$key]["column"] === "nbr"){
						$returned .=  "<td class='".$is_display."' style='".$style."'>0</td>";
					}else{
						if(isset($columns[$key]["format"])){
							if($columns[$key]["format"] === "money"){
								$returned .= "<td class='".$is_display."' style='".$style."'>" . $this->format(0) . "</td>";
							}else if($columns[$key]["format"] === "on_off"){
								$returned .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
							}else{
								$returned .= "<td class='".$is_display."' style='".$style."'></td>";
							}
						}else{
							$returned .= "<td class='".$is_display."' style='".$style."'></td>";
						}
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
			foreach($filters as $filter){
				if($filter["value"] !== "-1"){

					
					if( $filter["id"] == "Client_Status" ){
						$request['id_status = '] = $filter["value"];
						$item = 'id_status = ' . $filter["value"];						
					}
					if( $filter["id"] == "Category" ){
						$request['id_category = '] = $filter["value"];
						$item = 'id_category = ' . $filter["value"];						
					}
					if( $filter["id"] == "Type" ){
						$request['id_type = '] = $filter["value"];
						$item = 'id_type = ' . $filter["value"];						
					}

					$sql .= $sql===''? $item.'<br>': ' AND '.$item.'<br>';					
				}
				
			}

		}
		
		/***********
			Body
		***********/
		$use = (isset($params['use']))? strtolower($params['use']): strtolower($this->tableName);

		//var_dump($request);
		//return $sql;
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
						
			//$background = isset($v["all_ligne"])? $v["all_ligne"]? $v["hex_string"]: "": "";
			//$background = isset($v["bg-color"])? $v["bg-color"] : "";
			$trs .= '<tr class="bg-gray-50" data-page="'.$use.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
								
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "client_status"){
						$trs .= "<td class='w-32'><span class='".$v[ 'bg-color' ]." py-1 px-3 border rounded-lg'>" . ucfirst($v[ 'client_status' ]) . "</span></td>";
					}elseif(isset($columns[$key]["format"])){
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
	
	public function ShortTableBy($params = []){

		$items = '';
		if( isset($params['request']) ){
			
			$request = strtolower($params['request']);
			
			$clients = $this->find('', ['conditions OR'=>['LOWER(CONVERT(societe_name USING latin1)) like '=>'%'.$request.'%', 'LOWER(CONVERT(first_name USING latin1)) like '=>'%'.$request.'%', 'LOWER(CONVERT(last_name USING latin1)) like '=>'%'.$request.'%'], 'order'=>'first_name DESC'], '');
		}else{
			$clients = $this->find('', ['order'=>'first_name DESC'], '');
		}
		
		
		foreach( $clients as $k=>$v){
			$name = $v["first_name"] . " " . $v["last_name"] . "<div>" . $v["societe_name"] . "</div>";
			$active = isset($params['id_table'])? $params['id_table'] === $v["id"]? "active": "": "";
			$items .= '
					<div class="item '.$active.'">
						<div class="name select_this_client" data-societe_name="'.$v["societe_name"].'" data-first_name="'.$v["first_name"].'" data-last_name="'.$v["last_name"].'" data-ville="'.$v["ville"].'" data-cin="'.$v["cin"].'" data-passport="'.$v["passport"].'" data-id="'.$v["id"].'"> '.$name.' </div>
					</div>
			';
			
		}
		
		return $items;
		
	}
	
	public function ShortTable($params = []){
		$template = '
			
			<div class="short_table">
				<div class="search_bar">
					<input type="text" class="request" data-controler="Client" data-id="id_client">
				</div>
				
				<div class="result">
					{{items}}
				</div>
			</div>
		
		';
		$items = '';
		if( isset($params['request']) ){
			$clients = $this->find('', ['conditions OR'=>['LOWER(societe_name) like '=>'%'.$params['request'].'%', 'LOWER(first_name) like '=>'%'.$params['request'].'%', 'LOWER(last_name) like '=>'%'.$params['request'].'%'], 'order'=>'first_name DESC'], '');
		}else{
			$clients = $this->find('', ['order'=>'first_name DESC'], '');
		}
		
		
		foreach( $clients as $k=>$v){
			$name = $v["first_name"] . " " . $v["last_name"] . "<div>" . $v["societe_name"] . "</div>";
			$active = isset($params['id_table'])? $params['id_table'] === $v["id"]? "active": "": "";
			$items .= '
					<div class="item '.$active.'">
						<div class="name select_this_client" data-societe_name="'.$v["societe_name"].'" data-first_name="'.$v["first_name"].'" data-last_name="'.$v["last_name"].'" data-ville="'.$v["ville"].'" data-cin="'.$v["cin"].'" data-passport="'.$v["passport"].'" data-id="'.$v["id"].'"> '.$name.' </div>
					</div>
			';
			
		}
		
		return str_replace("{{items}}", $items, $template);
		
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
				
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$data = [
			'UID'						=>	addslashes($params['columns']['UID']),
			'created'					=>	$created,
			'created_by'				=>	$created_by,
			'updated'					=>	$created,
			'id_type'					=>	$params['columns']['id_type'],
			'id_category'				=>	$params['columns']['id_category'],
			'id_status'					=>	$params['columns']['id_status'],
			'id_color'					=>	$params['columns']['id_color'],
			'first_name'				=>	addslashes($params['columns']['first_name']),
			'last_name'					=>	addslashes($params['columns']['last_name']),
			'societe_name'				=>	addslashes($params['columns']['societe_name']),
			'cin'						=>	addslashes($params['columns']['cin']),
			'passport'					=>	addslashes($params['columns']['passport']),
			'phone_1'					=>	addslashes($params['columns']['phone_1']),
			'phone_2'					=>	addslashes($params['columns']['phone_2']),
			'adresse'					=>	addslashes($params['columns']['adresse']),
			'ville'						=>	addslashes($params['columns']['ville']),
			'email'						=>	addslashes($params['columns']['email']),
			'notes'						=>	addslashes($params['columns']['notes']),
			'status'					=>	$params['columns']['status']
		];
		
		if( isset($params['columns']["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["id"] = $params['columns']["id"];
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "Client: " . $data["first_name"] . " " . $data["last_name"];
				$this->saveActivity("fr", $created_by, ['Client', 0], $data["id"], $msg);				
			}else{
				$msg = "Client: " . $data["first_name"] . " " . $data["last_name"];
				$this->saveActivity("fr", $created_by, ['Client', 1], $this->getLastID(), $msg);
			}

			return 1;
			
		}else{
			return $this->err;
		}		
		
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
							<img class="download_file" data-link="' . $image["file_link"] . '" src="'.$image["file_icon"].'">
						</div>
						<div class="info" style="flex:1; text-align:left">
							<div class="name">' . $image["file_name"] . '</div>
						</div>
						<div class="image_actions">
							<button class="red remove-file" data-uid="' . $params["UID"] . '" data-folder="' . $params["folder"] . '" data-controler="Proprietaire" data-function="DeleteFile" data-filename="' . $image["file_name"] . '"><i class="far fa-trash-alt"></i></button>
						</div>
					</li>
			
			';
		}
		$lis = $lis===''? $empty: $lis;
		return str_replace(["{{li}}"], [$lis], $template);
	}
	
	public function DeleteFile($params){
		$created_by = $_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$this->saveActivity("fr",$created_by,array("Proprietaire","3"),0,"Fichier : " . $params["file_name"]);
		$folder = $_SESSION["UPLOAD_FOLDER"].$params["folder"].DIRECTORY_SEPARATOR.$params["UID"].DIRECTORY_SEPARATOR.$params["file_name"];
		if(file_exists($folder)){
			return unlink($folder)? 1:0;
		}else{
			return 0;
		}

	}
	
	
}
$client = new Client;