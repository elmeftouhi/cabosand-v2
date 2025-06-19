<?php
require_once('Helpers/Modal.php');
require_once('Helpers/Config.php');
require_once('Helpers/View.php');

class Person extends Modal{

	
	private $columns = array(
		array("column" => "id", "label"=>"#ID", "width"=>40),
		array("column" => "first_name", "label"=>"PRENOM"),
		array("column" => "last_name", "label"=>"NOM"),
		array("column" => "person_profile", "label"=>"PROFILE"),
		array("column" => "telephone", "label"=>"TELEPHONE"),
		array("column" => "status", "label"=>"STATUS", "width"=>80)
	);
	private $tableName = __CLASS__;
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
			foreach( $this->find('', [], '') as $k=>$v){
				if( count( $this->find('', ['conditions'=>['id_person='=>$v['id']]], 'person_permissions')) === 0 ){
					
					$json = '{';
					foreach($this->find('', [], 'modules') as $kk=>$vv){
						$json .= '"'.$vv["module_name"].'":{';
						foreach($this->find('', ['conditions'=>['id_module='=>$vv['id']]], 'module_actions') as $kkk=>$vvv){
							$json .= '"'.$vvv["module_action"].'":1,';
						}
						$json = rtrim($json, ',');
						$json .= '},';
					}
					$json = rtrim($json, ',');
					$json .= '}';
					
					$this->save(['id_person'=>$v['id'], 'permissions'=>$json], 'person_permissions');
					
				}				
			}

