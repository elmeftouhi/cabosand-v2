<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Proprietaire";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Propriétaire
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-content">

		<div class="menu_form">

			<h3 style="margin-left: 6px">Propriétaire</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="proprietaire_name">Nom et Prénom </label>
					<input type="text" placeholder="Nom et Prénom" id="proprietaire_name" value="">
				</div>				
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_cin">CIN </label>
					<input type="text" placeholder="CIN" id="proprietaire_cin" value="">
				</div>				
				<div class="col_6-inline">
					<label for="proprietaire_passport">PASSPORT </label>
					<input type="text" placeholder="Passport" id="proprietaire_passport" value="">
				</div>	
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="proprietaire_ville">Ville </label>
					<input type="text" placeholder="Ville" id="proprietaire_ville" value="">
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="proprietaire_adresse" value="">
				</div>	
				<div class="col_6-inline">
					<label for="proprietaire_email">E-Mail </label>
					<input type="text" placeholder="E-Mail" id="proprietaire_email" value="">
				</div>
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_contact_1">Contact (1)</label>
					<input type="text" placeholder="Contact" id="proprietaire_contact_1" value="">
				</div>	
				<div class="col_6-inline">
					<label for="proprietaire_contact_2">Contact (2)</label>
					<input type="text" placeholder="Contact" id="proprietaire_contact_2" value="">
				</div>				
			</div>	
		<h3 style="margin-left: 6px">BANQUE (RIB)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="proprietaire_agence_1">Agence</label>
					<input type="text" placeholder="Agence" id="proprietaire_agence_1" value="">
				</div>	
				<div class="col_4-inline">
					<label for="proprietaire_rib_1">RIB</label>
					<input type="text" placeholder="RIB" id="proprietaire_rib_1" value="">
				</div>				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="proprietaire_agence_2">Agence</label>
					<input type="text" placeholder="Agence" id="proprietaire_agence_2" value="">
				</div>	
				<div class="col_4-inline">
					<label for="proprietaire_rib_2">RIB</label>
					<input type="text" placeholder="RIB" id="proprietaire_rib_2" value="">
				</div>				
			</div>	
		<h3 style="margin-left: 6px">NOTE(S)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<textarea id="proprietaire_notes" style="max-width: 100%; height: 120px"></textarea>
				</div>					
			</div>			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="on_off on" id="proprietaire_status"></div>
				</div>						
			</div>			
		</div>		

	</div>


</div>

<div class="debug_client"></div>

