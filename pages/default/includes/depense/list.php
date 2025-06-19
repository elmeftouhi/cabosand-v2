<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Depense";
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
    2025	=>	'2025',
    2024	=>	'2024',
    2023	=>	'2023',
	2022	=>	'2022',
	2021	=>	'2021',
	2020	=>	'2020',
	2019	=>	'2019'	
];

$tags = [
	[ 'hide'=>'', 'id'=>'libelle', 'label' => 'Designation'],
	[ 'hide'=>'hide', 'id'=>'code', 'label' => 'Appartement'],
	[ 'hide'=>'hide', 'id'=>'first_name', 'label' => 'Utilisateur'],
	[ 'hide'=>'hide', 'id'=>'montant', 'label' => 'Montant'],
	[ 'hide'=>'hide', 'id'=>'notes', 'label' => 'Notes']
];

$filters = [
	'Categorie'				=>	$ob->find('', ['order'=>'depense_category'], 'depense_category'),
	'Complexe'				=>	$ob->find('', ['order'=>'name'], 'complexe'),
	'Caisse'				=>	$ob->find('', [ 'conditions'=>['status='=>1] ], 'caisse'),
	'Mois'					=>	$months,
	'Années'				=>	$years
];
	
?>

<div id="page" class="">
	<div class="page-head">
		<div class="title d-flex space-between">
			<div class="name">	Depenses</div> 
			<div class="actions d-flex">
				<button class="green add" data-controler="<?= $table_name ?>"><i class="fas fa-plus"></i> Ajouter</button>
			</div>
			
		</div>
		<div class="search d-flex space-between">
			<div class="request d-flex">
				<input type="text" placeholder="chercher" class="mr-5">
				<button class="mr-5 page_search_button" data-controler="<?= $table_name ?>" data-use="v_depense" data-column_style="v_depense"><i class="fa fa-search"></i></button>
				
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
							if($key === "Categorie")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["depense_category"] ) ."</option>";
							if($key === "Complexe")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["name"] ) ."</option>";
							if($key === "Caisse")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["name"] ) ."</option>";
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
				<button class="exportTo" data-target="depensetable" data-type="csv"><i class="fas fa-file-csv"></i></button>
				<button><i class="fas fa-at"></i></button>
			</div>
			
			<div class="d-flex nex_prev">
				<div class="pp">
					<select>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="200">200</option>
						<option value="500">500</option>
						<option value="1000">1000</option>
					</select>				
				</div>
				<div class="current hide">0</div>
				<div class="direction">
					<button data-step="-1"><i class="fas fa-angle-left"></i></button>
					<button data-step="1"><i class="fas fa-angle-right"></i></button>					
				</div>

			</div>
		</div>
	</div>
	<div class="page-body" style="padding-left: 10px;">

		<div class="table-container">
			<?php
				$params = [
					'column_style'	=>	'v_depense',
					'use'			=>	'v_depense',
					'filters'		=>	[ [ 'id'	=>	'Années', 'value' => date('Y')], ['id'	=>	'Mois', 'value' => date('m') ] ],
					'pp'			=>	20,
					'current'		=>	0

				];
				echo $ob->Table($params);
			?>
		</div>
	</div>
</div>
<style>
	.selectedRow{
		background-color: #f6fc74 !important;
		border-left: 2px solid #f00 !important
	}
</style>
