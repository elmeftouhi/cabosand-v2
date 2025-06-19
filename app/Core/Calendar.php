<?php
require_once('Helpers/Modal.php');

class Calendar extends Modal{

	private $months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
	
	private $tableName = "Contrat";
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
	
	/*
	public function days_in_month($month, $year) { 
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
	} 
	*/
	public function days_in_month( $params ) { 
		return $params["month"] == 2 ? ($params["year"] % 4 ? 28 : ($params["year"] % 100 ? 29 : ($params["year"] % 400 ? 28 : 29))) : (($params["month"]-1) % 7 % 2 ? 30 : 31); 
	} 	
	
	
	/*****************************
			MY CALENDAR
	*****************************/
	

	
	public function Data_Of_By_Societe($params = []){
		
		$current_month = intval($params['month']);//date('m');
		$current_year = intval($params['year']); //date('Y');

		$days_in_selected_month = $this->days_in_month(['month'=>$current_month, 'year'=>$current_year]);

		$listOfAppartements = array();
		$listOfDates = array();

		$id_complexe = isset($params["id_complexe"])? $params["id_complexe"]:0;
		$request = "
					select client.first_name, client.last_name, client.societe_name as client , v_propriete.name as complexe_name, v_propriete.id_complexe,contrat.UID as UID,v_propriete.propriete_category
					from client
					join contrat on contrat.id_client = client.id
					JOIN propriete_location on propriete_location.UID = contrat.UID AND propriete_location.source='contrat'
					JOIN v_propriete on propriete_location.id_propriete = v_propriete.id
					where v_propriete.id_complexe=".$id_complexe." group by client.societe_name order by client.societe_name";
		//echo $request;
		$data_ = $this->execute($request);

		foreach($data_ as $kk=>$vv){

			$request = "select * from v_propriete_location_1 where ((year(date_debut)=".$current_year." and month(date_debut)=" . intval($current_month) .") OR (year(date_fin)=".$current_year." and month(date_fin)=" . intval($current_month) .")) AND id_complexe=".$id_complexe."  order by id_client,code, date_debut, date_fin";
			$data = $this->execute($request);

			$sub="";
			$date_debut = "";
			$date_fin = "";
			$code = "";
			$color = "";
			$listOfDates = array();
			//var_dump($data);
			foreach($data as $k=>$v){
				if( array_key_exists($v["code"]." (".$v["propriete_category"].") ".$sub, $listOfAppartements) ){
					array_push($listOfDates,[ $v["date_debut"]=>$v["date_fin"] ]);
					if($k === (count($data)-1)){
						$listOfAppartements[$v["code"]." (".$v["propriete_category"].") ".$sub] = $listOfDates;
						$code="";
					}
				}else{
					if($k === 0){
						array_push($listOfDates,[ $v["date_debut"]=>$v["date_fin"] ]);
						$sub = ";".$v["hex_string"]."-".$v["id_client"]."-".$v["id"];
						$code = $v["code"]." (".$v["propriete_category"].") ";
						$listOfAppartements[$code.$sub] = $listOfDates;
					}else{
						$listOfAppartements[$code.$sub] = $listOfDates;
						$listOfDates = array();

						array_push($listOfDates,[ $v["date_debut"]=>$v["date_fin"] ]);
						$sub = ";".$v["hex_string"]."-".$v["id_client"]."-".$v["id"];
						$code = $v["code"]." (".$v["propriete_category"].") ";

						$listOfAppartements[$v["code"]." (".$v["propriete_category"].") ".$sub] = $listOfDates;

					}			
				}
			}
		}
		//var_dump($listOfAppartements);
		$row = array();

		$listOfRows = array();
		foreach($listOfAppartements as $code=>$dates){

			for($i=1;$i<=$days_in_selected_month;$i++){
				$row[$i] = "empty;#ededed";
			}

			$day = "01";
			$_month = $current_month > 9? $current_month: "0". $current_month;
			$start =  new DateTime($current_year . "-" . $_month . "-" . $day);
			$last = new DateTime($current_year . "-" . $_month . "-" . $days_in_selected_month);

			foreach($dates as $k=>$v){

				foreach($v as $kk=>$vv){
					$date_debut = new DateTime($kk);
					$date_fin = new DateTime($vv);				
				}

				if($date_debut <= $start){
					$diff = $start->diff($date_fin)->days;
					$days = $date_debut->diff($last)->days;

					if($diff>$days_in_selected_month){
						for($i=1; $i<=$days_in_selected_month; $i++){
							$row[$i] = $code."|".$k;
						}
					}else{
						for($i=1; $i<=$diff;$i++){
							$row[$i] = $code."|".$k;
						}
					}

				}elseif($date_fin >= $last){
					//echo "fin >= endofday";
					$diff = $start->diff($date_debut)->days;
					$diff +=1;
					$days = $date_debut->diff($last)->days;
					for($i=$diff; $i<($diff+$days);$i++){
						$row[$i] = $code."|".$k;
					}

				}else{
					$diff = $date_debut->diff($start)->days; // calculat day past from the first day in month
					$days = $date_debut->diff($date_fin)->days; // number of days reserved
					$diff +=1;
					for($i=$diff; $i<($diff+$days);$i++){
						$row[$i] = $code."|".$k;
					}
				}
			}

			array_push($listOfRows,$row);
		}

		return $listOfRows;
		
	}
	
