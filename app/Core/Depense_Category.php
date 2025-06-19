<?php
require_once('Helpers/Modal.php');

class Depense_Category extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "depense_category", "label"=>"Catégorie de Dépense"),
		array("column" => "is_default", "label"=>"PAR DEFAUT", "width"=>90)
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

	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				$data = $data[0];
				$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
				$msg = $data["depense_category"];
				$this->saveActivity("fr", $created_by, ['Depense_Category', -1], $data["id"], $msg);
				$this->delete($params["id"]);
				return 1;
			}else{
				return 0;
			}

		}else{
			return 0;
		}
	}

	public function Store($params){
		
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];

		if($params['is_default'] == "true"){
			foreach($this->fetchAll() as $f){
				$this->save(['id'=>$f['id'], 'is_default'=>0]);
			}
		}

		$data = [
			'depense_category'	=>	trim(addslashes($params['depense_category'])),
			'is_default'		=>	$params['is_default']=="true"? 1: 0,
		];
		
		if( isset($params["id"]) ){
			$data["id"] = $params["id"];
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = $params["depense_category"];
				$this->saveActivity("fr", $created_by, ['Depense_Category', 0], $data["id"], $msg);				
			}else{
				$msg = $data["depense_category"] ;
				$this->saveActivity("fr", $created_by, ['Depense_Category', 1], $this->getLastID(), $msg);	
			}

			return 1;
			
		}else{
			return $this->err;
		}		
		
	}

	public function GetListAsSelect(){
		$select = '<select class="bulk_change_type_selected">';
		$select .= '	<option value="-1">-- Catégories </option>';
		foreach($this->find('', ['order'=>'depense_category'], '') as $item){
			$select .= '	<option value="'.$item["id"].'">'.ucfirst($item["depense_category"]).'</option>';
		}
		$select .= '</select>';
		return $select;
	}
}
$depense_category = new Depense_Category;