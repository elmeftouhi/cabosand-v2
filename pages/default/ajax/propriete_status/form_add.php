<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Propriete_Status";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Status
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

			<h3 style="margin-left: 6px">Status</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Status" id="propriete_status" value="">
				</div>				
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off off" id="propriete__status"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Par Défaut
						</span>
					</div>
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
						<option data-hex='<?= $v["hex_string"] ?>' style='color:white;background-color:<?= $v["hex_string"] ?>' value="<?= $v["color_id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>
				</div>	
				<div class="col_1">
					<label for="">.</label>
					<div id="color" style="background-color: white; padding:16px 10px"></div>
				</div>
				
				<div class="col_2-inline">
					<label for="all_ligne">Toute la ligne
						<input type="checkbox" id="all_ligne">
					</label>
				</div>
			</div>
			
		</div>		


	</div>


</div>

<div class="debug_client"></div>

