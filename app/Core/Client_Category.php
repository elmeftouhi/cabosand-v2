<?php
require_once('Helpers/Modal.php');

class Client_Category extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "client_category", "label"=>"Catégorie de Client"),
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
}

$client_category = new Client_Category;