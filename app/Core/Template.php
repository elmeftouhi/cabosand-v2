<?php
require_once('Helpers/Modal.php');

class Template extends Modal{

// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("manager_template");
		}catch(Exception $e){
			$this->err->save("Template -> Constructeur","$e->getMessage()");
		}
	}	
}
$template = new Template;