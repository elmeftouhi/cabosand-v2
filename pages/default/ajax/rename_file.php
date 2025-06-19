<?php session_start();

if(!isset($_SESSION['CORE'])){die("-1");}
if(!isset($_POST['link'], $_POST['new_name'])){die("-2");}

$link = $_POST['link'];

$path_parts = pathinfo($link);

if(file_exists($link)){
	$old_name = $path_parts["filename"];
	$new_name = $_POST['new_name'];
	
	$new_link = str_replace($old_name, $new_name, $link);
	if($new_link === $link){
		echo 1;
	}else{
		if(file_exists($new_link)){
			echo 'file exists';
		}else{
			if(rename($link,$new_link)){
				if (strpos($link, 'contrat') !== false) {
					$msg = $old_name . " par " . $new_name;
					require_once($_SESSION['CORE']."Contrat.php");
					$contrat->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Contrat","2"),0,$msg);					
				}elseif (strpos($link, 'propriete') !== false) {
					$msg = $old_name . " par " . $new_name;
					require_once($_SESSION['CORE']."Propriete.php");
					$propriete->saveActivity("fr",$_SESSION["CABOSANDE-MANAGER"]["USER"]["id"],array("Propriete","2"),0,$msg);	
				}

				echo 1;
			}
			
			
			
		}		
	}

}else{
	echo 'not found!';
}





