<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Complexe_Facilities";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Facilities
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save_edit" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header">
	Complexe Facilities
	</div>
	<div class="panel-content">

		<div class="menu_form">

			<h3 style="margin-left: 6px">Complexe Facilities</h3>
			<input type="hidden" value="<?= $data["id"]  ?>" id="id">
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Libelle" id="complexe_facilities" value="<?= $data["complexe_facilities"] ?>">
				</div>		
				
			</div>	
			
		</div>		


	</div>


</div>

<div class="debug_client"></div>