	public function Table($params = []){
		$month = isset($params['month'])? $params['month']: date('m');
		$year = isset($params['year'])? $params['year']: date('y');
		$id_complexe = $params['complexe'] != "-1"? $params['complexe']: 0;
		$id_client = $params['client'] != "-1"? $params['client']: 0;
		$id_propriete = $params['appartement'] != "-1"? $params['appartement']: 0;

		$nbr = 0;

		$request = "
						SELECT 
							propriete_location.id as id, 
							propriete_location.UID as UID, 
							propriete_location.id_propriete as id_propriete, 
							propriete_location.date_debut as date_debut, 
							propriete_location.date_fin as date_fin, 
							p.code as code, 
							colors.hex_string as color, 
							client.id as id_client, 
							p.id_complexe as id_complexe, 
							client.first_name, 
							client.last_name, 
							client.societe_name 
						FROM propriete_location 
						LEFT JOIN propriete p ON p.id=propriete_location.id_propriete
						LEFT JOIN contrat ON contrat.UID=propriete_location.UID
						LEFT JOIN client on client.id=contrat.id_client
						LEFT JOIN colors on colors.color_id=client.id_color
						WHERE 
								(	
									year(date_debut)=".$year." 
								AND 
									month(date_debut)=".intval($month)."
								) 
								OR 
								(
									year(date_fin)=".$year." 
								AND 
									month(date_fin)=".intval($month)."
								) 
						ORDER BY id_complexe, id_propriete, date_debut, date_fin
		";

		$propriete_locations = $this->execute($request);


		// Get total of days in the given Month and Yaer
		$days_in_month = $this->days_in_month(['year'=>$year, 'month'=>$month]);
		$totalOfContrats = count($propriete_locations);
		$day = "01";
		$month = $month > 9? $month: "0". $month;
		$start =  new DateTime($year . "-" . $month . "-" . $day);
		$last = new DateTime($year . "-" . $month . "-" . $days_in_month);

		
		$appartements = [];


		$table = '
		<div class="relative w-full">
			<div class="py-2 px-2 text-xl font-bold">
				Nombre d\'appartements : {{nbr}}
			</div>
			{{table_1}}

			{{table_2}}
			
		</div>
		<script>
			$(document).ready(function(){
				$(".calendar_show_detail").on("click", function(){
					var propriete_locations_id = $(this).data("propriete_locations_id")
					alert(propriete_locations_id)
				})
			});
		</script>
		';

		/** First start by drowing the header of the calendar */
		$table_1 = '
			<table class="w-full" style="table-layout: fixed;">
		';

		$tr = '<tr>';

		for($i=1; $i<=($days_in_month*2); $i++){
		
			$style = 'bg-blue-50 py-4 text-center text-gray-600 text-sm';

			if( $i % 2 == 0 )
				$tr .= '<td colspan="2" class="border-r border-gray-200 border-l '.$style.'">'.($i/2).'</td>';

		}

		$tr .= '</tr>';	

		$td_style = 'border-r border-gray-200 border-l';

		for($j=0; $j<$totalOfContrats; $j++){
			$continue = true;
			if($id_client){
				if( $propriete_locations[$j]['id_client'] != $id_client )
					$continue = false;
			}

			if($id_complexe){
				if( $propriete_locations[$j]['id_complexe'] != $id_complexe )
					$continue = false;
			}

			if($id_propriete){
				if( $propriete_locations[$j]['id_propriete'] != $id_propriete )
					$continue = false;
			}

			if($continue){
				if(!in_array( $propriete_locations[$j]['code'], $appartements))
					array_push($appartements, $propriete_locations[$j]['code']);

				$tr .= '<tr height="35px">';
				for($i=1; $i<=($days_in_month*2); $i++){
				
					if( ($i/2) % 2 == 0 )
						if( $i % 2 == 0 )
							$tr .= '<td colspan="2" class="'.$td_style.'"></td>';
						else
							$tr .= '<td colspan="2" class="bg-gray-50 '.$td_style.'"></td>';

				}

				$tr .= '</tr>';		
			}	
		}

		$emptyTr= '
			<tr>
				<td class="bg-white" colspan="'.($days_in_month*2).'"> 
					<div class="w-64 mx-auto my-8 text-xs text-red-800 font-light text-center flex flex-col items-center">
						<img class="h-24" src="https://us.v-cdn.net/6031209/uploads/W6CE78AAFKJ8/image.png">
						Aucune Période pour cette recherche
					</div>
				</td>
			</tr>
		';

		$tr .= !$totalOfContrats? $emptyTr: "";
		$tr .= '</table>';	
		$table_1 .= $tr;

		/** Drow the body of the calendar */
		$table_2 = '
			<table class="absolute top-24 mt-1 right-0 left-0 w-full" style="table-layout: fixed; bg-opacity-0">
		';
		$tr = '';

		for($row=0; $row < $totalOfContrats; $row++){
			$continue = true;
			if($id_client){
				if( $propriete_locations[$row]['id_client'] != $id_client )
					$continue = false;
			}

			if($id_complexe){
				if( $propriete_locations[$row]['id_complexe'] != $id_complexe )
					$continue = false;
			}

			if($id_propriete){
				if( $propriete_locations[$row]['id_propriete'] != $id_propriete )
					$continue = false;
			}

			if($continue){
				$tr .= '<tr height="35px">';
				for($i=1; $i<=$days_in_month; $i++){

					$date_debut = new DateTime($propriete_locations[$row]['date_debut']);
					$date_fin = new DateTime($propriete_locations[$row]['date_fin']);

					if($date_debut <= $start){
						$startDay = 1;

						if($date_fin>$last){
							$nbrOfDays = $days_in_month;
						}else{
							$nbrOfDays = $start->diff($date_fin)->days+1;
						}

					}elseif($date_debut > $start){
						if($date_fin <= $last){
							$startDay = $start->diff($date_debut)->days+1;
							$nbrOfDays = $date_fin->diff($date_debut)->days+1;
						}else{
							$startDay = $start->diff($date_debut)->days+1;
							$nbrOfDays = $date_debut->diff($last)->days+1;

						}


					}elseif($date_debut > $start AND $date_fin>$last){
						$startDay = $start->diff($date_debut)->days;
						$nbrOfDays = $date_fin->diff($date_debut)->days+1;
					}

					if($i==$startDay){
						//$color = isset($propriete_locations[$row]['color'])? 'background-color:#'.$propriete_locations[$row]['color']: "";
						$color = 'background-color:'.$propriete_locations[$row]['color'];
						$tr .= '
							<td colspan="'.$nbrOfDays.'" class="truncate overflow-hidden px-1 border-b">
								<div data-propriete_locations_id="'.$propriete_locations[$row]['id'].'" class="calendar_show_detail relative overflow-hidden rounded-lg w-full bg-blue-400 bg-opacity-60 text-xs text-white text-center border hover:shadow-lg hover:border-red-600 cursor-pointer" style="'.$color.'"> 
									'.$propriete_locations[$row]['code'].' <span style="font-size:8px">('.$propriete_locations[$row]['societe_name'].')</span>
									<span class="absolute top-0 left-0  text-white ml-1">'.$date_debut->format('d').'</span>
									<span class="absolute top-0 right-0  text-white mr-1">'.$date_fin->format('d').'</span>
								</div>
							</td>
						';
						$nbr++;
					}else{
						if($i<$startDay){
							$tr .= '<td class=" border-b"></td>';
						}
						if($i>($startDay+$nbrOfDays-1)){
							$tr .= '<td class=" border-b"></td>';
						}
					}
				}
				$tr .= '</tr>';				
			}
			
		}
		$tr .= '</table>';	
		$table_2 .= $tr;


		$table = str_replace(['{{table_1}}', '{{table_2}}', '{{nbr}}'], [$table_1, $table_2, count($appartements)], $table);
		return $table;
	}

