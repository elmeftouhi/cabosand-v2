<?php 
class DataBase{
        public $isConnected = false;
        protected $datab;
		public $err;
	
        public function __construct($config){
            	
            try { 
				
                $this->datab = new PDO(
					"mysql:host={$config["host"]};dbname={$config["dbname"]}", 
					$config["username"], 
					$config["password"],
					array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
				); 


				$this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				$this->isConnected = true;
            } 
			
            catch(Exception $e) { 
                $this->isConnected = false;
				$this->err =  $e->getMessage();
				//echo $this->err;
                //throw new Exception($e->getMessage());
            }
        }
	
	
	
        public function Disconnect(){
            $this->datab = null;
            $this->isConnected = false;
        }
	
	
	

		public function getTables($query){
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute();
                return $stmt->fetchAll();  
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
		
	
	
		public function getall($query){
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute();
                return $stmt->fetch();  
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
        public function getRow($query, $params=array()){
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute($params);
                return $stmt->fetch();  
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
        public function getNumRows($query, $params=array()){
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute($params);
                return $stmt->rowCount();  
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
        public function getRows($query, $params=array()){
            try{ 
				if($this->isConnected){
					$stmt = $this->datab->prepare($query); 
					$stmt->execute($params);
					return $stmt->fetchAll(); 
				}else{
					return false;
				}				
			}catch(PDOException $e){
				throw new Exception($e->getMessage());
            }       
        }
        public function insertRow($query, $params=array()){
            try{ 
                $stmt = $this->datab->prepare($query); 
                return $stmt->execute($params);
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }           
        }
        public function updateRow($query, $params){
            return $this->insertRow($query, $params);
        }
        public function deleteRow($query, $params){
            return $this->insertRow($query, $params);
        }
    }
 