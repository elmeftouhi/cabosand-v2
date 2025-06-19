<?php
require_once('Helpers/Modal.php');
require_once("Helpers/ListView.php");

class Propriete_Document_Category extends Modal{

	private $columns_ = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "document_category", "label"=>"Catégorie du Document")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("propriete_document_category");
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
			foreach($l->getByName("propriete_document_category",true)["default"] as $k=>$v){
				array_push($columns, array("column" => $v["column"], "label" => $v["label"]) );
			}
			return $columns;
		}
		
	}
}

$propriete_document_category = new Propriete_Document_Category;


/*

$l = new ListView();
var_dump($l->getByName("caisse",true));
*/