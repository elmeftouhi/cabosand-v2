<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Entreprise_Forme_Juridique";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Forme Juridique
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="row">
	<div class="col_12">
		<div class="panel">
			<div class="panel-content">
				<h3 style="margin-left: 6px">Entreprise Forme Juridique</h3>
				<input type="hidden" id="id" value="<?= $data["id"] ?>">
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_9-inline">
						<label for="forme_juridique">Forme Juridique </label>
						<input type="text" placeholder="Forme Juridique" id="forme_juridique" value="<?= $data["forme_juridique"] ?>">
					</div>				
					<div class="col_3-inline">
						<label for="ABR">ABR </label>
						<input type="text" placeholder="ABR" id="ABR" value="<?= $data["ABR"] ?>">
					</div>
				</div>			
				
				<div class="row" style="margin-bottom: 20px">
					<span style="padding-left: 10px; font-size:14px">Par Défaut</span>
					<div class="col_12-inline">
						<div class="on_off <?= ($data["is_default"] == "1")? "on":"off" ?>" id="forme_juridique_is_default"> </div>
					</div>	
				</div>					
				<div class="row" style="margin-bottom: 20px">
					<span style="padding-left: 10px; font-size:14px">Status</span>
					<div class="col_12-inline">
						<div class="on_off <?= ($data["status"] == "1")? "on":"off" ?>" id="forme_juridique_status"></div>
					</div>	
				</div>
				
			</div>
		</div>
	</div><!--  -->

</div>


<div class="debug_client"></div>