	public function Table_Complexe($params=[]){
		$month = isset($params['month'])? $params['month']: date('m');
		$year = isset($params['year'])? $params['year']: date('y');
		$id_complexe = !isset($params['complexe'])? 0: ($params['complexe'] != "-1"? $params['complexe']: 0);


		$request = "
						SELECT p.id_complexe as id_complexe, complexe.name as name
						FROM propriete_location 
						LEFT JOIN propriete p ON p.id=propriete_location.id_propriete
						LEFT JOIN complexe ON complexe.id=p.id_complexe
						WHERE 
								(	
									year(date_debut)=".$year." 
								AND 
									month(date_debut)=".intval($month)."
								) 
								OR 
								(
									year(date_fin)=".$year." 
								AND 
									month(date_fin)=".intval($month)."
								) 
						GROUP BY p.id_complexe
		";

		$select = '
		<select class="rounded-lg px-2" id="complexe">
			<option value="-1">-- Complexe </option>
			{{options}}						
		</select>
		';

		$complexes = $this->execute($request);
		$option = '';
		foreach($complexes as $complexe){
			if($id_complexe)
				if($id_complexe == $complexe['id_complexe'])
					$option .= '<option selected value="'.$complexe['id_complexe'].'">'.$complexe['name'].'</option>';
				else
					$option .= '<option value="'.$complexe['id_complexe'].'">'.$complexe['name'].'</option>';
			else	
				$option .= '<option value="'.$complexe['id_complexe'].'">'.$complexe['name'].'</option>';
		}
		$select = str_replace("{{options}}", $option, $select);
		return $select;
	}

