<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Person_Activity";
require_once($core.$table_name.".php");  
$ob = new $table_name();

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

$years = [
	2019	=>	'2019',
	2020	=>	'2020'
];

$tags = [
	[ 'hide'=>'', 'id'=>'activity_message', 'label' => 'Action']
];

$module = [
			0 => ['module'=>'Log', 'Libelle'=>'Conexion'],
			1 => ['module'=>'Client', 'Libelle'=>'Client'],
			2 => ['module'=>'Contrat_Periode', 'Libelle'=>'Période Contrat'],
			3 => ['module'=>'Contrat', 'Libelle'=>'Contrat'],
			4 => ['module'=>'Propriete', 'Libelle'=>'Propriété'],
			5 => ['module'=>'Depense', 'Libelle'=>'Dépense']
		];

$filters = [
	'Utilisateur'			=>	$ob->find('', ['order'=>'first_name'], 'person'),
	'Module'				=>	$module,
	'Mois'					=>	$months,
	'Années'				=>	$years
];
	
?>

<div id="page" class="">
	<div class="page-head">
		<div class="title d-flex space-between">
			<div class="name">	Log d'Activités</div> 
			<div class="actions d-flex hide">
				<button class="green add" data-controler="<?= $table_name ?>"><i class="fas fa-plus"></i> Ajouter</button>
			</div>
			
		</div>
		<div class="search d-flex space-between">
			<div class="request d-flex">
				<input type="text" placeholder="chercher" class="mr-5">
				<button class="mr-5 page_search_button" data-controler="<?= $table_name ?>" data-use="v_person_activity" data-column_style="v_person_activity"><i class="fa fa-search"></i></button>
				
				<!-- TAGS -->
				<div class="tags">
					<ul class="">
						<?php
							foreach($tags as $k=>$v){
								echo '<li class="'.$v["hide"].'" id="'.$v["id"].'">'.$v["label"].'</li>';
							}
						?>
						<li class="show_filters"><i class="fas fa-ellipsis-h"></i></li>
					</ul>
				</div>
				
			</div>
			<div class="filter">
				<?php
					$string = "";
					foreach($filters as $key=>$value){
						$string .= '<select id="'.$key.'">';
						$string .= '	<option value="-1"> -- '.$key." -- </option>";
						foreach($value as $k=>$v){
							if($key === "Module")
								$string .= '<option value="'.$v["module"].'">'. strtoupper( $v["Libelle"] ) ."</option>";
							if($key === "Utilisateur")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["first_name"] ) ."</option>";
							if($key === "Mois"){
								if( $k === intval(date("m")) ) 
									$string .= '<option selected value="'.$k.'">'.$v."</option>";
								else
									$string .= '<option value="'.$k.'">'.$v."</option>";
							}
							if($key === "Années"){
								if( $k === intval(date("Y")) ) 
									$string .= '<option selected value="'.$k.'">'.$v."</option>";
								else
									$string .= '<option value="'.$k.'">'.$v."</option>";
							}
								
						}
						$string .= '</select>';
					}
				echo $string;
				?>
			</div>
		</div>
		<div class="result d-flex space-between">
			
			<div class="totals" style="padding-top: 0!important">
				<button><i class="fas fa-chart-bar"></i></button>
				<button><i class="far fa-file-pdf"></i></button>
				<button><i class="fas fa-file-csv"></i></button>
				<button><i class="fas fa-at"></i></button>
			</div>
			
			<div class="d-flex">
				<div class="numbers">
					<select>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="200">100</option>
						<option value="500">500</option>
						<option value="1000">1000</option>
					</select>				
				</div>

				<div class="direction">
					<button><i class="fas fa-angle-left"></i></button>
					<button><i class="fas fa-angle-right"></i></button>					
				</div>

			</div>
		</div>
	</div>
	<div class="page-body" style="padding-left: 10px;">

		<div class="table-container">
			<?php
				$params = [
					'column_style'	=>	'v_person_activity',
					'use'			=>	'v_person_activity',
					'filters'		=>	[ [ 'id'	=>	'Années', 'value' => date('Y'), 'id'	=>	'Mois', 'value' => date('m') ] ]

				];
				echo $ob->Table($params);
			?>
		</div>
	</div>
</div>



























<!--


<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-user"></i> Depense(s)
	</div>
	<div class="col_6-inline actions">
		<button class="btn btn-green show_form_right_container" data-action="add" data-page="<?= $table_name ?>"><i class="fas fa-plus" aria-hidden="true"></i> Ajouter</button>
		<button class="btn btn-default refresh" value="<?= $table_name ?>"><i class="fas fa-sync-alt"></i></button>
	</div>
</div>
<hr>
<div class="row searchBar">
	<div class="col_6">

		<div class="input-group" style="overflow: hidden; margin-top: 10px">
			<input type="text" placeholder="Chercher" class="suf" name="" id="request">
			<div class="input-suf"><button title="Chercher" id="a_u_s" data="_request"><i class="fa fa-search"></i></button></div>
		</div>

	</div>
	<div class="col_6">

		<div class="row _select" style="margin-top: 10px">
			
			<div class="col_3-inline">
				<select id="propriete__complexe" data="complexe">
					<option selected value="-1"> --  Complexe  -- </option>
						<?php require_once($core."Complexe.php"); 
							foreach( $complexe->find("", array("conditions" => array("status="=>1), "order"=>"name" ), "") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["name"] ?> </option>
						<?php } ?>
				</select>
			</div>
			<div class="col_3-inline">
				<select id="caisse" data="caisse">
					<option selected value="-1"> --  Caisse  -- </option>
						<?php require_once($core."Caisse.php"); 
							foreach( $caisse->find("", array("conditions"=>array("status="=>1),"order"=>"name"), "") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["name"] ?> </option>
						<?php } ?>
				</select>
			</div>
			<div class="col_3-inline">
				<select id="depense_category" data="category">
					<option selected value="-1"> --  Catégorie  -- </option>
						<?php require_once($core."Depense_Category.php"); 
							foreach( $depense_category->find("", array("order"=>"depense_category"), "") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["depense_category"] ?> </option>
						<?php } ?>
				</select>
			</div>
			<div class="col_3-inline">
				<select id="utilisateur" data="utilisateur">
					<option selected value="-1"> --  Utilisateur  -- </option>
						<?php require_once($core."Person.php"); 
							foreach( $person->find("", array("order"=>"first_name"), "") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["first_name"] ?> </option>
						<?php } ?>
				</select>
			</div>

		</div>

	</div>

	<div class="col_12 _choices" style="padding-top: 15px"></div>
	
</div>

<div class="row">
	<div class="col_4">
		
		<?= $ob->Draw_Graph_01() ?>
			
	</div>
	
	<div class="col_8">
		<div class="row <?= strtolower($table_name) ?>">
		<?php
			$args = array(
				"column_name"		=>		"v_depense",
				"sort_by"			=>		"created desc"
						 );
			 echo $ob->drawTable($args,[],"v_depense");	 
		?>

		</div>	
	</div>
</div>

-->






<div class="debug">

</div>
