<?php
require_once('Modal.php');

class ListView extends Modal{
	
	public function readOrigin($column_name=null){
		
		$fileList = array();
		
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;
		
		if(file_exists($filesDirectory)){
			foreach(scandir($filesDirectory) as $k=>$v){

				if($v <> "." and $v <> ".." and strpos($v, '.') !== false){
					$file = explode(".",$v);
					
					if(file_exists(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$file[0].".json")){
						$string = file_get_contents(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$file[0].".json");
					}	
					
					$fileList[$file[0]] = json_decode($string, true);

				}

			}	

		}
		
	
		return $fileList;
		
	}

	public function readAll(){

		$fileList = array();
		
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;
		
		if(file_exists($filesDirectory)){
			foreach(scandir($filesDirectory) as $k=>$v){

				if($v <> "." and $v <> ".." and strpos($v, '.') !== false){
					$file = explode(".",$v);
					
					if(file_exists(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$file[0].".json")){
						$string = file_get_contents(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$file[0].".json");
					}	
					
					$fileList[$file[0]] = json_decode($string, true);

				}

			}	

		}
		
	
		return $fileList;
		
	}
	
	public function create($module, $data = null){
		$string = '{"0":{"is_default":1,"name":"Standard","allow_to":"","data":{';
		
		if(is_null($data) || empty($data)){
			
			$data = $this->getColumnsName($module);
			foreach( $data as $k=>$v ){
				$display = 1;
				$style = "";
				if($v["Field"] === "id") {
					$display=0;
					$style = "";
				}else{
					if($v["Comment"] === "on_off" or $v["Comment"] === "on_off_default") {
						$style = "width:90px; min-width:90px";
					}
				}

				$string .= '"'.$k.'":{"column":"'.$v["Field"].'","label":"'.$v["Field"].'","style":"'.$style.'","format":"'.$v["Comment"].'","display":'.$display.'},';
			}
			
		}else{
			foreach( $data as $k=>$v ){
				if($v["column"] !== "actions") {
					$column = (isset($v["column"]))? $v["column"] : "";
					$display = (isset($v["display"]))? $v["display"] : 1;
					$style = (isset($v["style"]))? $v["style"] : "";
					$format = (isset($v["format"]))? $v["format"] : "";
					$string .= '"'.$k.'":{"column":"'.$column.'","label":"'.$v["label"].'","style":"'.$style.'","format":"'.$format.'","display":'.$display.'},';	
				}

			}
			
		}
		
		$string = rtrim($string,',');
		$string .= '}}}';
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

		$fp = fopen($filesDirectory . $module.".json","wb");
		fwrite($fp,$string);
		fclose($fp);
	}
	
	public function addStyle($module, $style=null){
		$styles = $this->getStylesByModule($module);
		$style_name = "";
		$columns = "";
		$new_style = "";
		$i=0;
		$j=0;
		
		if(!empty($styles)){
			foreach($styles as $k=>$v){
				
				$j=0;
				$columns="";
				$is_default = ($style["is_default"])? 0: $v["is_default"];
				
				foreach($v["data"] as $kk=>$vv){
					$format = (isset($vv["format"]))? $vv["format"]: "";
					if($j===0){
						$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
					}else{
						$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
					}					
					$j++;
				}
				$columns .= ($columns!=="")? "}": "";	
				
				if($v["name"] === $style["name"]){
					$style_name = $style["name"] . " Copy";
				}
				
				if($i===0){
					$new_style='{"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$is_default.',"allow_to":"","data":'.$columns.'}';
				}else{
					$new_style.=',"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$is_default.',"allow_to":"","data":'.$columns.'}';
				}
				
				$j=0;
				$columns="";
				
				foreach($style["data"] as $kk=>$vv){
					$format = (isset($vv["format"]))? $vv["format"]: "";
					if($j===0){
						$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
					}else{
						$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
					}					
					$j++;
				}
				$columns .= ($columns!=="")? "}": "";
				
				$i++;
			}
			
