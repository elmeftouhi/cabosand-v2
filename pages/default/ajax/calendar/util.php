<?php session_start();

$response  = array("code"=>0, "msg"=>"Error");

if(!isset($_SESSION['CORE'])){die(json_encode($response));}
if(!isset($_POST['style'])){$response["msg"]="Error Data"; die(json_encode($response));}
/*
var_dump($_POST);
die();
*/
$core = $_SESSION['CORE'];
require_once($core."Calendar.php");
require_once($core."Contrat.php");
$options = array(	"counter"		=>	(isset($_POST["counter"]))? $_POST["counter"]:0,
				 	"style"			=>	(isset($_POST["style"]))? $_POST["style"]:"month",
				 	"id_complexe"	=>	(isset($_POST["id_complexe"]))? $_POST["id_complexe"]:0,
				 	"UID"			=>	(isset($_POST["UID"]))? $_POST["UID"]:0,
				);




if($options["style"] === "month_"){
	
	$counter = (isset($_POST["counter"]))? $_POST["counter"]:0;
	$current_month = date('m');
	$current_year = date('Y');
	$request = "select * from v_contrat_periode where (year(date_debut)=".$current_year." and month(date_debut)=" . (intval($current_month)+$counter) .") OR (year(date_fin)=".$current_year." and month(date_fin)=" . (intval($current_month)+$counter) .") order by date_debut, date_fin";

	$_data = $contrat->execute($request);
	$data = array();
	foreach($_data as $k=>$v){
		array_push($data, array(
			"societe_name"	=>	$v["societe_name"],
			"date_debut"	=>	$v["date_debut"],
			"date_fin"		=>	$v["date_fin"],
			"color"			=>	$v["color"],
		));
	}
	$args = array("options"=>$options,"data"=>$data);

}else{
	$current_month = date('m');
	$current_year = date('Y');
	$counter = $options["counter"];
	
	$days_in_selected_month = $calendar->days_in_month(($current_month + $counter), $current_year);
	$listOfAppartements = array();
	$listOfDates = array();
	
	$id_complexe = $options["id_complexe"];
	$request = "
select client.first_name, client.last_name, client.societe_name as client , v_propriete.name as complexe_name, v_propriete.id_complexe,contrat.UID as UID,v_propriete.propriete_category
from client
join contrat on contrat.id_client = client.id
JOIN propriete_location on propriete_location.UID = contrat.UID AND propriete_location.source='contrat'
JOIN v_propriete on propriete_location.id_propriete = v_propriete.id
where v_propriete.id_complexe=".$id_complexe." group by client.societe_name order by client.societe_name";
		//echo $request;
	$data_ = $contrat->execute($request);
	//var_dump($data_);
	foreach($data_ as $k=>$v){

		$request = "select * from v_propriete_location_1 where ((year(date_debut)=".$current_year." and month(date_debut)=" . (intval($current_month)+$counter) .") OR (year(date_fin)=".$current_year." and month(date_fin)=" . (intval($current_month)+$counter) .")) AND id_complexe=".$id_complexe." AND UID='".$v["UID"]."' order by code, date_debut, date_fin";		


		$data = $contrat->execute($request);
		//var_dump($data);
		

		
		$sub="";
		$date_debut = "";
		$date_fin = "";
		$code = "";
		$color = "";
		$listOfDates = array();
		foreach($data as $k=>$v){
			if( array_key_exists($v["code"]." (".$v["propriete_category"].") ".$sub, $listOfAppartements) ){

				array_push($listOfDates,array($v["date_debut"]=>$v["date_fin"]));
				if($k === (count($data)-1)){
					$listOfAppartements[$v["code"]." (".$v["propriete_category"].") ".$sub] = $listOfDates;
					$code="";
				}
			}else{

				if($k === 0){
					array_push($listOfDates,array($v["date_debut"]=>$v["date_fin"]));
					$sub = ";".$v["hex_string"];
					$code = $v["code"]." (".$v["propriete_category"].") ";
					$listOfAppartements[$code.$sub] = $listOfDates;
				}else{
					$listOfAppartements[$code.$sub] = $listOfDates;
					$listOfDates = array();

					array_push($listOfDates,array($v["date_debut"]=>$v["date_fin"]));
					$sub = ";".$v["hex_string"];
					$code = $v["code"]." (".$v["propriete_category"].") ";

					$listOfAppartements[$v["code"]." (".$v["propriete_category"].") ".$sub] = $listOfDates;

				}			
			}
		}
	}

	//var_dump($listOfAppartements);
	//die();
	$row = array();
	/*
	for($i=1;$i<=$days_in_selected_month;$i++){
		$row[$i] = "empty;#ededed";
	}
	*/
	$listOfRows = array();
	foreach($listOfAppartements as $code=>$dates){
		
		for($i=1;$i<=$days_in_selected_month;$i++){
			$row[$i] = "empty;#ededed";
		}
		
		$day = "01";
		$_month = (($current_month + $counter)>9)? ($current_month + $counter): "0". ($current_month + $counter);
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
	//var_dump($listOfRows);
	//die();
	$args = array("options"=>$options,"data"=>$listOfRows);
	//echo $calendar->drawCalendar($current_month,$current_year, $args);
	//die();
	
	
}
/*
var_dump($data);
die();
*/
//echo $calendar->drawCalendar($current_month,$current_year, $args);
//die();
$response  = array("code"=>1, "msg"=>$calendar->drawCalendar($current_month,$current_year, $args));

echo json_encode($response);
