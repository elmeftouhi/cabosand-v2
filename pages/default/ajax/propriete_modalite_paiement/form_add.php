<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Propriete_Modalite_Paiement";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Modalités de Paiement
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

			<h3 style="margin-left: 6px">Modalités de Paiement</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Modalité Paiement" id="propriete_modalite_paiement" value="">
				</div>				
				
			</div>	
			
		</div>		


	</div>


</div>

<div class="debug_client"></div>