			$style_name = ($style_name==="")? $style["name"]: $style_name;
			
			if($i===0){
				$new_style='{"0":{"name":"'. $style_name .'","is_default":'.$style["is_default"].',"allow_to":"","data":'.$columns.'}';
			}else{
				$new_style.=',"'.($i).'":{"name":"'. $style_name .'","is_default":'.$style["is_default"].',"allow_to":"","data":'.$columns.'}';
			}
			
			$new_style.= '}';
			


			$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

			$fp = fopen($filesDirectory . $module.".json","wb");
			fwrite($fp,$new_style);
			fclose($fp);
			
			
			//var_dump(json_decode($new_style,true));
			
		}
		//echo $new_style;
	}
	
	public function editStyle($module, $data){
		$styles = $this->getStylesByModule($module);
		$style_name = "";
		$columns = "";
		$new_style = "";
		$i=0;
		$j=0;
		
		if(!empty($styles)){
			foreach($styles as $k=>$v){
				
				$is_default = ($data["is_default"])? 0: $v["is_default"];
				
				if($v["name"] === $data["name_temp"]){
					$j=0;
					$columns="";
					foreach($v["data"] as $kk=>$vv){
						$format = (isset($vv["format"]))? $vv["format"]: "";
						if($j===0){
							$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}else{
							$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}					
						$j++;
					}
					$columns .= ($columns!=="")? "}": "";
					if($i===0){
						$new_style='{"'.$i.'":{"name":"'. $data["name"] .'","is_default":'.$data["is_default"].',"allow_to":"","data":'.$columns.'}';
					}else{
						$new_style.=',"'.$i.'":{"name":"'. $data["name"] .'","is_default":'.$data["is_default"].',"allow_to":"","data":'.$columns.'}';
					}		
					$i++;	
					
					
				}else{
					$j=0;
					$columns="";
					foreach($v["data"] as $kk=>$vv){
						$format = (isset($vv["format"]))? $vv["format"]: "";
						if($j===0){
							$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}else{
							$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}					
						$j++;
					}
					$columns .= ($columns!=="")? "}": "";
					if($i===0){
						$new_style='{"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$is_default.',"allow_to":"","data":'.$columns.'}';
					}else{
						$new_style.=',"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$is_default.',"allow_to":"","data":'.$columns.'}';
					}		
					$i++;		
				}
			}				
			$new_style.= '}';
				
			$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

			$fp = fopen($filesDirectory . $module.".json","wb");
			fwrite($fp,$new_style);
			fclose($fp);
		}
	}
	
	public function editColumns($module, $data){
		$styles = $this->getStylesByModule($module);
		$style_name = "";
		$columns = "";
		$new_style = "";
		$i=0;
		$j=0;
		if(!empty($styles)){
			foreach($styles as $k=>$v){
				
				if($v["name"] === $data["name"]){
					$j=0;
					$columns="";
					foreach($data["columns"] as $kk=>$vv){
						if($j===0){
							$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$vv["format"].'"}';
						}else{
							$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$vv["format"].'"}';
						}					
						$j++;
					}
					$columns .= ($columns!=="")? "}": "";
					if($i===0){
						$new_style='{"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}else{
						$new_style.=',"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}		
					$i++;	
					
					
				}else{
					$j=0;
					$columns="";
					$format = "";
					foreach($v["data"] as $kk=>$vv){
						$format = (isset($vv["format"])? $vv["format"]: "");
						if($j===0){
							$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}else{
							$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}					
						$j++;
					}
					$columns .= ($columns!=="")? "}": "";
					if($i===0){
						$new_style='{"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}else{
						$new_style.=',"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}		
					$i++;		
				}
			}				
			$new_style.= '}';

			$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

			$fp = fopen($filesDirectory . $module.".json","wb");
			fwrite($fp,$new_style);
			fclose($fp);

		}
	}
	
	public function deleteStyle($module, $style){
		$styles = $this->getStylesByModule($module);
		$style_name = "";
		$columns = "";
		$new_style = "";
		$i=0;
		$j=0;
		
		if(!empty($styles)){
			foreach($styles as $k=>$v){
				if($v["name"] !== $style){
					$j=0;
					$columns="";
					foreach($v["data"] as $kk=>$vv){
						$format = (isset($vv["format"]))? $vv["format"]: "";
						if($j===0){
							$columns='{"0":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}else{
							$columns.=',"'.$j.'":{"column":"'. $vv["column"] .'","label":"'.$vv["label"].'","display":'.$vv["display"].',"style":"'.$vv["style"].'","format":"'.$format.'"}';
						}					
						$j++;
					}
					$columns .= ($columns!=="")? "}": "";
					if($i===0){
						$new_style='{"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}else{
						$new_style.=',"'.$i.'":{"name":"'. $v["name"] .'","is_default":'.$v["is_default"].',"allow_to":"","data":'.$columns.'}';
					}		
					$i++;	
					
					
				}
			}				
			$new_style.= '}';
				
			$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

			$fp = fopen($filesDirectory . $module.".json","wb");
			fwrite($fp,$new_style);
			fclose($fp);
		}
	}
	
	public function deleteModule($module){
		$filesDirectory 	= realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;
		$filesDirectory		.= $module.".json";
		if(file_exists($filesDirectory)){
			unlink($filesDirectory);
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getStylesByModule($module=null){
		if(!is_null($module)){
			$style = array();
			$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$module.'.json';
			if(file_exists($filesDirectory)){
				$string = file_get_contents($filesDirectory);
				$style = json_decode($string, true);
			}
			return $style;
		}
	}
	
	public function getDefaultStyleName($module){
		$defaultName = "";
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$module.'.json';
		if(file_exists($filesDirectory)){
			$string = file_get_contents($filesDirectory);
			foreach(json_decode($string, true) as $k=>$v){
				if($v["is_default"] === 1){
					$defaultName = $v["name"];
				}
			}
		}
		return $defaultName;
	}
	
	public function getDefaultStyle($module, $data=null){
		$style = array();
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$module.'.json';
		if(file_exists($filesDirectory)){
			$string = file_get_contents($filesDirectory);
			foreach(json_decode($string, true) as $k=>$v){
				if($v["is_default"] === 1){
					$style = $v;
				}
			}
		}
		if(empty($style)){
			$this->create($module, $data);
			$string = file_get_contents($filesDirectory);
			foreach(json_decode($string, true) as $k=>$v){
				if($v["is_default"] === 1){
					$style = $v;
				}
			}
		}
		return $style;
	}
	
	public function getStyleByName($module, $style_name){
		$style = array();
		$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR.$module.'.json';
		if(file_exists($filesDirectory)){
			$string = file_get_contents($filesDirectory);
			foreach(json_decode($string, true) as $k=>$v){
				if($v["name"] === $style_name){
					$style = $v;
				}
			}
		}
		return $style;
	}
	
	public function getByName($name, $createIfNotExists=false){
		if(isset(  $this->readAll()[$name]  )){
			return  $this->readAll()[$name];
		}else{

			if(!empty($this->getColumnsName($name))){
				if($createIfNotExists){
					$filename = $name;
					$string = '{"0":{"is_default":1, "name":"Default", "allow_to":"0","data":{ ';

					foreach( $this->getColumnsName($name) as $k=>$v ){
						$string .= '"'.$k.'":{"column":"'.$v["Field"].'","label":"'.$v["Field"].'","style":"","format":"","display":"1"},';
					}

					$string = rtrim($string,',');

					$string .= '}}}';

					$filesDirectory = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."lists".DIRECTORY_SEPARATOR;

					$fp = fopen($filesDirectory . $name.".json","wb");
					fwrite($fp,$string);
					fclose($fp);

					return  $this->readAll()[$name];					
				}else{
					return array();
				}

				//return $string;
			}else{
				return array();
			}

			//array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			//return array();
		}
	}

}