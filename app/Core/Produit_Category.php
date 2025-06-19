<?php
require_once($_SESSION['CORE'].'Helpers/Modal.php');

class Produit_Category extends Modal{
	
	private $columns = array(
		array("column" => "id", "label"=>"#ID"),
		array("column" => "produit_category", "label"=>"CATEGORIE"),
		array("column" => "created", "label"=>"CREE LE"),
		array("column" => "nbr", "label"=>"PRODUIT"),
		array("column" => "status", "label"=>"STATUS")
	);
	
// construct
	public function __construct(){
		parent::__construct();
		$this->setTableName("produit_category");
	}
	
	
	public function add($data){
			 

		$columns = array("id_status","id_list","link","element","UID");
		$isAllRight = true;
		
		foreach($columns as $k=>$v){
						
			if( !isset( $data[$v] ) ){
				$isAllRight = false;
			}
			
		}
		if($data['id'] == ''){
			unset($data['id']);
		}
		if($isAllRight){ 
			echo $this->save($data);
		}else{
			echo 0;
		}

	}
	
	public function getColumns(){
		
		if ( isset($this->columns) ){
			return $this->columns;
		}else{
			$columns = array();
			//var_dump($this->getColumnsName("client"));
			foreach($this->getColumnsName("produit_category") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
}
$produit_category = new Produit_Category;