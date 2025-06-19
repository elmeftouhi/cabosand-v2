<?php
$core = $_SESSION["CORE"];

?>


<div id="popup" style="width: 350px">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter une Caisse</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="caisse" class="">
			
			<div class="form-element inline">
				<label for="created">Date</label>
				<input id="created" type="date" value="<?= isset($caisse)? explode(" ", $caisse["created"])[0]: date('Y-m-d'); ?>" class="field required">
				<?= isset($caisse)? '<input type="hidden" id="id" value="'.$caisse["id"].'" class="field required">': '' ?>
			</div>
			
			<div class="form-element inline">
				<label for="name">Nom</label>
				<input type="text" id="name" value="<?= isset($caisse)? $caisse["name"]: "" ?>" class="required field">
			</div>
			<div class="form-element inline">
				<label for="solde_minimum">Solde Min.</label>
				<input class="text-right required field" type="number" id="solde_minimum" placeholder="0.00" value="<?= isset($caisse)? $caisse["solde_minimum"]: "" ?>" style="background-color: rgba(249,244,196,1.00); font-size: 16px; font-weight: bold">
			</div>
			
			<div class="form-element inline">
				<label for="solde_initial">Solde Init.</label>
				<input class="text-right required field" <?= isset($caisse)? "readonly": "" ?> type="number" id="solde_initial" placeholder="0.00" value="<?= isset($caisse)? $caisse["solde_initial"]: "" ?>" style="background-color: rgba(249,244,196,1.00); font-size: 16px; font-weight: bold">
			</div>

			<div class="form-element inline">
				<label for=""></label>

				<div class="col_12 d-flex">
					<div>
						<label class="switch" style="width: 40px">
							<input class="field" id="status" type="checkbox" <?= isset($caisse)? $caisse["status"]==="1"? "checked" : "" : "checked"  ?>>
							<span class="slider round"></span>
						</label>
					</div>
					<div class="pt-5 pl-5"> Status</div>
				</div>
			</div>						

			<div class="form-element inline">
				<label for=""></label>

				<div class="col_12 d-flex">
					<div>
						<label class="switch" style="width: 40px">
							<input class="field" id="is_default" type="checkbox" <?= isset($caisse)? $caisse["is_default"]==="1"? "checked" : "" : "checked"  ?>>
							<span class="slider round"></span>
						</label>
					</div>
					<div class="pt-5 pl-5"> Par Défaut</div>
				</div>
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Caisse">Enregistrer</button></li>
			<?php if(isset($caisse)) { ?>
			<li><button class="delete red" data-controler="Caisse" value="<?= $caisse["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
