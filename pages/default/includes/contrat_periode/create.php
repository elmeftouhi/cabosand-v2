<div class="periode_add mb-20">
	<div class="row pb-5">
		<div class="col_3-inline">
			<label for="date_debut">Date Début</label>
			<input type="hidden" id="UID" value="<?= $UID ?>" class="field required">
			<input type="date" id="date_debut" value="<?= isset($periode)? $periode["date_debut"]: $year."-01-01" ?>">
			<?php
				if(isset($periode))
					echo '<input type="hidden" id="id" value="'.$periode["id"].'">'
			?>
		</div>
		<div class="col_3-inline">
			<label for="date_fin">Date Fin </label>
			<input type="date" id="date_fin" value="<?= isset($periode)? $periode["date_fin"]: $year."-12-31" ?>">
		</div>
		<div class="col_3-inline">
			<label for="nbr__nuite">Nuités </label>
			<input type="number" readonly id="nbr__nuite" value="<?= isset($periode)? $periode["nbr_nuite"]: "365" ?>" style="font-size: 16px; text-align: center; background-color: rgba(249,245,191,1.00)">
		</div>
		
		<div class="col_3-inline d-flex pt-20">
			<div class="">
				<label class="switch" style="width: 40px">
					<input class="field" id="status" type="checkbox" <?= isset($periode)? ($periode["status"] === "1"? "checked": ""): "" ?>>
					<span class="slider round"></span>
				</label>
			</div>
			<div class="pt-5 pl-5"> Activé</div>
		</div>
		
	</div>


	<div class="row pb-10 pt-10">
		<div class="col_12 d-flex">
			<button class="blue periode_store mr-10">Enregistrer</button>
			<?php
				if(isset($periode))
					echo '<button class="periode_delete red mr-10" value="'.$periode["id"].'">Supprimer</button>';
			?>
			<button class="periode_abort">Annuler</button>
		</div>	
	</div>

</div>