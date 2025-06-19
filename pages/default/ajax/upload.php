<?php
session_start();


	$core = $_SESSION["CORE"];
	$host = $_SESSION["HOST"];
	$upload_folder = $_SESSION["UPLOAD_FOLDER"];

	$dS = DIRECTORY_SEPARATOR;

	$folder 	= 	"";
	$sub_folder	=	"";
	$is_unique	= "0";

	$autorizedExt = [
		"jpg", 
		"jpeg", 
		"png", 
		"gif", 
		"bmp", 
		"JPG", 
		"JPEG", 
		"doc", 
		"docx", 
		"pdf"
	];

	$autorizedType = [
		"image/jpeg",
		"image/gif",
		"image/png",
		"image/bmp",
		"image/jpg",
		"application/msword",
		"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		"application/pdf"
	];

	$error = '';
	$continu = false;
	$filename = "";

	if(isset($_FILES['upload'])){
		if(!empty($_FILES['upload']) && ($_FILES['upload']['error'] == 0)){
			if(in_array($_FILES['upload']['type'], $autorizedType)){
				if($_FILES['upload']['size']>10000){	// > 20 ko
					if($_FILES['upload']['size']<10000000){	// < 4.0 Mo
						$filename = basename($_FILES['upload']['name']);
						$ext = substr($filename, strrpos($filename, '.') + 1);
						if (in_array($ext, $autorizedExt) ){
							if(isset($_GET['folder'], $_GET['uid'])){
								if(!empty($_GET["folder"]) || !empty($_GET["uid"]) ){
									$folder 	= 	addslashes( $_GET['folder'] );
									$sub_folder	=	addslashes( $_GET['uid'] );
									$is_unique = isset($_GET['is_unique'])? addslashes($_GET['is_unique']): "0";
									$upload_folder .= $dS.$folder.$dS.$sub_folder.$dS;
									
									
									$continu=true;
								}else{$error= "Empty Parameters"; }	
							}else{$error= "Missing Parameters"; }										
						}else{ $error= "Unsupported Format"; }
					}else{ $error= "Check Size Of File"; }
				}else{ $error= "File Too Short"; }
			}else{ $error= "Unsupported Format"; }
		}else{ $error="Empty Data"; }
	}else{ $error="Data Not Set"; }


	if ($continu){

		if (!file_exists($upload_folder)) {
			mkdir($upload_folder, 0777, true);
		}elseif(  $is_unique === "1"){
			array_map('unlink', glob("$upload_folder/*.*"));
		}	

		$fileSize = round($_FILES['upload']['size'] /1000000 , 2);
		$lastId = time();
		$ext = substr($filename, strrpos($filename, '.') + 1);
		$new_file = $upload_folder.$filename; // .$lastId.'.'.$ext;

		//$_SESSION["UPLOADED_FILE"] = $lastId.'.'.$ext;


		if (move_uploaded_file($_FILES['upload']['tmp_name'], $new_file)) {
			echo 1; 
		}else{
			echo '0';
		}
	}else{
		echo $error;
	}