	public function Table_Client($params=[]){
		$month = isset($params['month'])? $params['month']: date('m');
		$year = isset($params['year'])? $params['year']: date('y');
		$id_complexe = !isset($params['complexe'])? 0: ($params['complexe'] != "-1"? $params['complexe']: 0);

		$request = "
						SELECT complexe.id as id_complexe, client.id as id_client, client.first_name as first_name, client.last_name as last_name, client.societe_name as societe_name
						FROM propriete_location 
						LEFT JOIN propriete p ON p.id=propriete_location.id_propriete
						LEFT JOIN complexe ON complexe.id=p.id_complexe
						LEFT JOIN contrat ON contrat.UID=propriete_location.UID
						LEFT JOIN client on client.id=contrat.id_client
						WHERE 
								(	
									year(date_debut)=".$year." 
								AND 
									month(date_debut)=".intval($month)."
								) 
								OR 
								(
									year(date_fin)=".$year." 
								AND 
									month(date_fin)=".intval($month)."
								) 
		";

		$select = '
		<select class="rounded-lg px-2" id="client">
			<option value="-1">-- Client </option>
			{{options}}						
		</select>
		';

		$complexes = $this->execute($request);
		$option = '';
		foreach($complexes as $complexe){
			if($id_complexe)
				if($id_complexe == $complexe['id_complexe'])
					$option .= '<option selected value="'.$complexe['id_client'].'">'.$complexe['societe_name'].'</option>';
				else
					$option .= '<option value="'.$complexe['id_client'].'">'.$complexe['societe_name'].'</option>';
			else	
				$option .= '<option value="'.$complexe['id_client'].'">'.$complexe['nsociete_nameame'].'</option>';
		}
		$select = str_replace("{{options}}", $option, $select);
		return $select;
	}

