<?php
require_once('Helpers/Modal.php');

class Propriete_Modalite_Paiement extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>50),
		array("column" => "propriete_modalite_paiement", "label"=>"Modalités de Paiement")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("propriete_modalite_paiement");
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
			foreach($this->getColumnsName("propriete_modalite_paiement") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}

$propriete_modalite_paiement = new Propriete_Modalite_Paiement;