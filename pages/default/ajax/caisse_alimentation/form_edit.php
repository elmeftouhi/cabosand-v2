<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Caisse";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Caisse
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-content">
		<h3 style="margin-left: 6px">Caisse</h3>
		<input type="hidden" id="id" value="<?= $data["id"] ?>">
		<div class="row" style="margin-bottom: 20px">
			<div class="col_12-inline">
				<label for="name">Libellé </label>
				<input type="text" placeholder="Libellé" id="name" value="<?= $data["name"] ?>">
			</div>				

		</div>	
		<div class="row" style="margin-bottom: 20px">
			<div class="col_6-inline">
				<label for="solde_initial">Solde Initial </label>
				<input type="number" placeholder="Solde Initial" id="solde_initial" value="<?= $data["solde_initial"] ?>" style="text-align: right; font-size: 18px; font-weight: bold">
			</div>				
			<div class="col_6-inline">
				<label for="solde_minimum">Solde Minimum </label>
				<input type="number" placeholder="Solde Minimum" id="solde_minimum" value="<?= $data["solde_minimum"] ?>" style="text-align: right; font-size: 18px; font-weight: bold">
			</div>	
		</div>


		<h3 style="margin-left: 6px">NOTE(S)</h3>
		<div class="row" style="margin-bottom: 20px">
			<div class="col_12-inline">
				<textarea id="notes" style="max-width: 100%; height: 120px"><?= $data["notes"] ?></textarea>
			</div>					
		</div>			
		<div class="row" style="margin-bottom: 20px">
			<div class="col_6-inline">
				<div class="on_off <?= ($data["status"] == "1")? "on":"off" ?>" id="caisse_status"></div>
			</div>						
		</div>
	</div>


</div>

<div class="debug_client"></div>

