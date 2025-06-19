<?php
require_once('Helpers/Modal.php');
require_once('Helpers/View.php');

class Proprietaire extends Modal{

	private $tableName = __CLASS__;
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
			
			/*
			foreach($this->fetchAll() as $k=>$v){
				if( $v["UID"] === "0" || $v["UID"] === "" ){
					$this->save(['id'=>$v["id"], 'UID' =>  md5( uniqid('auth', true) )  ]);
				}
			}
			*/
			
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
	
	public function drawTable($columns = null, $conditions=null, $user=null){
		if($columns == null){
			$columns = $this->getColumns();
		}
		$returned = '<table class="table">';
		$returned .= '	<thead>';
		$returned .= '		<tr>';
		
		foreach($columns as $key=>$value){
			if(isset($value["width"])){
				$returned .=  "<th style='width:" . $value["width"] . "'>" . $value["label"] . "</th>";
			}else{
				$returned .=  "<th>" . $value["label"] . "</th>";
			}
		}
		$returned .= '		<th style="width:55px;"></th>';
		$returned .= '		</tr>';
		$returned .= '	</thead>';
		$returned .= '<tbody>';
		
		if($conditions==null)
			$values = $this->fetchAll();
		else
			$values = $this->find("",$conditions,"");
		
		foreach($values as $k=>$v){
			$returned .= '	<tr class="select_this_proprietaire" data-adresse="'. $v["adresse"] .'" data-id="' . $v["id"] . '" data-name="'. $v["name"] .'" data-ville="'. $v["ville"] .'">';
			foreach($columns as $key=>$value){
				if(isset($v[ $columns[$key]["column"] ])){
					$returned .= "<td>" . $v[ $columns[$key]["column"] ] . "</td>";
				}else{
					$returned .=  "<td>NaN</td>";
				}
			}	
			$returned .= '<td><button style="margin:0" data-adresse="'. $v["adresse"] .'" data-id="' . $v["id"] . '" data-name="'. $v["name"] .'" data-ville="'. $v["ville"].'" class="select_this_proprietaire btn btn-blue" value="'.$v["id"].'"><i class="fas fa-check-circle"></i></button></td>';
			$returned .= '	</tr>';
		}
		$returned .= '</tbody>';	
		$returned .= '</table>';
		return $returned;
		
	}
	
	public function ShortTable($params = []){
		$template = '
			
			<div class="short_table h-96 overflow-y-auto">
				<div class="search_bar">
					<input type="text" class="request" data-controler="Proprietaire" data-id="id_proprietaire">
				</div>
				
				<div class="result">
					{{items}}
				</div>
			</div>
		
		';
		$items = '';
		if( isset($params['request']) ){
			$proprietaires = $this->find('', ['conditions OR'=>['name like '=>'%'.$params['request'].'%', 'phone_1 like '=>'%'.$params['request'].'%'], 'order'=>'name DESC'], '');
		}else{
			$proprietaires = $this->find('', ['order'=>'name DESC'], '');
		}
		
		
		foreach( $proprietaires as $k=>$v){
			$rib = $v["agence_1"];
			$rib .= $rib === ""? $rib: " : ".$v["rib_1"];
			$active = isset($params['id_table'])? $params['id_table'] === $v["id"]? "active": "": "";
			$items .= '
					<div class="item '.$active.'">
						<div class="name select_this_proprietaire" data-email="'.$v["email"].'" data-telephone="'.$v["phone_1"].'" data-name="'.$v["name"].'" data-ville="'.$v["ville"].'" data-rib="'.$rib.'" data-id="'.$v["id"].'"> '.$v["name"].' </div>
					</div>
			';
			
		}
		
		return str_replace("{{items}}", $items, $template);
		
	}
	
