<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$id = $_POST["id"];

$data = $ob->find("",array("conditions"=>array("id="=>$id)),"v_propriete_status")[0];

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

			<h3 style="margin-left: 6px">Propriete Status</h3>
			<input type="hidden" value="<?= $data["id"]  ?>" id="id">
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Propriete Status" id="propriete_status" value="<?= $data["propriete_status"] ?>">
				</div>		
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off <?= ($data["is_default"] == 1)? "on" : "off" ?>" id="propriete__status"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Par Défaut
						</span>
					</div>
				</div>						
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_1">
					<label for="id_color">Color</label>
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
				
				<div class="col_2-inline">
					<label for="all_ligne">Toute la ligne
						<input type="checkbox"  <?= ($data["all_ligne"])? "checked":"" ?> id="all_ligne">
					</label>
				</div>
				
			</div>
			
		</div>		


	</div>


</div>

<div class="debug_client"></div>

