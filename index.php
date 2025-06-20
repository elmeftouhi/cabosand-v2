<?php
session_start();
/*
print_r(scandir(session_save_path()));
*/
$project_name = basename(__DIR__);
$host_dev = "localhost/apps/" . $project_name . "/";
$host_prod = $_SERVER['SERVER_NAME'] . "/";
$envirenment = "CABOSAND-MANAGER";
$D_S = DIRECTORY_SEPARATOR;

$errors = array();
$actions = array();
$url =array();
$lang = "";	// 	es | ar | fr | en
$dir = "";	//	ltr | rtl | auto

//unset($_SESSION[$envirenment]["USER"]);

require_once("app".$D_S."Core".$D_S."Helpers".$D_S."Config.php");

/*
 *---------------------------------------------------------------
 * APP_ENVIRONMENT
 *---------------------------------------------------------------
 *
 */

if($config->getEnv() == "dev"){
	error_reporting(E_ALL);
	define('APP_ENVIRONMENT', 'dev');
	$filesDirectory = $host_dev.'../statics/';
	define('HOST',$host_dev);
	define('HTTP',"http://");


} else{
	error_reporting(0);
	define('APP_ENVIRONMENT', 'prod');
	$filesDirectory = "statics.cabosand.ma/";

	define('HOST',$host_prod);
	if( isset($_SERVER['HTTPS'] ) ) {
		define('HTTP',"https://");
	}else{
		define('HTTP',"http://");
	}
}


/*
 *---------------------------------------------------------------
 * GLOBAL PARAMETERS
 *---------------------------------------------------------------
 *
 */


define('ROOT', $D_S);
define('APP', 'app'.$D_S);

define('CORE', APP.'Core'.$D_S);
define('APP_PAGES','pages'.$D_S);

define('GLOBAL_TEMPLATE_PATH','templates'.'/global/');

define('UPLOAD_FOLDER','files'.$D_S.'upload'.$D_S);
define('DOWNLOAD_FOLDER','files'.$D_S.'download'.$D_S);

$_SESSION["HOST"] = HOST;
$_SESSION["CORE"] = realpath(dirname(__FILE__)).$D_S.CORE;
$_SESSION["ROOT"] = realpath(dirname(__FILE__)).$D_S;

$_SESSION["UPLOAD_FOLDER"] = realpath(dirname(__FILE__)).$D_S."..".$D_S."statics".$D_S;

$_SESSION["STATICS"] = HTTP . $filesDirectory;



if(!file_exists(CORE."Helpers".$D_S."Modal.php")){
	$errors["FILE_NOT_EXISTS"] = CORE."Helpers".$D_S."Modal.php";
	
}else{
	require_once(CORE."Helpers".$D_S."Modal.php");
	//require_once(CORE."Helpers".$D_S."Utils.php");
	$modal = new Modal;
	if (!$modal->isConnected){
		$errors["DB_CONNECT"] = $modal->err;
	}
}
var_dump($config);

/*
	*************
	Uncomment this section when you are using the login section
	
	*************
*/
if(!isset($_SESSION[$envirenment]["USER"])){
	$errors["USER"] = "unknown";
}


if (count($errors)>0){
	
	if(isset($errors["FILE_NOT_EXISTS"])){
		
		$page_title = "FILE NOT FOUND - " . $errors["FILE_NOT_EXISTS"];
		define('APP_TEMPLATE',"errors");
		$page = "404";
		
	}elseif(isset($errors["DB_CONNECT"])){
		
		$page_title = "DB ERRORS";
		define('APP_TEMPLATE',"errors");
		$page = "db_error";
		
	}elseif(isset($errors["USER"])){
		
		$page_title = $envirenment . " : Log In";
		define('APP_TEMPLATE',"login");
		$page = "index";
		
	}

}else{
	
	if(isset($_GET['url'])){
		
		$url = explode("/",rtrim($_GET['url'], '/'));

		require_once(CORE."Template.php");
		$templates = $template->find('all',array('conditions'=>array('status='=>1)),null);
		
		// Define wether lang parameter is set in Session
		
		if(isset($_SESSION[$envirenment]["params"]["lang"])){
			
			$page_title = $_SESSION[$envirenment]["params"]["lang"]["title"];
			$lang = $_SESSION[$envirenment]["params"]["lang"]["lang"];
			$dir = $_SESSION[$envirenment]["params"]["lang"]["dir"];
			
			$page = $url[0];
			
		}else{
			
			$page_title = $templates[0]["template_title"];
			$lang = $templates[0]["template_lang"];
			$dir = $templates[0]["template_direction"];
			
			$page = $url[0];
		}

		
		if(!file_exists(APP_PAGES.$templates[0]["template_name"].DIRECTORY_SEPARATOR.$page.'.php')){
			$page_title = "404 - Page not found";
			define('APP_TEMPLATE',"errors");
			$page = "404";
		}else{
			define('APP_TEMPLATE',$templates[0]["template_name"]);
		}	

	}else{
		
		if(file_exists(CORE."Template.php")){
			
			require_once(CORE."Template.php");
			$templates = $template->find('all',array('conditions'=>array('status='=>1)),null);
			
			if(isset($_SESSION[$envirenment]["params"]["lang"])){

				$page_title = $_SESSION[$envirenment]["params"]["lang"]["title"];
				$lang = $_SESSION[$envirenment]["params"]["lang"]["lang"];
				$dir = $_SESSION[$envirenment]["params"]["lang"]["dir"];	
				
				$page = $templates[0]["start_page"];
				
			}else{

				$page_title = $templates[0]["template_title"];
				$lang = $templates[0]["template_lang"];	
				$dir = $templates[0]["template_direction"];	
				
				$page = $templates[0]["start_page"];
			}
			
			define('APP_TEMPLATE',$templates[0]["template_name"]);
			
			
		}else{
			
			$page_title = "404 - Page not found";
			define('APP_TEMPLATE',"errors");
			$page = "404";
			
		}
	}
	
}

/*
 *---------------------------------------------------------------
 * SETUP TEMPLATE
 *---------------------------------------------------------------
 *
 */
//var_dump($_SESSION);
//unset($_SESSION['LOCATOR-APP']);

define('APP_TEMPLATE_PATH','templates'.$D_S.APP_TEMPLATE.$D_S);

if(APP_TEMPLATE !== "errors" and APP_TEMPLATE !== "login"){
	require_once(GLOBAL_TEMPLATE_PATH.'header.php');
}
	
require_once(APP_TEMPLATE_PATH . 'header.php');
require_once(APP_PAGES.APP_TEMPLATE.$D_S.$page.'.php');
require_once(APP_TEMPLATE_PATH . 'footer.php');

if(APP_TEMPLATE !== "errors" and APP_TEMPLATE !== "login"){
	
	require_once(GLOBAL_TEMPLATE_PATH.'footer.php');
}

?>