			//var_dump(json_decode($json, true));
		}catch(Exception $e){
			$this->err->save("person -> Constructeur",$e->getMessage());
		}
	}	
		
	public function getColumns($style = null){
		
		$style = (is_null($style))? strtolower($this->tableName): $style;
		
		$columns = array();
		$l = new ListView();
		foreach($l->getDefaultStyle($style, $columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
	
	public function checkLogin($login_password = null){

		$data = $this->find(null,array("conditions AND"=>array("login=" => $login_password[0], "password="=>$login_password[1])),"v_person");
		if (count($data)>0){
			return $data;			
		}else{
			return null;
		}
		
		
	}
	
	public function GetProfile(){
		
		// sleep(2);
		$template = '
				<div id="popup">	

					<div class="popup-header d-flex space-between">
						<div class="">Mon Profile</div>
						<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
					</div>

					<div class="popup-content">
						<div id="profile" class="">
							<div class="image-container">
								<div class="image">
									<img src="{{img}}">
									<div class="image-actions">
										<button class="image-edit upload_btn" data-target="upload"><i class="fas fa-camera-retro"></i></button>
										<button class="image-reload" data-folder="person" data-uid="{{UID}}"><i class="fas fa-sync-alt"></i></button>										
									</div>

									<input class="hide" type="file" id="upload" data-uid="{{UID}}" data-folder="person" data-is_unique="1">
								</div>
								<div class="progress">
									<div style="width:0%" class="progress-bar progress-value">0%</div>
								</div>
							</div>

							<div class="profile-content">
								<div class="form-element">
									<label for="user_first_name">Prénom</label>
									<input type="text" id="user_first_name" value="{{first_name}}">
									<input type="hidden" id="id" value="{{id}}">
								</div>
								<div class="form-element">
									<label for="user_last_name">Nom</label>
									<input type="text" id="user_last_name" value="{{last_name}}">
								</div>
								<div class="form-element">
									<label for="user_profile">Profile</label>
									<input type="text" id="user_profile" value="{{user_profile}}" disabled style="background-color:rgba(0,0,0,0.1)">
								</div>
								<div class="form-element">
									<label for="user_telephone">Téléphone</label>
									<input type="text" id="user_telephone" value="{{telephone}}">
								</div>
								<div class="form-element">
									<label for="user_email">E-Mail</label>
									<input type="email" id="user_email" value="{{email}}">
								</div>

								<hr>

								<div class="form-element">
									<label for="user_login">Login</label>
									<input class="" type="email" id="user_login" value="{{user_login}}">
								</div>
								<div class="form-element">
									<label for="user_password">Password</label>
									<input type="password" id="user_password" value="{{user_password}}"  disabled style="background-color:rgba(0,0,0,0.1)">
									<button class="edit-password-profile"><i class="far fa-keyboard"></i></button>
								</div>
							</div>
						</div>
					</div>

					<div class="popup-actions">
						<ul>
							<li><button class="store-profile green">Enregistrer</button></li>
							<li><button class="abort">Quitter</button></li>
						</ul>
					</div>
				</div>
		';
		
		if(isset($_SESSION['HOST'])){
			
			$host = $_SESSION['HOST'];
			 //'http://'.$host.'templates/default/images/user-default-image.png';

			$config = new Config;
			$env = $config->get()["GENERAL"]["ENVIRENMENT"];
			
			if(isset($_SESSION[$env]["USER"])){
				$id = $_SESSION[$env]["USER"]["id"];
				$user = $this->find( '', ['conditions'=>['id='=>$id] ], 'v_person' )[0];
				$img = $this->GetPictures(['folder'=>'person', 'UID'=>$user["UID"]])[0];
				$replace = ["{{img}}", "{{UID}}", "{{first_name}}", "{{last_name}}", "{{user_profile}}", "{{telephone}}", "{{email}}", "{{user_login}}", "{{user_password}}", "{{id}}"];
				$by_this = [ $img, $user["UID"], $user["first_name"], $user["last_name"], $user["person_profile"], $user["telephone"], $user["email"], $user["login"], $user["password"], $user["id"] ];

				return str_replace($replace, $by_this, $template);					
			}else{
				
				return -1;
			}
		}else{
			return -1;
		}
	}
	
	public function StoreProfile($params){
		
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];
		
		$updated_by = $_SESSION[$env]["USER"]["id"];
		$updated = date("Y-m-d H:i:s");
		
		$first_name = addslashes( $params['first_name'] );
		$last_name = addslashes( $params['last_name'] );
		$telephone = addslashes( $params['telephone'] );
		$email = addslashes( $params['email'] );
		
		$user_login = addslashes( $params['user_login'] );
				
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL) && filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
			$data = [
				'updated'		=>	$updated,
				'updated_by'	=>	$updated_by,
				'first_name'	=>	$first_name,
				'last_name'		=>	$last_name,
				'telephone'		=>	$telephone,
				'email'			=>	$email
			];
			if(isset($params["status"])) $data["status"] = $params['status'];
			if(isset($params["id_profil"])) $data["id_profil"] = addslashes( $params['id_profil'] );
			
			if(isset($params['id'])){
				$data["id"] = $params['id'];
			}else{
				$data["UID"] = $params['UID'];
				$data["created"]	=	$updated;
				$data["created_by"]	=	$updated_by;
			}
			
			$this->save($data);
			
			if(isset($data["id"])){
				$user = $this->find('', [ 'conditions'=>['id_person='=>$data["id"] ] ], 'person_login')[0];				
				if($user_login !== addslashes($user["login"]) || md5($params['user_password']) !== $user["password_"] ){
					$user_password = md5( $params['user_password'] );
					$data = [
						'id'		=>	$user["id"],
						'login'		=>	$user_login,
						'password_'	=>	$user_password

					];
					$this->save($data, 'person_login');
					if($updated_by === $user['id_person']){
						unset( $_SESSION[$env]["USER"] );
						return 2;					
					}else{
						return 1;
					}
				}else{
					return 1;
				}				
			}else{
				$user_password = md5( $params['user_password'] );
				$data = [
					'id_person'		=>	$this->getLastID(),
					'login'			=>	$user_login,
					'password_'		=>	$user_password
				];
				$this->save($data, 'person_login');
				return 1;
			}
		}else{
			return 0;
		}
		

		
	}
	
	public function GetPictures($params){
		
		$statics = $_SESSION["STATICS"];
		
		$folder = $_SESSION["UPLOAD_FOLDER"].$params["folder"].DIRECTORY_SEPARATOR.$params["UID"].DIRECTORY_SEPARATOR;
		
		$dS = DIRECTORY_SEPARATOR;
		
		
		$default_src = $statics."/public/images/user-default-image.png";
		$array_src = [];
						
		
		if(file_exists($folder)){

			
			foreach(scandir($folder) as $k=>$v){
				if($v <> "." and $v <> ".." and strpos($v, '.') !== false){
					
					array_push( $array_src, $statics.$params["folder"]."/".$params["UID"]."/".$v );
				}
			}	
		}
		
		if (count($array_src) === 0)
			array_push( $array_src, $default_src );
		
		return $array_src;
	}
	
	public function GetDefaultPicture($params){		
		return $this->GetPictures($params)[0]."?".time();
	}
	
	public function FindBy($params){
		
		$code = addslashes( strtolower($params['request']) );
		$data = $this->find('', ['conditions'=>['lower(first_name)='=>$code] ], '');
		return count( $data ) === 1? $data[0]: 0;
		
	}
	
	public function Table($params = []){
		
		$remove_sort = array("actions", "activity");
		$column_style = (isset($params['column_style']))? $params['column_style']: strtolower($this->tableName);
		
		$filters = (isset($params["filters"]))? $params["filters"]: [];
		
		$l = new ListView();
		$defaultStyleName = $l->getDefaultStyleName($column_style);
		$columns = $this->getColumns($column_style);
		
		
		$table = '
			<div class="table-container">
				<div class="d-flex space-between" style="padding:0 10px 10px 10px">
					<div style="font-size:16px; font-weight:bold">{{counter}}</div>
					<div class="text-green" style="font-size:16px; font-weight:bold">{{total}}</div>
				</div>
				<table>	
					<thead>	
						<tr>
							{{ths}}
						</tr>
						
					</thead>
					<tbody>
						{{trs}}
					</tbody>
				</table>
			</div>
		
		';
		
		/***********
			Columns
		***********/
		$ths = '';
		$trs_counter = 1;
		
		foreach($columns as $column){
			$is_sort = ( in_array($column["column"], $remove_sort) )? "" : "sort_by";
			$style = ""; 
			$is_display = ( isset($column["display"]) )? ($column["display"])? "" : "hide" : "";
			
			if($column['column'] === "actions"){
				$ths .= "<th class='". $is_display . "'>";
				$ths .= "	<button data-default='".$defaultStyleName."' value='".$column_style."' class='show_list_options'>";
				$ths .= "		<i class='fas fa-ellipsis-h'></i></button>";
				$ths .= "	</button>";
				$ths .=	"</th>";
			}else{
				$trs_counter += $is_display === "hide"? 0:1;
				$ths .= "<th class='".$is_sort." ". $is_display . "' data-sort='" . $column['column'] . "' data-sort_type='desc'>";
				$ths .=  "	<div class='d-flex'>";
				$ths .=  		$column['label'];
				$ths .= "		<i class='pl-5 fas fa-sort'></i> ";
				$ths .=  "	</div>";
				$ths .=	"</th>";
			}

		}
		
		/***********
			Conditions
		***********/
		
		$request = [];
		$sql = '';
		if(isset($params['request'])){
			if( $params['request'] !== "" ){
				if( isset($params['tags']) ){
					if( count( $params['tags'] ) > 0 ){
						foreach( $params['tags'] as $k=>$v ){
							$request[ 'LOWER(CONVERT(' . $v. ' USING latin1)) like '] = '%' . strtolower( $params['request'] ) . '%';
							
							$item = 'LOWER(CONVERT(' . $v. ' USING latin1)) like %' . strtolower( $params['request'] ) . '%';
							$sql .= $sql===''? $item.'<br>': ' AND '.$item.'<br>';
							
						}
					}
				}
			}
		}
		
		if( count($filters) > 0 ){
			foreach($filters as $k=>$v){
				if($v["value"] !== "-1"){

					
					if( $v["id"] === "Profile" ){
						$request['id_profil = '] = $v["value"];
						$item = 'id_profil = ' . $v["value"];						
					}
					if( $v["id"] === "Person_Status" ){
						$request['status = '] = $v["value"];
						$item = 'status = ' . $v["value"];						
					}
					$sql .= $sql===''? $item.'<br>': ' AND '.$item.'<br>';					
				}
				
			}

		}
		
		/***********
			Body
		***********/
		$use = (isset($params['use']))? strtolower($params['use']): strtolower($this->tableName);

		
		$conditions = [];
		
		if( count($request) === 1 ){
			$conditions['conditions'] = $request;
		}elseif( count($request) > 1 ){
			$conditions['conditions AND'] = $request;
		}
		
		if(isset($params['sort'])){
			$conditions['order'] = $params['sort'];
		}else{
			$conditions['order'] = 'first_name desc';
		}
		
		$pp = isset( $params['pp'] ) ? $params['pp']: 20;
		$current = isset( $params['current'] ) ? $params['current']: 0;
		
		
		// Counter
		$counter = count($this->find('', $conditions, $use));
		
		$conditions['limit'] = [$current,$pp];
		
		$data = $this->find('', $conditions, $use);
		$trs = '';

		
		foreach($data as $k=>$v){
						
			$background = isset($v["all_ligne"])? $v["all_ligne"]? $v["hex_string"]: "": "";
			$trs .= '<tr style="background-color:'.$background.'" data-page="'.$use.'">';
			foreach($columns as $key=>$value){
				
				$style = (!$columns[$key]["display"])? "display:none": $columns[$key]["style"] ;
								
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "last_name"){
						if($v["status"] === "1"){
							$icon = "<span style='font-size:20px; padding-right:10px; color:#96ceb4'><i class='fas fa-user'></i></span>";
						}else{
							$icon = "<span style='font-size:20px; padding-right:10px; color:#ea7070'><i class='fas fa-user'></i></span>";
						}
						$trs .= "<td class='".$is_display."' style='".$style."'> " . $icon . $v["first_name"] . " " . $v["last_name"] . "</td>";	
					
					}else{
						if(isset($columns[$key]["format"])){
							if($columns[$key]["format"] === "money"){
								$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
							}else if($columns[$key]["format"] === "on_off"){
								if($v[ $columns[$key]["column"] ] === "0")
									$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
								else
									$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-green'>Activé</div></td>";
							}else if($columns[$key]["format"] === "color"){
								$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
							}else if($columns[$key]["format"] === "date"){
								$date = explode(" ", $v[ $columns[$key]["column"] ]);
								if(count($date)>1){
									$_date = "<div style='min-width:105px'><i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
								}else{
									$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
								}
								$trs .= "<td class='".$is_display."' style='".$style.";'>".$_date."</td>";

							}else{
								$trs .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]. "</td>";
							}
						}else{
							$trs .= "<td class='".$is_display."' style='".$style."'>".$v[ $columns[$key]["column"] ]."</td>";
						}						
					}
											
				}else{
					if($columns[$key]["column"] == "actions"){
						
						$trs .=   "<td style='width:155px; text-align: center'>
										<button class='show_droits' value='".$v["id"]."'><i class='fas fa-skull-crossbones'></i></button>
										<button class='show_log' value='".$v["id"]."'><i class='fas fa-clipboard-list'></i></button>
										<button data-controler='". $this->tableName ."' class='update' value='".$v["id"]."'><i class='fas fa-ellipsis-v'></i></button>
								</td>";	
					
					}elseif($columns[$key]["column"] == "activity"){
						$activities = $this->find('', ['conditions'=>['created_by='=>$v["id"]], 'order'=>'created DESC'], 'person_activity');
			
						if(count($activities)>0){
							//$date = explode(" ", $activities[0]['created']);
							$_date = $this->timeElapsed($activities[0]['created']);
							/*
							if(count($date)>1){
								$_date = "<div style='min-width:105px'><i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
							}else{
								$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
							}	
							*/
						}else{
							$_date = 'Aucune';
						}

						$trs .= "<td class='".$is_display."' style='".$style."'>" . $_date . "</td>";
					}else{
						
						if($columns[$key]["format"] === "money"){
							$trs .= "<td class='".$is_display."' style='".$style."'>" . $this->format($v[ $columns[$key]["column"] ]) . "</td>";
						}elseif($columns[$key]["column"] == "nbr_nuite"){
							$trs .= "<td style='".$style."'><button class='show_propriete_proprietaire' data-id='".$v['id']."'>" . $nbr_nuite . "</button></td>";
						}else if($columns[$key]["format"] === "on_off"){
							$trs .= "<td class='".$is_display."' style='".$style."'><div class='label label-red'>Désactive</div></td>";
						}else if($columns[$key]["format"] === "color"){
							$trs .= "<td class='".$is_display."' style='".$style."'> <span style='padding:10px 15px; background-color:".$v[ $columns[$key]["column"] ]."'>".$v[ $columns[$key]["column"] ] . "</span></td>";
						}else if($columns[$key]["format"] === "date"){
							$date = explode(" ", $v[ $columns[$key]["column"] ]);
							if(count($date)>1){
								$_date = "<div style='min-width:105px'><i class='fas fa-calendar-alt'></i> ".$date[0]."</div><div style='min-width:105px'><i class='far fa-clock'></i> ".$date[1]."</div>";
							}else{
								$_date = "<div><i class='fas fa-calendar-alt'></i> ".$date[0]."</div>";
							}
							
							$trs .= "<td class='".$is_display."' style='".$style.";'>".$_date."</td>";
						}else{
							$trs .= "<td class='".$is_display."' style='".$style."'>" . "NaN" . "</td>";
						}						
					}

				}


			}
			$trs .= '</tr>';
			
		}
		
		if(count($data) === 0)
			$trs = '<tr><td colspan="'.$trs_counter.'">No Data to Display!</td></tr>';
		
		$counter = $counter . " Operations";
		return str_replace(["{{ths}}", "{{trs}}", "{{sql}}", "{{counter}}"], [$ths, $trs, $sql, $counter], $table);
		
	}
	
	function timeElapsed($date){
        $months=array();
        for ($i=1; $i < 13; $i++) { 
            $month = date('F',mktime(0,0,0,$i));
            $months += [substr($month,0,3) => $i];
        }
        $date_year = date('Y', strtotime($date));//year of the date
        $date_month = date('m', strtotime($date));//month of the date
        $date_day = date('d', strtotime($date));//day of the date
        $date_hour = date('h', strtotime($date));//hour of the date
        $date_minute = date('i', strtotime($date));//minute of the date
        $current_year = date('Y');//current year(2019 in this case)

        //seconds passed between the given and current date
        $seconds_passed = round((time()-strtotime($date)),0);

        //minutes  passed between the given and current date
        $minutes_passed = round((time()-strtotime($date))/ 60,0);

        //hours passed between the given and current date
        $hours_passed = round((time()-strtotime($date))/ 3600,0);

        //days passed between the given and current date
        $days_passed = round((time()-strtotime($date))/ 86400,0);

        if($seconds_passed<60) return $seconds_passed." second".($seconds_passed == (1) ? " " : "s")." ago";
        //outputs 1 second / 2-59 seconds ago

        else if($seconds_passed>=60 && $minutes_passed<60) return $minutes_passed." minute".($minutes_passed == (1) ? " " : "s")." ago";
        //outputs 1 minute/ 2-59 minutes ago

        else if($minutes_passed>=60 && $hours_passed<24) return $hours_passed." hour".($hours_passed == (1) ? " " : "s")." ago";
        //outputs 1 hour / 2-23 hours ago

        else if($hours_passed>=24 && $days_passed<2) return "Yesterday at ".$date_hour.":".$date_minute;
        //outputs [Yesterday at 11:30] for example

        else{
            if($current_year!=$date_year){
                foreach($months as $month_name => $month_number){
                    
					if($month_number==$date_month){
                        return $month_name." ".$date_day.", ".$date_year." ".$date_hour.":".$date_minute;
                        //echo $date_hour < (12) ? "AM" : "PM " ;
                        //outputs [Dec 11, 2018 11:32] for example
                    }
                }
            }
            else{
                
				foreach($months as $month_name => $month_number){
                    if($month_number==$date_month){
                        return $month_name." ".$date_day.", ".$date_hour.":".$date_minute;
                        //echo $date_hour < (12) ? "AM" : "PM " ;
                        //outputs [Dec 11, 11:32] for example
                    }
                }
            }
        }
    }
	
	public function Create($params = []){
		$push = [];
		$push['Obj']	=	new Person;
		$push['profiles'] = $this->find('', [ 'order' => 'person_profile DESC' ], 'person_profile');
		$view = new View("person.create");
		
		return $view->render($push);
	}
	
	public function Update($params){
		
		$push = [];
		$push['Obj']	=	new Person;
		$push['profiles'] = $this->find('', [ 'order' => 'person_profile DESC' ], 'person_profile');
		
		$person = $this->find('', [ 'conditions'=>[ 'id='=>$params['id'] ] ], 'v_person');		
		if( count($person) > 0 ){
			$push['person'] = $person[0];
		}
		
		
		$view = new View("person.create");
		return $view->render($push);
	}
	
	public function Log($params = []){
		
		
		$logs = $this->find('', ['conditions'=>['created_by=' => $params['id_user'] ], 'order'=>'created DESC'], 'v_person_activity');

		$template = '
		<div id="popup" class="pb-5" style="width:520px; margin:50px auto; height:450px">
			<div class="popup-header d-flex space-between">
				<div class="">Utilisateur Logs</div>
				<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
			</div>
			<div class="ppl_wrapper" style="overflow:auto; height:400px">
				<div class="popup-content ppl" style="padding-bottom:0px; max-height:100%">
					<div class="header d-flex space-between mb-10">
						<div class="title" style="font-weight:bold; padding-top:7px">Utilisateur Logs</div>
					</div>
					<div class="body">
						<table>
							<thead>
								<tr>
									<th>DATE</th>
									
									<th>LOG</th>
								</tr>
							</thead>

							<tbody>
								{{trs}}
							</tbody>
						</table>
					</div>
				</div>			
			</div>

			
		</div>
		';
		
		$trs = '';
		
		
		foreach($logs as $k=>$v){
			$trs .= '
							<tr>
								<td>'.$v["created"].'</td>
								<td>'.$v["activity_message"].'</td>
							</tr>
			';
		}
				
		
		return str_replace(["{{trs}}"], [$trs], $template);
	}
	
	public function Get_Droits($params = []){
		$id_person = isset($params['id_user'])? $params['id_user']: 0;
		$push['modules'] = $this->find('', ['order'=>'module_name'], 'modules');
		$push['actions'] = $this->find('', ['order'=>'module_action'], 'module_actions');
		$push['Obj'] = new Person;
		$push['id_user'] = $id_person;		
		$view = new View("person.droits");
		return $view->render($push);
	}
	
	public function Is_Permission_Granted($params){
		
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];
		$id_user = $_SESSION[$env]["USER"]["id"];
		
		$id_person = isset($params['id_user'])? $params['id_user']: $id_user;
		$permissions = $this->find('', ['conditions'=>['id_person='=>$id_person]], 'person_permissions');
		if(count($permissions) > 0){
			$allPermissions = json_decode($permissions[0]['permissions'], true);
			if(isset($params['key'])){
				if(array_key_exists($params['key'], $allPermissions)){
					if(!isset($params['value'])){
						return 1;
					}else{
						if(array_key_exists($params['value'], $allPermissions[$params['key']])){
							return $allPermissions[$params['key']][$params['value']]===1? 1:0;
						}else{
							return 0;
						}						
					}

				}else{
					return 0;
				}
			}else{
				return 0;
			}
			
		}else{
			return 0;
		}
	}
	
	public function SavePermission($params){
		$id_user = isset($params['id_user'])? $params['id_user']: 0;
		
		if(count($this->find('', ['conditions'=>['id='=>$id_user]], 'person')) > 0){
			$mypermissions = $this->find('', ['conditions'=>['id_person='=>$id_user]], 'person_permissions');
			if(count($mypermissions) > 0){
				$data = [
					'id'			=>	$mypermissions[0]['id'],
					'id_person'		=>	$params['id_user'],
					'permissions'	=>	$params['permissions']
				];
				$this->save($data, 'person_permissions');
				return 1;				
			}else{
				$data = [
					'id_person'		=>	$params['id_user'],
					'permissions'	=>	$params['permissions']
				];
				$this->save($data, 'person_permissions');
				return 1;				
			}

		}else{
			return  0;
		}
		
	}
	
}
$person = new Person;

