<?php
require_once('Helpers/Modal.php');

class Propriete_Category extends Modal{

	private $columns_ = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "propriete_category", "label"=>"Catégorie de Propriété"),
		array("column" => "is_default", "label"=>"PAR DEFAUT", "width"=>90)
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("propriete_category");
		}catch(Exception $e){
			$this->err->save("Template -> Constructeur","$e->getMessage()");
		}
	}	
	
		
	public function getColumns(){
		
		if ( isset($this->columns) ){
			return $this->columns;
		}else{
			$columns = array();
			$l = new ListView();
			foreach($l->getDefaultStyle("propriete_category")["data"] as $k=>$v){
				array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
				
			}
			array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
			return $columns;
		}
		
	}

	public function Remove($params){
		if(isset($params["id"])){
			
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) === 1){
				$data = $data[0];
				$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
				$msg = $data["propriete_category"];
				$this->saveActivity("fr", $created_by, ['Propriete_Category', -1], $data["id"], $msg);
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
			'propriete_category'	=>	addslashes($params['propriete_category']),
			'is_default'		=>	$params['is_default']=="true"? 1: 0,
		];
		
		if( isset($params["id"]) ){
			$data["id"] = $params["id"];
		}
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = $params["propriete_category"];
				$this->saveActivity("fr", $created_by, ['Propriete_Category', 0], $data["id"], $msg);				
			}else{
				$msg = $data["propriete_category"] ;
				$this->saveActivity("fr", $created_by, ['Propriete_Category', 1], $this->getLastID(), $msg);	
			}

			return 1;
			
		}else{
			return $this->err;
		}		
		
	}

}
$propriete_category = new Propriete_Category;