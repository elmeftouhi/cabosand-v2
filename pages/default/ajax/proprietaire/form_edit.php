<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Proprietaire";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Proprtiétaire
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save_edit" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-content">

		<div class="menu_form">

			<h3 style="margin-left: 6px">Propriétaire</h3>
			<input type="hidden" value="<?= $data["id"]  ?>" id="id">
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="proprietaire_name">Nom et Prénom </label>
					<input type="text" placeholder="Nom et Prénom" id="proprietaire_name" value="<?= $data["name"]  ?>">
				</div>				
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_cin">CIN </label>
					<input type="text" placeholder="CIN" id="proprietaire_cin" value="<?= $data["cin"]  ?>">
				</div>				
				<div class="col_6-inline">
					<label for="proprietaire_passport">PASSPORT </label>
					<input type="text" placeholder="Passport" id="proprietaire_passport" value="<?= $data["passport"]  ?>">
				</div>	
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="proprietaire_ville">Ville </label>
					<input type="text" placeholder="Ville" id="proprietaire_ville" value="<?= $data["ville"]  ?>">
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="proprietaire_adresse" value="<?= $data["adresse"]  ?>">
				</div>	
				<div class="col_6-inline">
					<label for="proprietaire_email">E-Mail </label>
					<input type="text" placeholder="E-Mail" id="proprietaire_email" value="<?= $data["email"]  ?>">
				</div>
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="proprietaire_contact_1">Contact (1)</label>
					<input type="text" placeholder="Contact" id="proprietaire_contact_1" value="<?= $data["phone_1"]  ?>">
				</div>	
				<div class="col_6-inline">
					<label for="proprietaire_contact_2">Contact (2)</label>
					<input type="text" placeholder="Contact" id="proprietaire_contact_2" value="<?= $data["phone_2"]  ?>">
				</div>				
			</div>	
		<h3 style="margin-left: 6px">BANQUE (RIB)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="proprietaire_agence_1">Agence</label>
					<input type="text" placeholder="Agence" id="proprietaire_agence_1" value="<?= $data["agence_1"]  ?>">
				</div>	
				<div class="col_4-inline">
					<label for="proprietaire_rib_1">RIB</label>
					<input type="text" placeholder="RIB" id="proprietaire_rib_1" value="<?= $data["rib_1"]  ?>">
				</div>				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="proprietaire_agence_2">Agence</label>
					<input type="text" placeholder="Agence" id="proprietaire_agence_2" value="<?= $data["agence_2"]  ?>">
				</div>	
				<div class="col_4-inline">
					<label for="proprietaire_rib_2">RIB</label>
					<input type="text" placeholder="RIB" id="proprietaire_rib_2" value="<?= $data["rib_2"]  ?>">
				</div>				
			</div>	
		<h3 style="margin-left: 6px">NOTE(S)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<textarea id="proprietaire_notes" style="max-width: 100%; height: 120px"><?= $data["notes"]  ?></textarea>
				</div>					
			</div>			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="on_off <?= ($data["status"] == 1)? "on" : "off" ?>" id="proprietaire_status"></div>
				</div>						
			</div>			
		</div>		

	</div>


</div>

<div class="debug_client"></div>

