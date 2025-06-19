<?php
require_once('Helpers/Modal.php');

class Client_Type extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "client_type", "label"=>"Type de Client"),
		array("column" => "is_default", "label"=>"PAR DEFAUT", "width"=>90)
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("client_type");
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
			foreach($this->getColumnsName("client_type") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}
$client_type = new Client_Type;