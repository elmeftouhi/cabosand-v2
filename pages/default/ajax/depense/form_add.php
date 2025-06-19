<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Depense";
?>

<div class="container">

	<div class="row page_title">
		<div class="col_6-inline icon">
			<i class="fas fa-address-card"></i> Dépense
		</div>
		<div class="col_6-inline actions <?= strtolower($return_page) ?>">
			<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
			<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
		</div>
	</div>
	<hr>

	<div class="panel">
		
		<div class="col_6-inline actions">
			<button class="btn btn-green save_form <?= ($action === "edit")? "edit" : "" ?>" data-table="<?= $table_name ?>"><i class="fas fa-save"></i></button>
			<button class="btn btn-red close" value="<?= $table_name ?>"><i class="fas fa-times"></i></button>
		</div>
		
		<div class="panel-content">

			<h3 style="margin-left: 6px">Dépense</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_4">
					<label for="depense_category">Catégorie</label>
					<select id="depense_category">
						<option selected value="-1"></option>
							<?php require_once($core."Depense_Category.php"); 
								foreach( $depense_category->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["is_default"]==1)? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["depense_category"] ?> </option>
							<?php } ?>
					</select>
				</div>				
				<div class="col_4">
					<label for="depense_caisse">Caisse</label>
					<select id="depense_caisse">
						<option selected value="-1"></option>
							<?php require_once($core."Caisse.php"); 
								foreach( $caisse->find("",array("conditions"=>array("status="=>1)),"") as $k=>$v){
							?>	
						<option value="<?= $v["id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>
				</div>

			</div>

			<div class="row" style="margin-bottom: 20px">
				<div class="col_4">
					<label for="depense_montant">Montant</label>
					<input type="number" placeholder="Montant" value="0.00" id="depense_montant" style="background-color: #FFF9C4; text-align: center; font-weight: bold; font-size: 16px">
				</div>				
				<div class="col_8">
					<label for="depense_libelle">Libellé</label>
					<input type="text" placeholder="Libellé" id="depense_libelle">
				</div>

			</div>

			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="on_off on" id="depense_status"></div>
				</div>						
			</div>

			<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
				<div class="col_8-inline">
					<h3 style="margin-left: 6px">APPARTEMENT</h3>					
				</div>
				<div class="col_4-inline" style="text-align: right">
					<button class="btn btn-default select_propriete"><i class="fas fa-list-alt"></i> Select</button>				
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12">
					<label for="propriete_complexe">Complexe </label>
					<input type="text" placeholder="Complexe" id="propriete_complexe" value="" disabled  style="background-color: #ededed; font-weight: bold">
					<input type="hidden" id="propriete_id" value="0">
				</div>	
			</div>	
			<div class="row" style="margin-bottom: 20px;">
				<div class="col_3-inline">
					<label for="propriete_code">Code </label>
					<input type="text" placeholder="Code" id="propriete_code" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>	
				<div class="col_3-inline">
					<label for="propriete_numero">Numero </label>
					<input type="text" placeholder="Numéro" id="propriete_numero" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>
				<div class="col_3-inline">
					<label for="propriete_zone">Zone </label>
					<input type="text" placeholder="Zone" id="propriete_zone" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>	
				<div class="col_3-inline">
					<label for="propriete_bloc">Bloc </label>
					<input type="text" placeholder="Bloc" id="propriete_bloc" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>	
			</div>

			<h3 style="margin-left: 6px">NOTE(S)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<textarea id="notes" style="max-width: 100%; height: 120px"></textarea>
				</div>					
			</div>			

		</div>


	</div>

	<div class="debug"></div>	
</div>


