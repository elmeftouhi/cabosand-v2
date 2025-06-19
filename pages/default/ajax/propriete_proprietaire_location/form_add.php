<?php session_start(); $core = $_SESSION['CORE']; 

$formToken=uniqid();
$return_page = "Propriete";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Appartement
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header" style="padding: 0px">
		<div class="panel-header-tab ">
			<a href="" class="active"><i class="fas fa-file-invoice"></i> Détails</a>
			<a href=""><i class="far fa-clock"></i> Location</a>
			<a  href=""><i class="fas fa-images"></i> Images</a>
		</div>
	</div>
	<div class="panel-content" style="padding: 0px">
		<div class="tab-content">
			<div class="propriete_form">

				<h3 style="margin-left: 6px">Appartement</h3>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_8-inline">
					<label for="propriete_complexe">Complexe <span class="is_doing hide"><i class="fas fa-sync fa-spin"></i> ... </span>  </label>
					<select id="propriete_complexe">
						<option selected value="-1"></option>
							<?php require_once($core."Complexe.php"); 
								foreach( $complexe->find("", array("conditions" => array("status="=>1) ), "") as $k=>$v){
							?>	
						<option value="<?= $v["id"] ?>"> <?= $v["name"] ?> </option>
							<?php } ?>
					</select>

				</div>	
				<div class="col_4-inline">
					<label for="propriete_code">Code </label>
					<input type="text" placeholder="Code" id="propriete_code" value="" style="background-color: #ededed; font-weight: bold">
				</div>				
				
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_4-inline">
					<label for="propriete_category">Catégorie</label>
					<select id="propriete_category">
						<option selected value="-1"></option>
							<?php require_once($core."Propriete_Category.php"); 
								foreach( $propriete_category->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["is_default"]==1)? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_category"] ?> </option>
							<?php } ?>
					</select>
				</div>				
				<div class="col_4-inline">
					<label for="propriete_type">Type</label>
					<select id="propriete_type">
						<option selected value="-1"></option>
							<?php require_once($core."Propriete_Type.php"); 
								foreach( $propriete_type->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["is_default"]==1)? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_type"] ?> </option>
							<?php } ?>
					</select>
				</div>
				<div class="col_4-inline">
					<label for="propriete_type">Status</label>
					<select id="propriete_type">
						<option selected value="-1"></option>
							<?php require_once($core."Propriete_Status.php"); 
								foreach( $propriete_status->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["is_default"]==1)? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["propriete_status"] ?> </option>
							<?php } ?>
					</select>
				</div>
			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_3-inline">
					<label for="propriete_zone">Zone </label>
					<input type="text" placeholder="0" id="propriete_zone" value="0">
				</div>				
				<div class="col_3-inline">
					<label for="propriete_bloc">Bloc </label>
					<input type="text" placeholder="0" id="propriete_bloc" value="0">
				</div>
				<div class="col_3-inline">
					<label for="propriete_numero">N° </label>
					<input type="text" placeholder="0" id="propriete_numero" value="0">
				</div>
				<div class="col_3-inline">
					<label for="propriete_etage">Etage </label>
					<input type="text" placeholder="0" id="propriete_etage" value="0">
				</div>
			</div>

			<div class="row" style="margin-bottom: 20px">
				<div class="col_3-inline">
					<label for="propriete_surface">Surfaçe </label>
					<input type="text" placeholder="0" id="propriete_surface" value="0">
				</div>				
				<div class="col_3-inline">
					<label for="propriete_chambre">Chambre </label>
					<input type="text" placeholder="0" id="propriete_chambre" value="2">
				</div>
				<div class="col_3-inline">
					<label for="propriete_max_person">Persone </label>
					<input type="text" placeholder="0" id="propriete_max_person" value="4">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="propriete_ville">Ville </label>
					<input type="text" placeholder="Ville" id="propriete_ville" value="">
				</div>	
				<div class="col_6-inline">
					<label for="propriete_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="propriete_adresse" value="">
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
					<input type="text" placeholder="Ville" id="propriete_proprietaire_name" value="" disabled  style="background-color: #ededed; font-weight: bold">
					<input type="hidden" id="propriete_proprietaire_id" value="">
				</div>	

			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="propriete_ville">Ville </label>
					<input type="text" placeholder="Ville" id="propriete_proprietaire_ville" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>	
				<div class="col_6-inline">
					<label for="propriete_adresse">Adresse </label>
					<input type="text" placeholder="Adresse" id="propriete_proprietaire_adresse" value="" disabled style="background-color: #ededed; font-weight: bold">
				</div>	

			</div>	
	
			<div class="row" style="margin-bottom: 20px">
				<?php 
				require_once($core."Propriete_Options.php");
				foreach($propriete_options->fetchAll() as $k=>$v){
					echo "<div class='col_12'><label><input type='checkbox' value='".$v['id']."'>".$v["propriete_options"]."</label></div>";
				}
				
				?>
			</div>			

		</div>
	</div>
	
	<div class="tab-content" style="display: none">
		<div class="location_form">
			<div class="row">
				<div class="col_4-inline">
					<h3 style="margin-left: 6px">Location</h3>
				</div>
				<div class="col_8-inline" style="text-align: right; padding: 10px 5px">
					<button class="btn btn-green add_location"><i class="fas fa-plus-square"></i> Ajouter</button>
				</div>
			</div>
			
			<?php 
			require_once($core."Propriete_Proprietaire_Location.php"); 
			echo $propriete_proprietaire_location->drawTable();
			?>
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
				<input type="file" id="upload_file" class="" name="image" accept="image/*" capture style="position: absolute; z-index: 9999; top: 0; left: 0; background-color: aqua; padding: 10px 0; opacity: 0">
				</button>	
				<button class="btn btn-blue show_files" value="<?= $formToken  ?>"> Actualiser </button>					
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

