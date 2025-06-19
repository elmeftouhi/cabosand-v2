<?php

class Upload{
	
	public function __construct($params){
		
		$core = $_SESSION["CORE"];
		$host = $_SESSION["HOST"];
		$upload_folder = $_SESSION["UPLOAD_FOLDER"];

		$dS = DIRECTORY_SEPARATOR;
		
		$folder 	= 	$params['folder'];
		$file_name	=	$params['file_name'];
		
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
		
		// Default constructor
	}
}