<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Complexe_Type";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Type
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header">
	Type
	</div>
	<div class="panel-content">

		<div class="menu_form">

			<h3 style="margin-left: 6px">Type</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Libelle" id="complexe_type" value="">
				</div>				
				
			</div>	
			
		</div>		


	</div>


</div>

<div class="debug_client"></div>

