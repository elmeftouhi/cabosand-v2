<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Propriete";
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
	[ 'hide'=>'', 'id'=>'code', 'label' => 'Code'],
	[ 'hide'=>'hide', 'id'=>'nbr_chambre', 'label' => 'Chambres'],
	[ 'hide'=>'hide', 'id'=>'notes', 'label' => 'Notes'],
	[ 'hide'=>'hide', 'id'=>'proprietaire', 'label' => 'Proprietaire'],
	[ 'hide'=>'hide', 'id'=>'phone', 'label' => 'Téléphone']
];

$filters = [
	'Categorie'				=>	$ob->find('', ['order'=>'propriete_category'], 'propriete_category'),
	'Type'					=>	$ob->find('', ['order'=>'propriete_type'], 'propriete_type'),
	'Complexe'				=>	$ob->find('', ['order'=>'name'], 'complexe'),
	'Status'				=>	$ob->find('', ['order'=>'propriete_status' ], 'propriete_status'),
	'Vente'					=>	[0=>['id'=>0, 'label'=>'Non Vente'], 1=>['id'=>1, 'label'=>'A Vendre']],
	'Location'				=>	[0=>['id'=>0, 'label'=>'Non Location'], 1=>['id'=>1, 'label'=>'A Louer']]
];
	
?>

<div id="page" class="">
	<div class="page-head">
		<div class="title d-flex space-between">
			<div class="name">	Appartement</div> 
			<div class="actions d-flex">
				<button class="green add" data-controler="<?= $table_name ?>"><i class="fas fa-plus"></i> Ajouter</button>
			</div>
			
		</div>
		<div class="search d-flex space-between">
			<div class="request d-flex">
				<input type="text" placeholder="chercher" class="mr-5">
				<button class="mr-5 page_search_button" data-controler="<?= $table_name ?>" data-use="v_propriete" data-column_style="v_propriete"><i class="fa fa-search"></i></button>
				
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
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["propriete_category"] ) ."</option>";
							if($key === "Type")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["propriete_type"] ) ."</option>";
							if($key === "Complexe")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["name"] ) ."</option>";
							if($key === "Status")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["propriete_status"] ) ."</option>";
							if($key === "Vente")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["label"] ) ."</option>";
							if($key === "Location")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["label"] ) ."</option>";
							if($key === "Mois"){
								if( $k === intval(date("m")) ) 
									$string .= '<option selected value="'.$k.'">'.$v."</option>";
								else
									$string .= '<option value="'.$k.'">'.$v."</option>";
							}
							if($key === "Années"){
								if( $k == intval(date("Y")) ) 
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
					'column_style'	=>	'v_propriete',
					'use'			=>	'v_propriete',
					'filters'		=>	[  ],
					'pp'			=>	20,
					'current'		=>	0

				];
				echo $ob->Table($params);
			?>
		</div>
	</div>
</div>