	public function Table_Month($params){
		$month = isset($params['month'])? $params['month']: date("m");		
		$year = isset($params['year'])? $params['year']: date("Y");	
		$months = [
			1	=>	'Janvier',
			2	=>	'Février',
			3	=>	'Mars',
			4	=>	'Avril',
			5	=>	'Mai',
			6	=>	'Juin',
			7	=>	'Juillet',
			8	=>	'Août',
			9	=>	'Septembre',
			10	=>	'Octobre',
			11	=>	'Novembre',
			12	=>	'Décembre'
		];
		
		$first_year = 2019;
		$this_year = date('Y');
		$years = [];
		for($year_=$first_year; $year_<=$this_year; $year_++){
			array_push($years, $year_);
		}

		$select_months = '<select class="calendar_month border-0 bg-transparent text-center">';
		foreach($months as $k=>$m){
			if($k == date('m'))
				$select_months .= '<option selected value="'.$k.'">'.$m.'</option>';
			else
				$select_months .= '<option value="'.$k.'">'.$m.'</option>';
		}
		$select_months .= '</select>';

		$select_years = '<select class="calendar_year border-0 bg-transparent text-center">';
		foreach($years as $y){
			if($y == date('Y'))
				$select_years .= '<option selected value="'.$y.'">'.$y.'</option>';
			else
				$select_years .= '<option value="'.$y.'">'.$y.'</option>';
		}
		$select_years .= '</select>';

		$month_year = '
			<div class="text-gray-800 p-2">
				<div class="flex justify-center items-center py-1">
					<div class="flex calendar_year_month items-center border border-gray-300 rounded overflow-hidden">
						<div class="calendar_year_month_prev py-3 px-4 bg-gray-100 hover:bg-gray-300 hover:text-gray-700 cursor-pointer">
							<i class="fa fa-chevron-left"></i>
						</div>
						'.$select_months.'
						'.$select_years.'
						<div class="calendar_year_month_next py-3 px-4 bg-gray-100 hover:bg-gray-300 hover:text-gray-700 cursor-pointer">
							<i class="fa fa-chevron-right"></i>
						</div>
					</div>
				</div>
			</div>
		';

		$template = '
			<div class="calendar rounded shadow mx-1">
				<div class="calendar_header flex justify-between bg-green-100 py-2 px-2 text-green-600">
					<div class="">
						<i class="far fa-calendar-alt"></i> Calendar
					</div>
					<div class="flex items-center justify-between">
						<div class="flex items-center gap-2">
							<div data-target="calendar_body" class="collapse py-1 px-2 text-green-500 rounded cursor-pointer hover:bg-green-400">
								<i class="fas fa-grip-lines"></i>
							</div>
							<div class="calendar_body_refresh py-1 px-2 text-green-500 rounded cursor-pointer hover:bg-green-400">
								<i class="fas fa-arrows-rotate"></i>
							</div>
						</div>
					</div>
				</div>
				'.$month_year.'
				<div class="calendar_body py-2 px-2">
					'.$this->By_Month(['month'=>$month, 'year'=>$year]).'
				</div>
			</div>
		';
		return $template;
	}

