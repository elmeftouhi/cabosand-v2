<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];

$data = $ob->find("",array("conditions"=>array("id="=>$id)),"v_client")[0];

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
			<input type="hidden" id="id" value="<?= $data["id"] ?>">
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="first_name">Prénom </label>
					<input type="text" placeholder="Prénom" id="first_name" value="<?= $data["first_name"] ?>">
				</div>				
				<div class="col_6-inline">
					<label for="last_name">Nom </label>
					<input type="text" placeholder="Nom" id="last_name" value="<?= $data["last_name"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="societe_name">Société </label>
					<input type="text" placeholder="Société" id="societe_name" value="<?= $data["societe_name"] ?>">
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
						<option <?= ($v["id"] == $data["id_category"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_category"]  ?> </option>
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
						<option <?= ($v["id"] == $data["id_type"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_type"] ?> </option>
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
						<option <?= ($v["id"] == $data["id_status"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["client_status"] ?> </option>
							<?php } ?>
					</select>
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_cin">CIN </label>
					<input type="text" placeholder="CIN" id="client_cin" value="<?= $data["cin"] ?>">
				</div>				
				<div class="col_6-inline">
					<label for="client_passport">PASSPORT </label>
					<input type="text" placeholder="Passport" id="client_passport" value="<?= $data["passport"] ?>">
				</div>	
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_ville">Ville </label>
					<input type="text" placeholder="Ville" id="client_ville" value="<?= $data["ville"] ?>">
				</div>	
				<div class="col_6-inline">
					<label for="client_email">E-Mail </label>
					<input type="text" placeholder="E-Mail" id="client_email" value="<?= $data["email"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="client_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="client_adresse" value="<?= $data["adresse"] ?>">
				</div>	

			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="client_contact_1">Contact (1)</label>
					<input type="text" placeholder="Contact" id="client_contact_1" value="<?= $data["phone_1"] ?>">
				</div>	
				<div class="col_6-inline">
					<label for="client_contact_2">Contact (2)</label>
					<input type="text" placeholder="Contact" id="client_contact_2" value="<?= $data["phone_2"] ?>">
				</div>				
			</div>	
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_1">
					<label for="client_contact_1">Color</label>
					<select id="id_color">
						<option selected value="-1"></option>
							<?php require_once($core."Client_Type.php"); 
								foreach( $client_type->find("", array(), "colors") as $k=>$v){
							?>	
						<option data-hex='<?= $v["hex_string"] ?>' style='color:white;background-color:<?= $v["hex_string"] ?>' <?= ($v["color_id"] == $data["id_color"])? "selected":"" ?> value="<?= $v["color_id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>
				</div>	
				<div class="col_1">
					<label for="">.</label>
					<div id="color" style="background-color: <?= $data["hex_string"] ?>; padding:16px 10px"></div>
				</div>
			</div>
			
		<h3 style="margin-left: 6px">NOTE(S)</h3>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<textarea id="client_notes" style="max-width: 100%; height: 120px"><?= $data["notes"] ?></textarea>
				</div>					
			</div>						
		</div>		

	</div>


</div>

<div class="debug_client"></div>
