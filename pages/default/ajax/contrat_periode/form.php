<?php session_start(); 
if(!isset($_SESSION['CORE'])){ die("-1"); }
if(!isset($_POST["page"])){ die("-2"); }

$core = $_SESSION['CORE']; 
$action = "add";
$table_name = $_POST["page"];
if(!file_exists($core.$table_name.".php")){ die("-3"); }
$formToken = md5( uniqid('auth', true) );
$id = 0;

if(isset($_POST["id"])){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	$ob->id = $_POST["id"];
	$id = $_POST["id"];
	if( count( $ob->read() ) < 1){ die("-4"); }
	$data = $ob->read()[0];
	$action = "edit";
	
}


?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Contrat <span style="font-size: 8px; color: white; padding: 3px 10px; background-color: rgba(19,54,136,0.62); border-radius: 5px"><?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?></span>
		<?= ($action === "edit")? "<input class='form-element' type='hidden' id='id' value='".$id."'>" : "" ?>
	</div>
	<div class="col_6-inline actions">
		<button class="btn btn-green save_form <?= ($action === "edit")? "edit" : "" ?>" data-table="<?= $table_name ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $table_name ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header" style="padding: 0px">
		<div class="panel-header-tab ">
			<a href="" class="active"><i class="fas fa-file-contract" style="color:#00C853"></i> Détails</a>
			<a href=""><i class="fas fa-key"></i> Appartements</a>
			<a  href="" class="show_files contrat" data="<?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?>"><i class="fas fa-images"></i> Fichiers</a>
		</div>
	</div>
	<div class="panel-content" style="padding: 0px">
		<div class="tab-content">
			<div class="row  <?= strtolower($table_name) ?>" style="margin-top: 25px">
				<?= ($action === "edit")? "<input class='form-element' type='hidden' id='id' value='".$id."'>" : "" ?>
				<input class='form-element' type='hidden' id='UID' value='<?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?>'>
				<div class="row">
					<div class="col_12" style="margin-bottom: 20px">
						<div style="max-width: 190px">
							<label for="date_contrat">Date Contrat :</label>
							<input type="date" id="date_contrat" placeholder="jj/mm/aaaa" class="form-element required" value="<?= ($action === "edit")? date('Y-m-d', strtotime($data["date_contrat"])) : date('Y-m-d') ?>">					
						</div>
					</div>
				</div>
				<div class="row" style="margin-bottom: 20px">
					<div class="col_6">
						<label for="id_societe">Société</label>
						<select id="id_societe" class="form-element required">
							<option selected value="-1"></option>
							<?php 
								require_once($core."Entreprise.php");
								foreach($entreprise->find(null, array("conditions"=>array("status="=>1), "order"=>"raison_social"), null) as $k=>$v){
							?>
							<option <?= ($action === "edit")? ($v["id"] === $data["id_societe"])? "selected" : "" : ($v["is_default"])? "selected" : "" ?>  value="<?= $v["id"] ?>"> <?= $v["raison_social"] ?> </option>
							<?php } ?>
						</select>
					</div>	
					<div class="col_6">
						<label for="id_client">Client</label>
						<select id="id_client" class="form-element required">
							<option selected value="-1"></option>
							<?php 
								require_once($core."Client.php");
								foreach($client->find(null, array("conditions"=>array("status="=>1), "order"=>"first_name"), null) as $k=>$v){
							?>
							<option <?= ($action === "edit")? ($v["id"] === $data["id_client"])? "selected" : "" : "" ?>  value="<?= $v["id"] ?>"> <?= $v["first_name"] . " " . $v["last_name"] ?> </option>
							<?php } ?>
						</select>
					</div>	
				</div>

				<div class="row" style="margin-bottom: 20px">
					<div class="col_3">
						<label for="montant">Montant</label>
						<input type="number" id="montant" placeholder="0.00" class="form-element required" value="<?= ($action === "edit")? $data["montant"] : "0.00" ?>">
					</div>	
					<div class="col_3">
						<label for="nbr_appartement">Nbr. Appartements</label>
						<input type="number" id="nbr_appartement" placeholder="0" class="form-element required" value="<?= ($action === "edit")? $data["nbr_appartement"] : 0 ?>">
					</div>	
					<div class="col_3">
						<label for="nbr_nuite">Nbr. Nuités</label>
						<input type="number" id="nbr_nuite" placeholder="0" class="form-element required" value="<?= ($action === "edit")? $data["nbr_nuite"] : 0 ?>">
					</div>
					<div class="col_3">
						<label for="nbr_periode">Nbr. Période</label>
						<input type="number" id="nbr_periode" placeholder="0" class="form-element required" value="<?= ($action === "edit")? $data["nbr_periode"] : 0 ?>">
					</div>
				</div>

				<div class="row" style="margin-bottom: 20px">
					<div class="col_12" style="text-align: right">
						<button class="btn btn-default periode add" value="<?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?>">Définir Période</button>
					</div>	
					<?php require_once($core."Contrat_Periode.php"); echo $contrat_periode->drawTable(); ?>						
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
				<br>
				<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
					<div class="col_12-inline">
						<h3 style="margin-left: 6px">NOTES</h3>					
					</div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col_12">
						<textarea class="form-element" id="notes" style="max-width: 100%; height: 150px"><?= ($action === "edit")? $data["notes"] : "" ?></textarea>					
					</div>

				</div>


			</div> <!-- ROW-->			
		</div><!-- END TAB -->
		
		
		<div class="tab-content" style="display: none">
			<div class="location_form">
				<div class="row">
					<div class="col_4-inline">
						<h3 style="margin-left: 6px">Appartement(s)</h3>
					</div>
					<div class="col_8-inline" style="text-align: right; padding: 10px 5px">
						<button class="btn btn-green _select_propriete" value="0"><i class="fas fa-plus-square"></i> Ajouter</button>
						<button class="btn btn-default refresh_appartement" value="0"><i class="fas fa-sync-alt"></i></button>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-content" style="display: none" >
			<div class="row upload">
				<div class="col_4-inline">
					<h3>Images</h3>
				</div>

				<div class="col_8-inline" style="text-align: right; padding-top: 10px">
					<button class="btn btn-orange upload_btn" style="position: relative; overflow: hidden">
					<i class="fas fa-upload"></i> Choisir
					<input type="file" id="upload_file_contrat" data="<?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?>" class="" name="image" capture style="position: absolute; z-index: 9999; top: 0; left: 0; background-color: aqua; padding: 10px 0; opacity: 0">
					</button>	
					<button class="btn btn-blue show_files contrat" value="<?= ($action === "edit")? $data["UID"] : substr($formToken,0,8) ?>"> Actualiser </button>					
				</div>

				<div class="col_12">
					<div id="progress" class="progress hide"><div id="progress-bar" class="progress-bar"></div></div>
				</div>
			</div>

			<div class="show_files_result"></div>
		</div>
		


	</div>	<!-- END PANEL CONTENT -->

</div>

<div class="debug"></div>

