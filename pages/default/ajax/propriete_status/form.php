<?php session_start(); 
if(!isset($_SESSION['CORE'])){ die("-1"); }
if(!isset($_POST["page"])){ die("-2"); }

$core = $_SESSION['CORE']; 
$action = "add";
$table_name = $_POST["page"];
if(!file_exists($core.$table_name.".php")){ die("-3"); }
$formToken = md5( uniqid('auth', true) );
$id = 0;

require_once($core.$table_name.".php");
$ob = new $table_name();

$colors = $ob->fetchAll("colors");

if(isset($_POST["id"])){
	$id = $_POST["id"];
	$data = $ob->find("",array("conditions"=>array("id="=>$id)),"v_propriete_status");
	if( count( $data ) < 1){ die("-4"); }
	$data = $data[0];
	$action = "edit";
}

?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Propriete Status
		<?= ($action === "edit")? "<input class='form-element' type='hidden' id='id' value='".$id."'>" : "" ?>
	</div>
	<div class="col_6-inline actions">
		<button class="btn btn-green save_form <?= ($action === "edit")? "edit" : "" ?>" data-table="<?= $table_name ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $table_name ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-content" style="padding: 0px">
		<div class="row  <?= strtolower($table_name) ?>" style="margin-top: 25px">
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12">
					<label for="propriete_status">Status</label>
					<input class="form-element required" type="text" placeholder="Propriete Status" id="propriete_status" value="<?= ($action === "edit")? $data["propriete_status"] : "" ?>">
				</div>	
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off <?= ($action === "edit")? ($data["is_default"])? "on" : "off" : "off" ?> form-element" id="is_default"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Par Défaut 
						</span>
					</div>
				</div>						
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_1">
					<label for="id_color">Color</label>
					<select id="id_color" class="form-element">
						<option selected value="-1"></option>
							<?php  
								foreach( $colors as $k=>$v){
							?>	
						<option data-hex='<?= $v["hex_string"] ?>' style='color:white;background-color:<?= $v["hex_string"] ?>' value="<?= $v["color_id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>
				</div>	
				<div class="col_1">
					<label for="">.</label>
					<div id="color" style="background-color: white; padding:16px 10px"></div>
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6">
					<div class="" style="position: relative; width: 155px">
						<div class="on_off <?= ($action === "edit")? ($data["all_ligne"])? "on" : "off" : "off" ?> form-element" id="all_ligne"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Toute la ligne
						</span>
					</div>
				</div>						
			</div>
			
			
		</div> <!-- ROW-->

	</div>	<!-- END PANEL CONTENT -->

</div>

<div class="debug"></div>