	public function By_Month($params = []){
		
		$month = intval(isset($params['month'])? $params['month']: date('m'));
		$year = intval(isset($params['year'])? $params['year']: date('Y'));

		$request = "
			SELECT * 
			FROM v_contrat_periode 
			WHERE 
				(year(date_debut)=".$year." AND month(date_debut)=" . intval($month) .") 
			OR 	(year(date_fin)=".$year." AND month(date_fin)=" . intval($month) .") 
			ORDER BY date_debut, date_fin";

		$_data = $this->execute($request);
		$data = array();
		foreach($_data as $k=>$v){
			array_push($data, array(
				"societe_name"	=>	$v["societe_name"],
				"date_debut"	=>	$v["date_debut"],
				"date_fin"		=>	$v["date_fin"],
				"color"			=>	$v["color"]
			));
		}
		
		
		$calendar = '<table class="w-full bg-gray-100" style="table-layout: fixed;">';

		$headings = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
		$calendar.= '<tr class="bg-green-300"><td class="text-center py-3 text-green-600 border-2 border-green-200">'.implode('</td><td class="text-center py-3 text-green-600 border-2 border-green-200">',$headings).'</td></tr>';

		$running_day = date('w',mktime(0,0,0,$month,1,$year)); 		// order of first day in the week
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year)); 	// number of days in given month
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		$calendar.= '<tr class="">';

		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np bg-white" style="background:white !important"> </td>';
			$days_in_this_week++;
		endfor;

		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day min-h-16 h-24 overflow-hidden align-top hover:bg-blue-100 border-2 border-green-200 bg-transparent" style="padding-top:24px !important">';

			$day = ($list_day<10)? "0" . $list_day: $list_day;
			$_month = ($month>9)? $month: "0".$month;
			$date = $year . "-" . $_month . "-" . $day;
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
			$i	= 	0;
			$j	=	0;
			$complexes = array();
			$hided = "";
		
			foreach($data as $k=>$v){
				if( $v["date_debut"] === $date || $v["date_fin"] === $date){
					if(!in_array($v["societe_name"], $complexes)){
						array_push($complexes,$v["societe_name"]);
						if($i<6){
							$calendar.= "<div class='py-1 px-2 rounded-lg border-green-50 text-white my-1' style='padding:2px 3px; font-size:10px;background-color:" .  $v["color"] . "'>" .  $v["societe_name"] . "</div>";
						}else{
							$j++;
							$hided .= "<div class='py-1 px-2 rounded-lg border-green-50 text-white my-1' style='padding:2px 3px; font-size:10px;background-color:" .  $v["color"] . "'>" .  $v["societe_name"] . "</div>";
						}
						$i++;							
					}
				}							
			}

			if($j>0){
				$calendar.= "<div class='label label-default calendar_dev' style='padding:2px; font-size:10px;'>" .  $j . " Autres </div>";
				$calendar.= "<div class='hide to_show'>" .  $hided . "</div>";
			}

			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;
		
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np" style="background:white !important"> </td>';
			endfor;
		endif;
		
		$calendar.= '</tr>';		
		$calendar.= '</table>';	
		
		return $calendar;
		
		
	}	

	public function Table_Month_Body($params){
		$month = isset($params['month'])? $params['month']: date("m");		
		$year = isset($params['year'])? $params['year']: date("Y");		

		return  $this->By_Month(['month'=>$month, 'year'=>$year]);
	}

	/** Dépense Catégories */
	public function Propriete_Location_Details($params = []){
		$request = "
			SELECT *, 
			(
				SELECT
					SUM(depense.montant)
				FROM
					depense
				WHERE
					depense.id_category = depense_category.id
			) AS total
			FROM depense_category
			ORDER BY depense_category
		";
		$categories = $this->execute($request);
		$push['categories'] = $categories;
		$push['Obj'] = new $this;

		$view = new View("calendar.location.index");
		return $view->render($push);
	}

}

$calendar = new Calendar;