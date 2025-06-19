<?php
if(!isset($_SESSION['CORE'])){die(-1);}


class Mail{
	private $param = array(
							"host"			=>	"mail.aspi-confort.com",
							"port"			=>	"465",
							"smtp_secure"	=>	"ssl",
							"is_smtp_auth"	=>	true,
							"user_name"		=>	"contact@aspi-confort.com",
							"password"		=>	"1A2Z3E4R5T6Y",
							"from_name"		=>	"ASPICONFORT"
	
							);
	
	/*
	
	$values = array(
					"to=>"exemple@email.com",
					"message" =>	"message to send",
					"subject" =>	"Subject to send"
					);
	
	*/
	public function send($values=null){
		
		$s = DIRECTORY_SEPARATOR;
		require_once($_SESSION["CORE"].'..'.$s.'Libs'.$s.'PHPMailer'.$s.'PHPMailerAutoload.php');
				
		$email = new PHPMailer();

		$email->SMTPDebug  = 0; 
		$email->IsSMTP();
		$email->Mailer = "smtp";
		$email->Host = $this->param["host"];
		$email->Port = $this->param["port"]; 
		$email->SMTPAuth = $this->param["is_smtp_auth"];
		$email->SMTPSecure = $this->param["smtp_secure"];
		$email->Username = $this->param["user_name"];
		$email->Password = $this->param["password"];
		//$email->Timeout  =   60;

		$email->AddReplyTo($this->param["user_name"]);
		$email->setFrom($this->param["user_name"],$this->param["from_name"]);

		$email->AddCustomHeader("List-Unsubscribe: <mailto:unsubscribe@gestore.ma?subject=Unsubscribe>, <http://www.gestore.ma/unsubscribe.php?mailid=1234>");

		$email->Subject   = $values["subject"];

		$email->MsgHTML( $values["message"] );
		$email->IsHTML(true); 
		$email->CharSet="utf-8";

		$email->AddAddress($values["to"]);

		$email->SMTPOptions = array(
									'ssl' => array(
										'verify_peer' => false,
										'verify_peer_name' => false,
										'allow_self_signed' => true
									)
		);

		if (!$email->send()) {
			return $email->ErrorInfo;
		} else {
			return "success";
		}
	}
	
	
	/*
	
	$paramas = array(
					"campaign=>"campaign_name",
					"replace"=>array(
										"{{name}}": "name_value",
										"{{link}}": "link"
								)
	
					);
	
	*/
	public function getCampaign($params){
				
		$s = DIRECTORY_SEPARATOR;
		$core = $_SESSION["CORE"]."..".$s."..".$s;
		$host = $_SESSION["HOST"];
		
		$lang = (isset($_SESSION["GESTORE"]["params"]["lang"]["lang"]))? $_SESSION["GESTORE"]["params"]["lang"]["lang"] : "fr";
		$name = $params["campaign"];
		

		if(file_exists($core."templates".$s."campaign".$s.$lang.$s.$name.$s."index.html")){

			$message = file_get_contents($core."templates".$s."campaign".$s.$lang.$s.$name.$s."index.html"); 
			
			if(isset($params["replace"])){
				foreach($params["replace"] as $k=>$v){
					$message = str_replace($k, $v, $message);	
				}				
			}

			return $message;

		}else{
			return -1;
		}
		
		
	}
		
	
}
$_mail = new Mail;