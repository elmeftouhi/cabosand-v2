<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Person_Profile";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Profile
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

			<h3 style="margin-left: 6px">Profile</h3>
			<input type="hidden" value="<?= $data["id"]  ?>" id="id">
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12-inline">
					<input type="text" placeholder="Complexe Type" id="person_profile" value="<?= $data["person_profile"] ?>">
				</div>		
				
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off <?= ($data["is_default"] == 1)? "on" : "off" ?>" id="person_profile_status"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Par Défaut
						</span>
					</div>
				</div>						
			</div>
		</div>		


	</div>


</div>

<div class="debug_client"></div>

