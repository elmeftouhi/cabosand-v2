<div id="popup" style="max-width: 350px; text-align: left">	

	<div class="popup-header d-flex space-between">
		<div class="">Détail location</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="propriete_location" class="">
			<div class="row">
				<div class="col_12">
					<div class="form-element">
						<label for="client">Client</label>
						<input type="text" id="client" value="<?= isset($PL)? $PL["client_first_name"] . " " . $PL["client_last_name"]: "" ?>" class="required field">
					</div>
				</div>
			</div>
		
			<div class="row">
				<div class="col_6-inline">
					<div class="form-element">
						<label for="client">Début Période</label>
						<input type="text" id="client" value="<?= isset($PL)? $PL["periode_date_debut"]: "" ?>" class="required field">
					</div>
				</div>
				<div class="col_6-inline">
					<div class="form-element">
						<label for="client">Fin Période</label>
						<input type="text" id="client" value="<?= isset($PL)? $PL["periode_date_fin"]: "" ?>" class="required field">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col_12">
					<div class="form-element">
						<label for="complexe">Complexe</label>
						<input type="text" id="complexe" value="<?= isset($PL)? $PL["complexe"]: "" ?>" class="required field">
					</div>
				</div>
			</div>
			
			<hr>
			
			<div class="row">
				<div class="col_6-inline">
					<div class="form-element">
						<label for="client">Début Location</label>
						<input type="text" id="client" value="<?= isset($PL)? $PL["date_debut"]: "" ?>" class="required field">
					</div>
				</div>
				<div class="col_6-inline">
					<div class="form-element">
						<label for="client">Fin Location</label>
						<input type="text" id="client" value="<?= isset($PL)? $PL["date_fin"]: "" ?>" class="required field">
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Propriete_Location">Enregistrer</button></li>
			<?php if(isset($PL)) { ?>
			<li><button class="delete red" data-controler="Propriete_Location" value="<?= $PL["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>