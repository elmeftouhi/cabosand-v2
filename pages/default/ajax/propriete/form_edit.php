<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
//$ob->id = $_POST["id"];
$data = $ob->find("",array("conditions"=>array("id="=>$_POST["id"])),"v_propriete")[0];

$formToken=uniqid();
$return_page = "Propriete";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Appartement <span style="font-size: 12px; color: #B8B7B7"><?= $data["UID"] ?></span>
		<input type="hidden" id="UID" value="<?= $data["UID"] ?>">
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i> Enregistrer</button>
		<button class="btn btn-red close_form" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header" style="padding: 0px">
		<div class="panel-header-tab ">
			<a href="" class="active"><i class="fas fa-file-invoice"></i> Détails</a>
			<a href=""><i class="far fa-clock"></i> Contrat</a>
			<a  href=""><i class="fas fa-images"></i> Images</a>
		</div>
	</div>
	<div class="panel-content" style="padding: 0px">
		<div class="tab-content">
			<div class="propriete_form">

				<h3 style="margin-left: 6px">Appartement</h3>
				<input type="hidden" id="id" value="<?= $data["id"] ?>">
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_8-inline">
						<label for="propriete_complexe">Complexe <span class="is_doing hide"><i class="fas fa-sync fa-spin"></i> ... </span>  </label>
						<select id="propriete_complexe">
								<?php require_once($core."Complexe.php"); 
									foreach( $complexe->find("", array("conditions" => array("status="=>1) ), "") as $k=>$v){
								?>	
							<option <?= ($data["id_complexe"] == $v["id"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["name"] ?> </option>
								<?php } ?>
						</select>

					</div>	
					<div class="col_4-inline">
						<label for="propriete_code"> <span style="color: green" class="propriete_code_correct hide"><i class="fas fa-check"></i> </span>Code <span class="propriete_code_loding hide" style="color: red"><i class="fas fa-sync fa-spin"></i> Vérification...</span></label>
						<input type="text" placeholder="Code" id="propriete_code" data-code="<?= $data["code"] ?>" value="<?= $data["code"] ?>" style="background-color: #ededed; font-weight: bold">
					</div>				

				</div>
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_4-inline">
						<label for="propriete_category">Catégorie</label>
						<select id="propriete_category">
								<?php require_once($core."Propriete_Category.php"); 
									foreach( $propriete_category->fetchAll() as $k=>$v){
								?>	
							<option <?= ($v["id"]==$data["id_propriete_category"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_category"] ?> </option>
								<?php } ?>
						</select>
					</div>				
					<div class="col_4-inline">
						<label for="propriete_type">Type</label>
						<select id="propriete_type">
								<?php require_once($core."Propriete_Type.php"); 
									foreach( $propriete_type->fetchAll() as $k=>$v){
								?>	
							<option <?= ($v["id"]==$data["id_propriete_type"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_type"] ?> </option>
								<?php } ?>
						</select>
					</div>
					<div class="col_4-inline">
						<label for="propriete_status">Status</label>
						<select id="propriete_status">
								<?php require_once($core."Propriete_Status.php"); 
									foreach( $propriete_status->fetchAll() as $k=>$v){
								?>	
							<option <?= ($v["id"]==$data["id_propriete_status"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_status"] ?> </option>
								<?php } ?>
						</select>
					</div>
				</div>	
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_3-inline">
						<label for="propriete_zone">Zone </label>
						<input type="text" placeholder="0" id="propriete_zone" value="<?= $data["zone_number"] ?>">
					</div>				
					<div class="col_3-inline">
						<label for="propriete_bloc">Bloc </label>
						<input type="text" placeholder="0" id="propriete_bloc" value="<?= $data["bloc_number"] ?>">
					</div>
					<div class="col_3-inline">
						<label for="propriete_numero">N° </label>
						<input type="number" placeholder="0" id="propriete_numero" value="<?= $data["appartement_number"] ?>">
					</div>
					<div class="col_3-inline">
						<label for="propriete_etage">Etage </label>
						<input type="text" placeholder="0" id="propriete_etage" value="<?= $data["etage_number"] ?>">
					</div>
				</div>

				<div class="row" style="margin-bottom: 20px">
					<div class="col_3-inline">
						<label for="propriete_surface">Surfaçe </label>
						<input type="number" placeholder="0" id="propriete_surface" value="<?= $data["surface"] ?>">
					</div>				
					<div class="col_3-inline">
						<label for="propriete_chambre">Chambre </label>
						<input type="number" placeholder="0" id="propriete_chambre" value="<?= $data["nbr_chambre"] ?>">
					</div>
					<div class="col_3-inline">
						<label for="propriete_max_person">Persone </label>
						<input type="number" placeholder="0" id="propriete_max_person" value="<?= $data["maximum_person"] ?>">
					</div>
				</div>
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<label for="propriete_ville">Ville </label>
						<input type="text" placeholder="Ville" id="propriete_ville" value="<?= $data["ville"] ?>">
					</div>	
					<div class="col_6-inline">
						<label for="propriete_adresse">Adresse </label>
						<input type="text" placeholder="Adresse" id="propriete_adresse" value="<?= $data["adresse"] ?>">
					</div>	

				</div>	
				
				<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
					<div class="col_8-inline">
						<h3 style="margin-left: 6px">PROPRIETAIRE</h3>					
					</div>
					<div class="col_4-inline" style="text-align: right">
						<button class="btn btn-default select_proprietaire"><i class="fas fa-list-alt"></i> Select</button>				
					</div>
				</div>
				
				<div class="row" style="margin-bottom: 20px">
					<div class="col_12">
						<label for="propriete_ville">Nom et Prénom </label>
						<input type="text" placeholder="Ville" id="propriete_proprietaire_name" value="<?= $data["proprietaire"] ?>" disabled  style="background-color: #ededed; font-weight: bold">
						<input type="hidden" id="propriete_proprietaire_id" value="<?= $data["id_proprietaire"] ?>">
					</div>	

				</div>	
				
				<div class="row" style="margin-bottom: 20px;">
					<div class="col_6-inline">
						<label for="propriete_ville">Ville </label>
						<input type="text" placeholder="Ville" id="propriete_proprietaire_ville" value="<?= $data["proprietaire_ville"] ?>" disabled style="background-color: #ededed; font-weight: bold">
					</div>	
					<div class="col_6-inline">
						<label for="propriete_adresse">Adresse </label>
						<input type="text" placeholder="Adresse" id="propriete_proprietaire_adresse" value="<?= $data["proprietaire_adresse"] ?>" disabled style="background-color: #ededed; font-weight: bold">
					</div>	

				</div>	

				<div class="row">
					<div class="col_6">
						<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
							<div class="col_12-inline">
								<h3 style="margin-left: 6px">APPARTEMENT OPTIONS</h3>					
							</div>
						</div>
						<div class="row" style="margin-bottom: 20px;">
						<?php 
						require_once($core."Propriete_Options.php");
						$options = $ob->find("",array("conditions"=>array("id_propriete="=>$data["id"])),"options_in_propriete");
						$options = (is_null($options))? array() : $options;
						$allOptions = $propriete_options->fetchAll();	
						$isExist = false;
						foreach($allOptions as $k=>$v){
							$isExist = false;
							foreach($options as $kk=>$vv){
							if($v["id"] == $vv["id_propriete_options"]){
								$isExist = true;
							}

							}
							if($isExist){
								echo "<div class='col_12'><label><input class='propriete_options' checked type='checkbox' value='".$v['id']."'>".$v["propriete_options"]."</label></div>";
							}else{
								echo "<div class='col_12'><label><input class='propriete_options' type='checkbox' value='".$v['id']."'>".$v["propriete_options"]."</label></div>";
							}	


						}

						?>
						</div>						
					</div>
					<div class="col_6">
						<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
							<div class="col_12-inline">
								<h3 style="margin-left: 6px">PARAMETRES</h3>					
							</div>
						</div>

						<div class="row" style="margin-bottom: 20px;">
							<div class="col_12">
								<label for="propriete_isForSell">
									<input type="checkbox" id="propriete_isForSell" <?= ($data["is_for_sell"] == "1")? "checked":"" ?>> Disponible pour la vente
								</label>					
							</div>

						</div>	
						
						<div class="row" style="margin-bottom: 20px;">
							<div class="col_12">
								<label for="propriete_isForLocation">
									<input type="checkbox" id="propriete_isForLocation" <?= ($data["is_for_location"] == "1")? "checked":"" ?>> Disponible pour la location
								</label>					
							</div>

						</div>
						
					</div>
				</div>


				<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
					<div class="col_8-inline">
						<h3 style="margin-left: 6px">Notes</h3>					
					</div>
					<div class="col_4-inline" style="text-align: right">
						<button class="btn btn-green add_note" data-module="propriete", data-id_module="<?= $data["id"] ?>"><i class="fas fa-list-alt"></i> Ajouter</button>
						<button class="btn btn-default refresh_note hide" data-module="propriete", data-id_module="<?= $data["id"] ?>"><i class="fas fa-list-alt"></i> ref</button>				
					</div>
				</div>
				
				<div class="row notes" style="margin-bottom: 20px;">
					
					<?php
						require_once($core."Notes.php");
						echo $notes->Get_As_Table_By_Module("propriete", $data["id"]);
					?>
					
					<div class="col_12">
						<textarea id="propriete_notes" style="max-width: 100%; height: 150px"><?= $data["notes"] ?></textarea>					
					</div>

				</div>
		
			</div>
	</div>
	
	<div class="tab-content" style="display: none">
		<div class="location_form">
			<div class="row">
				<div class="col_4-inline">
					<h3 style="margin-left: 6px">Contrat</h3>
				</div>
				<div class="col_8-inline" style="text-align: right; padding: 10px 5px">
					<button class="btn btn-green add_location" value="<?= $data["id"] ?>"><i class="fas fa-plus-square"></i> Ajouter</button>
					<button class="btn btn-default refresh_location" value="<?= $data["id"] ?>"><i class="fas fa-sync-alt"></i></button>
				</div>
			</div>
			<div class="location_form_content">
				<?php 
				require_once($core."Propriete_Proprietaire_Location.php"); 
				echo $propriete_proprietaire_location->drawTable("", array("conditions" => array("id_propriete=" => $data["id"] )), "v_propriete_proprietaire_location" );
				?>				
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
				<input type="file" id="upload_file_propriete" data="<?= $data["UID"] ?>" class="" name="image" capture style="position: absolute; z-index: 9999; top: 0; left: 0; background-color: aqua; padding: 10px 0; opacity: 0">
				</button>	
				<button class="btn btn-blue show_files propriete" value="<?= $data["UID"] ?>"> Actualiser </button>					
			</div>

			<div class="col_12">
				<div id="progress" class="progress hide"><div id="progress-bar" class="progress-bar"></div></div>
			</div>
		</div>

		<div class="show_files_result"></div>
	</div>
	</div>	<!-- END PANEL CONTENT -->

</div>

<div class="debug_client"></div>

