<?php

class Route{
	
	public function Exist($params){
		
		$pages = $_SESSION["ROOT"] . "pages" . DIRECTORY_SEPARATOR . "default" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR;
		
		$page_not_found = '
			
			<div class="page_not_found">
				<div class="message">
					<div class="icon">
						<i class="fas fa-paint-roller"></i>
					</div>
					<div class="text">
						This Page Is Under Maintenance, it will be active Very Soon!
					</div>
				</div>
			</div>
		
		';
		
		if(isset($params["main"])){
			if(isset($params["sub"])){
				if(file_exists($pages.$params["main"].DIRECTORY_SEPARATOR.$params["sub"].".php")){
					return 1;
				}else{
					return $page_not_found;
				}
			}else{
				if( file_exists($pages. str_replace(".", DIRECTORY_SEPARATOR ,$params["main"]) . ".php") ){
					return 1;
				}else{
					return $page_not_found;
				}
			}
		}else{
			return $page_not_found;
		}
	
		
	}
	
}