<?php session_start(); 

$core = $_SESSION["CORE"];
$table_name = "Person";
require_once($core.$table_name.".php");  
$ob = new $table_name();


$tags = [
	[ 'hide'=>'', 'id'=>'first_name', 'label' => 'Nom'],
	[ 'hide'=>'hide', 'id'=>'email', 'label' => 'Email'],
	[ 'hide'=>'hide', 'id'=>'telephone', 'label' => 'Téléphone']
];

$filters = [
	'Profile'				=>	$ob->find('', ['order'=>'person_profile DESC'], 'person_profile'),
	'Person_Status'			=>	[0=>['label'=>'Activé','value'=>1], 1=>['label'=>'Désactivé','value'=>0]],
];
	
?>

<div id="page" class="">
	<div class="page-head">
		<div class="title d-flex space-between">
			<div class="name">	Utilisateurs</div> 
			<div class="actions d-flex">
				<button class="green add" data-controler="<?= $table_name ?>"><i class="fas fa-plus"></i> Ajouter</button>
			</div>
			
		</div>
		<div class="search d-flex space-between">
			<div class="request d-flex">
				<input type="text" placeholder="chercher" class="mr-5">
				<button class="mr-5 page_search_button" data-controler="<?= $table_name ?>" data-use="v_person" data-column_style="v_person"><i class="fa fa-search"></i></button>
				
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
							if($key === "Profile")
								$string .= '<option value="'.$v["id"].'">'. strtoupper( $v["person_profile"] ) ."</option>";	
							if($key === "Person_Status")
								$string .= '<option value="'.$v["value"].'">'. strtoupper( $v["label"] ) ."</option>";		
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
					'column_style'	=>	'v_person',
					'use'			=>	'v_person',
					'filters'		=>	[  ],
					'pp'			=>	20,
					'current'		=>	0

				];
				echo $ob->Table($params);
			?>
		</div>
	</div>
</div>
