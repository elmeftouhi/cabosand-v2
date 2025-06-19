<?php
require_once('Helpers/Modal.php');

class Support extends Modal{

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
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("support");
		}catch(Exception $e){
			$this->err->save("Template -> Constructeur","$e->getMessage()");
		}
	}	
	
		
	public function getColumns(){
		
		if ( isset($this->columns) ){
			return $this->columns;
		}else{
			$columns = array();
			//var_dump($this->getColumnsName("client"));
			foreach($this->getColumnsName("support") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}
$support = new Support;