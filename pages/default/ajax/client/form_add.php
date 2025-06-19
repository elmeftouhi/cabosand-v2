<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Client";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Client
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

			<h3 style="margin-left: 6px">Client</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="first_name">Prénom </label>
					<input type="text" placeholder="Prénom" id="first_name" value="">
				</div>				
				<div class="col_6-inline">
					<label for="last_name">Nom </label>
					<input type="text" placeholder="Nom" id="last_name" value="">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="societe_name">Société </label>
					<input type="text" placeholder="Société" id="societe_name" value="">
				</div>				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_4-inline">
					<label for="client_category">Catégorie </label>
					<select id="client_category">
						<option selected value="-1"></option>
							<?php require_once($core."Client_Category.php"); 
								foreach( $client_category->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["is_default"] == "1")? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_category"]  ?> </option>
							<?php } ?>
					</select>
				</div>				
				<div class="col_4-inline">
					<label for="client_type">Type </label>
					<select id="client_type">
						<option selected value="-1"></option>
							<?php require_once($core."Client_Type.php"); 
								foreach( $client_type->find("", array(), "") as $k=>$v){
							?>	
						<option <?= ($v["is_default"] == "1")? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_type"] ?> </option>
							<?php } ?>
					</select>
				</div>
				<div class="col_4-inline">
					<label for="client_status">Status </label>
					<select id="client_status">
						<option selected value="-1"></option>
							<?php require_once($core."Client_Status.php"); 
								foreach( $client_status->find("", array(), "") as $k=>$v){
							?>	
						<option <?= ($v["is_default"] == "1")? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_status"] ?> </option>
							<?php } ?>
					</select>
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_cin">CIN </label>
					<input type="text" placeholder="CIN" id="client_cin" value="">
				</div>				
				<div class="col_6-inline">
					<label for="client_passport">PASSPORT </label>
					<input type="text" placeholder="Passport" id="client_passport" value="">
				</div>	
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_ville">Ville </label>
					<input type="text" placeholder="Ville" id="client_ville" value="">
				</div>	
				<div class="col_6-inline">
					<label for="client_email">E-Mail </label>
					<input type="text" placeholder="E-Mail" id="client_email" value="">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="client_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="client_adresse" value="">
				</div>	

			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_contact_1">Contact (1)</label>
					<input type="text" placeholder="Contact" id="client_contact_1" value="">
				</div>	
				<div class="col_6-inline">
					<label for="client_contact_2">Contact (2)</label>
					<input type="text" placeholder="Contact" id="client_contact_2" value="">
				</div>				
			</div>	
						
			<div class="row" style="margin-bottom: 20px">
				<div class="col_1-inline">
					<label for="client_contact_1">Color</label>
					<select id="id_color">
						<option selected value="-1"></option>
							<?php require_once($core."Client_Type.php"); 
								foreach( $client_type->find("", array(), "colors") as $k=>$v){
							?>	
						<option data-hex='<?= $v["hex_string"] ?>' style='color:white;background-color:<?= $v["hex_string"] ?>' value="<?= $v["color_id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>
				</div>	
				<div class="col_1-inline">
					<label for="">.</label>
					<div id="color" style="background-color: white; padding:16px 10px"></div>
				</div>
			</div>
			
		<h3 style="margin-left: 6px">NOTE(S)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<textarea id="client_notes" style="max-width: 100%; height: 120px"></textarea>
				</div>					
			</div>						
		</div>		

	</div>


</div>

<div class="debug_client"></div>

