<?php
require_once('Helpers/Modal.php');

class Complexe_Facilities extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "complexe_facilities", "label"=>"Complexe Facilities")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("complexe_facilities");
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
			foreach($this->getColumnsName("complexe_facilities") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}
$complexe_facilities = new Complexe_Facilities;