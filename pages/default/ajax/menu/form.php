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

if(isset($_POST["id"])){

	$ob->id = $_POST["id"];
	$id = $_POST["id"];
	if( count( $ob->read() ) < 1){ die("-4"); }
	$data = $ob->read()[0];
	$action = "edit";
	
}


?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Menu
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
				<div class="col_6">
					<label for="libelle">Libelle</label>
					<input class="form-element required" type="text" placeholder="Libellé" id="libelle" value="<?= ($action === "edit")? $data["libelle"] : "" ?>">
				</div>	
				<div class="col_6-inline">
					<label for="id_parent">Parent</label>
					<select id="id_parent" class="form-element">
						<option selected value="0"></option>
						<?php 
							foreach($ob->find("",array("conditions AND"=>array("status="=>1,"id_parent="=>0), "order"=>"_order"),"") as $k=>$v){
								if($action === "add"){
									echo "<option value='".$v["id"]."'>".$v["libelle"]."</option>";
								}else{
									if($v["id"] === $data["id_parent"]){
										echo "<option selected value='".$v["id"]."'>".$v["libelle"]."</option>";
									}else{
										echo "<option value='".$v["id"]."'>".$v["libelle"]."</option>";
									}
								}								
							}
						  ?>
					</select>
				</div>	
			</div>
				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<label for="icon">Icon</label>
						<input class="form-element" type="text" placeholder="Icon Code" id="icon" value='<?= ($action === "edit")? $data["icon"] : "" ?>'>
					</div>
					<div class="col_6-inline icon_display" style="margin-top: 25px">

					</div>
				</div>
				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<label for="_order" style="display: block">Order</label>
						<input class="form-element required" style="width: 60px" type="text" placeholder="Order" id="_order" value="<?= ($action === "edit")? $data["_order"] : "0" ?>">
					</div>
					<div class="col_6-inline">
						<label for="menu_url" style="display: block">URL</label>
						<input class="form-element required" type="text" placeholder="Order" id="url" value="<?= ($action === "edit")? $data["url"] : "" ?>">
					</div>
				</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6">
					<div class="" style="position: relative; width: 125px">
						<div class="on_off <?= ($action === "edit")? ($data["status"])? "on" : "off" : "off" ?> form-element" id="status"></div>
						<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
							  Status
						</span>
					</div>
				</div>						
			</div>

		</div> <!-- ROW-->

	</div>	<!-- END PANEL CONTENT -->

</div>

<div class="debug"></div>

