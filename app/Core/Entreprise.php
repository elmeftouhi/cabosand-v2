<?php
require_once('Helpers/Modal.php');

class Entreprise extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
		array("column" => "raison_social", "label"=>"SOCIETE", "style"=>"font-weight:bold"),
		array("column" => "ice", "label"=>"ICE", "style"=>"min-width:80px; width:130px; color:black; font-size:16px"),
		array("column" => "registre_commerce", "label"=>"REGISTRE C.", "style"=>"min-width:80px; width:130px; color:black; font-size:16px"),
		array("column" => "identification_fiscale", "label"=>"IDENTIFICATION F.", "style"=>"min-width:80px; width:130px; color:black; font-size:16px"),
		array("column" => "patente", "label"=>"PATENTE", "style"=>"min-width:80px; width:130px; color:black; font-size:16px"),
		array("column" => "cnss", "label"=>"CNSS", "style"=>"min-width:80px; width:130px; color:black; font-size:16px"),
		array("column" => "is_default", "label"=>"PAR DEFAUT", "style"=>"min-width:80px; width:130px"),
		array("column" => "status", "label"=>"STATUS", "style"=>"min-width:80px; width:80px"),
		array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("entreprise");
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
			foreach($this->getColumnsName("entreprise") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
		
	public function FindBy($params){
		
		$code = addslashes( strtolower($params['request']) );
		$data = $this->find('', ['conditions'=>['lower(raison_social)='=>$code] ], '');
		return count( $data ) === 1? $data[0]: 0;
		
	}
}
$entreprise = new Entreprise;