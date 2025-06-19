<div class="ppl_add mb-20">
	<div class="row pb-5">
		<div class="col_4">
			<label for="periode_de">Date Début</label>
			<input type="date" id="periode_de" value="<?= isset($ppl)? $ppl["de"]: $year."-01-01" ?>">
			<?php
				if(isset($ppl))
					echo '<input type="hidden" id="id" value="'.$ppl["id"].'">'
			?>
		</div>
		<div class="col_4">
			<label for="periode_a">Date Fin </label>
			<input type="date" id="periode_a" value="<?= isset($ppl)? $ppl["a"]: $year."-12-31" ?>">
		</div>
		<div class="col_4">
			<label for="periode_nuite">Nuités </label>
			<input type="number" id="periode_nuite" value="<?= isset($ppl)? $ppl["nbr_nuite"]: "365" ?>" style="font-size: 16px; text-align: center; background-color: rgba(249,245,191,1.00)">
		</div>
	</div>
	<div class="row pb-10">
		<div class="col_4">
			<label for="periode_montant">Montant</label>
			<input type="number" id="periode_montant" value="<?= isset($ppl)? $ppl["montant"]: "" ?>">
		</div>
		<div class="col_8">
			<label for="date_fin">Date Fin </label>
			<select id="ppl_type">
				<option selected value="-1"></option>
				<option <?= isset($ppl)? ($ppl["id_propriete_location_type"] == "1"? "selected": ""): "" ?> value="1">Par Nuit</option>
				<option <?= isset($ppl)? ($ppl["id_propriete_location_type"] == "2"? "selected": ""): "" ?> value="2">Par Mois</option>
				<option <?= isset($ppl)? ($ppl["id_propriete_location_type"] == "3"? "selected": ""): "" ?> value="3">Forfait</option>
			</select>
		</div>
	</div>
	<div class="row pb-10">
		<div class="col_12 d-flex">
			<div>
				<label class="switch" style="width: 40px">
					<input class="field" id="status" type="checkbox" <?= isset($ppl)? ($ppl["status"] == "1"? "checked": ""): "" ?>>
					<span class="slider round"></span>
				</label>
			</div>
			<div class="pt-5 pl-5"> Activé</div>
		</div>	
	</div>

	<div class="row pb-10 pt-10">
		<div class="col_12 d-flex">
			<button class="blue ppl_store mr-10" value="<?= isset($ppl)? $ppl["id_propriete"]: $id_propriete ?>">Enregistrer</button>
			<?php
				if(isset($ppl))
					echo '<button class="ppl_delete red mr-10" value="'.$ppl["id"].'">Supprimer</button>';
			?>
			<button class="ppl_abort">Annuler</button>
		</div>	
	</div>

</div>