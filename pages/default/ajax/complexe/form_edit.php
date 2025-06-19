<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Complexe";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Complexe
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

			<h3 style="margin-left: 6px">Complexe</h3>
			<input type="hidden" value="<?= $data["id"]  ?>" id="id">
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="complexe_name">Complexe </label>
					<input type="text" placeholder="Complexe" id="complexe_name" value="<?= $data["name"] ?>">
				</div>				
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_3-inline">
					<label for="complexe_ABR">ABR </label>
					<input type="text" placeholder="ABR" id="complexe_ABR" value="<?= $data["ABR"] ?>" maxlength="4">
				</div>	
							
				<div class="col_9-inline">
					<label for="complexe_type">Complexe Type </label>
					<select id="complexe_type">
						<option selected value="-1"></option>
					<?php
						require_once($core."Complexe_Type.php");
						foreach($complexe_type->fetchAll() as $k=>$v){
					?>
						<option <?= ($data["id_complexe_type"] === $v["id"])? "selected":"" ?> value="<?= $v["id"] ?>"><?= $v["complexe_type"] ?></option>
					<?php
						}
					?>
					</select>
				</div>					
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="complexe_ville">Ville </label>
					<input type="text" placeholder="Ville" id="complexe_ville" value="<?= $data["ville"] ?>">
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<label for="complexe_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="complexe_adresse" value="<?= $data["adresse"] ?>">
				</div>	
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="complexe_contact1">Personne Contact (1)</label>
					<input type="text" placeholder="Contact" id="complexe_contact1" value="<?= $data["contact_1"] ?>">
				</div>	
				<div class="col_4-inline">
					<label for="complexe_phone1">Phone </label>
					<input type="text" placeholder="Phone" id="complexe_phone1" value="<?= $data["phone_1"] ?>">
				</div>
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="complexe_contact2">Personne Contact (2)</label>
					<input type="text" placeholder="Contact" id="complexe_contact2" value="<?= $data["contact_2"] ?>">
				</div>	
				<div class="col_4-inline">
					<label for="complexe_phone2">Phone </label>
					<input type="text" placeholder="Phone" id="complexe_phone2" value="<?= $data["phone_2"] ?>">
				</div>
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<?php 
				require_once($core."Complexe_Facilities.php");
				$facilities = $ob->find("",array("conditions"=>array("id_complexe="=>$data["id"])),"facilities_in_complexe");
				$facilities = (is_null($facilities))? array() : $facilities;
				$allFacilities = $complexe_facilities->fetchAll();	
				$isExist = false;
				foreach($allFacilities as $k=>$v){
					$isExist = false;
					foreach($facilities as $kk=>$vv){
					if($v["id"] == $vv["id_complexe_facilities"]){
						$isExist = true;
					}
					
					}
					if($isExist){
						echo "<div class='col_12'><label><input checked type='checkbox' value='".$v['id']."'>".$v["complexe_facilities"]."</label></div>";
					}else{
						echo "<div class='col_12'><label><input type='checkbox' value='".$v['id']."'>".$v["complexe_facilities"]."</label></div>";
					}	

				
				}
				
				?>
			</div>	
		</div>		


	</div>


</div>

<div class="debug_client"></div>

