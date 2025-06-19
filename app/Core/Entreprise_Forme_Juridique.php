<?php
require_once('Helpers/Modal.php');

class Entreprise_Forme_Juridique extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
		array("column" => "forme_juridique", "label"=>"FORME JURIDIQUE", "style"=>"font-weight:bold"),
		array("column" => "ABR", "label"=>"ABR", "style"=>"min-width:80px; width:130px; color:blue; font-size:16px"),
		array("column" => "is_default", "label"=>"PAR DEFAUT", "style"=>"min-width:80px; width:130px"),
		array("column" => "status", "label"=>"STATUS", "style"=>"min-width:80px; width:80px"),
		array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("entreprise_forme_juridique");
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
			foreach($this->getColumnsName("entreprise_forme_juridique") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}
$entreprise_forme_juridique = new Entreprise_Forme_Juridique;