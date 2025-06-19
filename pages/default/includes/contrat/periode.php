<?php
$core = $_SESSION["CORE"];
$status = [
	0 =>	'<div class="label label-red">Désactivé</div>',
	1 =>	'<div class="label label-green">Activé</div>'
]

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Programmer des Périodes</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="periode" class="row" style="width: 780px; min-height: 250px">
			
			<div class="periode_container"></div>
			
			<!-- DETAILS -->
			<div class="col_4-inline periode" style="">
				<div class="d-flex space-between">
					<div class="title">
						Les Périodes
					</div>
					<div>
						<button class="green add" value="<?= $UID ?>"><i class="far fa-calendar-plus"></i> Ajouter</button>
						<button class="refresh hide" value="<?= $UID ?>">ref</button>
					</div>
				</div>

				<div class="items">
					<?php 
						if (count($periodes) === 0){
					?>
						<div class="d-flex text-left">
							<div class="info info-success">
								<div class="info-message"> 
								Aucune période n'est enrégistrée
								</div>
							</div>				
						</div>

					<?php } ?>
					
					<?php foreach($periodes as $k=>$p) { ?>
					<div data-id="<?= $p["id"] ?>" data-date_debut="<?= $p["date_debut"] ?>" data-date_fin="<?= $p["date_fin"] ?>" class="show_this_periode item d-flex space-between <?= $k===0? "active": "" ?>">
						<div class="d-flex">
							<div class="dates d-flex">
								<div class="date_debut"><?= $p["date_debut"] ?></div>
								<div class="nbr_jours"><?= $p["nbr_nuite"] ?></div>
								<div class="date_fin"><?= $p["date_fin"] ?></div>
							</div>
							<div class="status"><?= $status[$p["status"]] ?></div>						
						</div>
						<div>
							<button class="periode_update" value="<?= $p["id"] ?>"><i class="fas fa-ellipsis-h"></i></button>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>

			<div class="col_8-inline appartements">
				<div class="d-flex space-between">
					<div class="title">
						Les Appartements
					</div>
					<div>
						<button class="green select_propriete" value="<?= $UID ?>"><i class="far fa-calendar-plus"></i> Ajouter</button>
					</div>
				</div>
				<div class="search_bar_2 pt-10" style="position:relative">
					<input type="text" class="request_3">
					<div class="result_counter_2 hide" style="position: absolute; top:20px; right:20px; color:green; font-size:10px">0</div>
				</div>
				<div class="items">
					
					<?php 
						if (count($locations) === 0){
					?>
						<div class="d-flex text-left">
								<div class="info info-success">
									<div class="info-message"> 
									Aucun Appartement n'est selectionné
									</div>
								</div>				
						</div>

					<?php } ?>
					
					<?php foreach($locations as $p) { ?>
					<div class="item d-flex space-between app">
						<div class="d-flex">
							<div class="dates d-flex">
								<div class="code"><?= $p["code"] ?></div>
								<div class="proprietaire"><?= $p["proprietaire"] ?></div>
								<div class="date_debut"><?= $p["date_debut"] ?></div>
								<div class="nbr_jours">0</div>
								<div class="date_fin"><?= $p["date_fin"] ?></div>
							</div>
							<div class="status"><?= $status[$p["status"]] ?></div>						
						</div>
						<div>
							<button><i class="fas fa-ellipsis-h"></i></button>
						</div>
					</div>
					<?php } ?>
				</div>
				
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
