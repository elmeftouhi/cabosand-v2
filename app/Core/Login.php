<?php
require_once('Helpers/Config.php');
require_once('Person.php');

class Login extends Modal{
	
	private $tableName = 'Person';
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
	
	public function IsExists($params){

		$data = $this->find(null,["conditions AND"=> $params ],"v_person");
		if (count($data)>0){
			return $data;			
		}else{
			return false;
		}
		
	}
	
	public function Analyze($params){
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];

		$msg = ['code'=>0, 'msg'=>'Error!'];
		
		if (isset($params['email'], $params['password'], $params['remember'], $params['token'])){
			
			if( !empty($params['email']) && !empty($params['password']) && !empty($params['remember']) && !empty($params['token']) ){

				if(isset($_SESSION[$env]['token'])){

					if($params['token'] === $_SESSION[$env]['token']){

						$email = addslashes($params['email']);
						$password = md5($params['password']);

						if (strlen($email) > 6){
							$person = $this->IsExists( [ 'login='=>$email, 'password='=>$password  ] );

							if( $person ){

								if( $person[0]['status'] == '1' ){
									unset($_SESSION[$env]['token']);
									$_SESSION[$env]["USER"] = $person[0];

									if($params['remember']){
										setcookie($env.'-EMAIL', $email, time() + (86400 * 30), "/");
										setcookie($env.'-PASSWORD', $params['password'], time() + (86400 * 30), "/");												
									}else{
										$expire = time() - 300;
										setcookie($env.'-EMAIL', '', $expire);
										setcookie($env.'-PASSWORD', '', $expire);											
									}

									//$person->saveActivity("fr",$data[0]["id"],array("Log",1),"0");
									$msg = ['code'=>1, 'msg'=>'Account Success'];
								}else{
									$msg = ['code'=>-1, 'msg'=>'Account Disabled'];
								}
							}else{
								$msg = ['code'=>0, 'msg'=>'Peron Not Exists'];
							}
						}else{
							$msg = ['code'=>0, 'msg'=>'Data Format Error'];	
						}
					}else{
						$msg = ['code'=>0, 'msg'=>'Session Token Error'];
					}						
				}else{
					$msg = ['code'=>0, 'msg'=>'Session Expired'];
				}
			}else{
				$msg = ['code'=>0, 'msg'=>'Empty Params'];
			}
		}else{
			$msg = ['code'=>0, 'msg'=>'Unset Params'];
		}
		
		return $msg;
		
	}
	
	public function Auth($params){
		
		$auth = $this->Analyze($params);
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];
		
		if($auth['code'] == 1){
			$this->saveActivity("fr",$_SESSION[$env]["USER"]["id"],array("Log",1),"0");
			return "success";
		}else if($auth['code'] == -1){
			return "disabled";
		}else{
			return $auth['msg'];
		}
		
	}
	
	public function Logout(){
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];
		
		if( isset( $_SESSION[$env]["USER"] ) ){
			$this->saveActivity("fr",$_SESSION[$env]["USER"]["id"],array("Log",0),"0");
			unset($_SESSION[$env]["USER"]);
		}
		
	}
	
}
$login = new  Login;