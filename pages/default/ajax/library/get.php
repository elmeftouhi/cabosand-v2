<?php session_start();

if(!isset($_SESSION['CORE'])){die();}
if(!isset($_POST['data'])){die();}


$table_name = $_POST['data']['t_n'];
$core = $_SESSION['CORE'];

if(file_exists($core.$table_name.".php")){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	$p_p = $_POST['data']['p_p'];
	$sort_by = $_POST['data']['sort_by'];
	$temp = explode(" ", $sort_by );
	$order = "";
	if(count( $temp ) > 1 ){ $order =  $temp[1]; }
	
	$current = $p_p *  $_POST['data']['current'];
		$request =  $_POST['data']['request'];
		if($request == ""){
			$values = $ob->find(null,array("order"=>$sort_by,"limit"=>array($current,$p_p)),"v_produit");
			$totalItems = $ob->getTotalItems();
		}else{
			$totalItems = $ob->find(null,array("conditions"=>array("LOWER(title) like "=>"%".$request."%"),"order"=>$sort_by),"v_produit");
			$values = $ob->find(null,array("conditions"=>array("LOWER(title) like "=>"%".$request."%"),"order"=>$sort_by,"limit"=>array($current,$p_p)),"v_produit");
		}


		$returned = "<div class='info info-error'>Error DATA </div>";
		
		$returned = '<div class="col_12" style="padding: 0">';
	
		$returned .= '	<div style="display: flex; flex-direction: row">';
		$returned .= '		<div style="flex: auto; padding: 15px 0 10px 5px; margin: 0; color: rgba(118,17,18,1.00)">';
		$returned .= '			Total : ('.count($values).' / '.$totalItems.') <span class="current hide">'.$_POST['data']['current'].'</span>';
		$returned .= '		</div>';
		$returned .= '		<div style="width: 10rem">';
		$returned .= '		<div style="flex-direction: row; display: flex">';
		$returned .= '			<div style="flex: 1">';
		$returned .= '				<select id="showPerPage">';
		$returned .= '					<option value="20" ' . ( $p_p == 20 ? "selected" : "") .'>20</option>';
		$returned .= '					<option value="50" ' . ( $p_p == 50 ? "selected" : "") .'>50</option>';
		$returned .= '					<option value="100" ' . ( $p_p == 100 ? "selected" : "") .'>100</option>';
		$returned .= '					<option value="200" ' . ( $p_p == 200 ? "selected" : "") .'>200</option>';
		$returned .= '					<option value="500" ' . ( $p_p == 500 ? "selected" : "") .'>500</option>';
		$returned .= '				</select>';
		$returned .= '					<span class="hide ' . $order . '" id="sort_by">'.$sort_by.'</span>';
		$returned .= '			</div>';
		$returned .= '			<div style="flex: 1; text-align: center">';
		$returned .= '				<div class="btn-group">';
		$returned .= '					<a style="padding: 12px 12px" id="btn_passive_preview"  title="Précédent"><i class="fa fa-chevron-left"></i></a>';
		$returned .= '					<a style="padding: 12px 12px" id="btn_passive_next" title="Suivant"><i class="fa fa-chevron-right"></i></a>';
		$returned .= '				</div>';
		$returned .= '			</div>';
		$returned .= '		</div>';
		$returned .= '		</div>';
		$returned .= '	</div>';	
	
		$returned .= '	<div class="panel" style="overflow: auto;">';
		$returned .= '		<div class="panel-content" style="padding: 0">';
		
		$returned .= '			<table class="table">';
		$returned .= '				<thead>';
		$returned .= '					<tr>';
		
		$columns = $ob->getColumns();
		foreach($columns as $key=>$value){
			$returned .=  "<th class='sort_by' data-sort='" . $value["column"] . "'>" . $value["label"] . "</th>";
		}
		$returned .=  "<th></th>";
		$returned .= '					</tr>';
		$returned .= '				</thead>';
		$returned .= '				<tbody>';
		
		
		
		$i = 0;
		foreach($values as $k=>$v){
			$returned .= '					<tr class="edit_ligne" data-page="'.$table_name.'">';
									foreach($columns as $key=>$value){
										if(isset($v[ $columns[$key]["column"] ])){
											if($columns[$key]["column"] == "is_default"){
												$returned .= ($v[ $columns[$key]["column"] ] == 0)? "<td>-</td>": "<td>Par Défaut</td>";
											}elseif($columns[$key]["column"] == "id"){
												$returned .= "<td><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
											}elseif($columns[$key]["column"] == "status"){
												if($v[ $columns[$key]["column"] ] == 1){
													$returned .= "<td><div class='label label-green'>Activé</div></td>";
												}else{
													$returned .= "<td><div class='label label-red'>Activé</div></td>";
												}
												
											}else{
												$returned .= "<td>" . $v[ $columns[$key]["column"] ] . "</td>";
											}											
										}else{
											$returned .= "<td>NaN</td>";
										}


									}
			$returned .= "<td><button data-page='".$table_name."' class='btn btn-red remove_ligne' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button></td>";
			$returned .= '					</tr>';
		$i++	;
		}
		
		
	
		$returned .= '				</tbody>';
		$returned .= '			</table>';
		$returned .= '		</div>';
		$returned .= '	</div">';
		$returned .= '</div>';
		
		echo $returned;		

}else{
	echo -1;
}