	public function ShortTableBy($params = []){

		$items = '';
		if( isset($params['request']) ){
			$proprietaires = $this->find('', ['conditions OR'=>['LOWER(CONVERT(name USING latin1)) like '=>'%'. strtolower($params['request']).'%', 'phone_1 like '=>'%'.$params['request'].'%'], 'order'=>'name DESC'], '');
		}else{
			$proprietaires = $this->find('', ['order'=>'name DESC'], '');
		}
		
		
		foreach( $proprietaires as $k=>$v){
			$rib = $v["agence_1"];
			$rib .= $rib === ""? $rib: " : ".$v["rib_1"];
			$active = isset($params['id_table'])? $params['id_table'] === $v["id"]? "active": "": "";
			$items .= '
					<div class="item '.$active.'">
						<div class="name select_this_proprietaire" data-email="'.$v["email"].'" data-telephone="'.$v["phone_1"].'" data-name="'.$v["name"].'" data-ville="'.$v["ville"].'" data-rib="'.$rib.'" data-id="'.$v["id"].'"> '.$v["name"].' </div>
					</div>
			';
			
		}
		
		return $items;
		
	}
	
	public function FindBy($params){
		
		$code = addslashes( strtolower($params['request']) );
		$data = $this->find('', ['conditions'=>['LOWER(CONVERT(name USING latin1)) like'=>$code.'%'] ], '');
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

					
					if( $v["id"] === "Status" ){
						$request['status = '] = $v["value"];
						$item = 'status = ' . $v["value"];						
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
		$push['Obj']	=	new Proprietaire;
		
		$view = new View("proprietaire.create");
		return $view->render($push);
	}
	
	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				
				if( count($this->find('', ['conditions' => ['id_proprietaire='=>$params['id']] ], 'depense') ) === 0 ){
					if( count($this->find('', ['conditions' => ['id_proprietaire='=>$params['id']] ], 'propriete') ) === 0 ){	
						if( count($this->find('', ['conditions AND' => ['module='=>'proprietaire', 'id_module='=>$params['id']] ], 'notes') ) === 0 ){

							$data = $data[0];
							$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
							$msg = $data["name"];
							$this->delete($params["id"]);

							$this->saveActivity("fr", $created_by, ['Proprietaire', -1], $data["id"], $msg);
							$folder = $_SESSION["UPLOAD_FOLDER"]."proprietaire".DIRECTORY_SEPARATOR.$data["UID"].DIRECTORY_SEPARATOR;
							array_map('unlink', glob("$folder/*.*"));
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

		}else{
			return 0;
		}
	}
	
	public function Update($params){
		
		$push = [];
		$push['depenses'] = $this->find('', [ 'conditions' => ['id_proprietaire=' => $params['id'] ] ], 'depense');
		$push['notess'] = $this->find('', [ 'conditions AND' => ['module='=>'proprietaire', 'id_module=' => $params['id'] ], 'order'=>'created DESC' ], 'notes');
		
		$push['Obj']	=	new Proprietaire;
		
		$proprietaire = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], '');		
		if( count($proprietaire) > 0 ){
			$push['proprietaire'] = $proprietaire[0];
		}
		
		
		$view = new View("proprietaire.create");
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
			'name'						=>	addslashes($params['columns']['name']),
			'cin'						=>	addslashes($params['columns']['cin']),
			'passport'					=>	addslashes($params['columns']['passport']),
			'phone_1'					=>	addslashes($params['columns']['phone_1']),
			'phone_2'					=>	addslashes($params['columns']['phone_2']),
			'adresse'					=>	addslashes($params['columns']['adresse']),
			'ville'						=>	addslashes($params['columns']['ville']),
			'email'						=>	addslashes($params['columns']['email']),
			'agence_1'					=>	addslashes($params['columns']['agence_1']),
			'agence_2'					=>	addslashes($params['columns']['agence_2']),
			'rib_1'						=>	addslashes($params['columns']['rib_1']),
			'rib_2'						=>	addslashes($params['columns']['rib_2']),
			'notes'						=>	addslashes($params['columns']['notes']),
			'status'					=>	$params['columns']['status']
		];
		
		if( isset($params['columns']["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["id"] = $params['columns']["id"];
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = "Nom: " . $data["name"];
				$this->saveActivity("fr", $created_by, ['Proprietaire', 0], $data["id"], $msg);				
			}else{
				$msg = "name: " . $data["name"];
				$this->saveActivity("fr", $created_by, ['Proprietaire', 1], $this->getLastID(), $msg);
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
							<img class="download_file" data-link="' . $image["file_src"] . '" src="'.$image["file_icon"].'">
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
$proprietaire = new Proprietaire;