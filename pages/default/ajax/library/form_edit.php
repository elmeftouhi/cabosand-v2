<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Produit";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Produit
	</div>
	<div class="col_6-inline actions <?= strtolower($return_page) ?>">
		<button class="btn btn-green save_edit" value="<?= $return_page ?>"><i class="fas fa-save"></i></button>
		<button class="btn btn-default close" value="<?= $return_page ?>"><i class="fas fa-times"></i></button>
	</div>
</div>
<hr>

<div class="panel">
	<div class="panel-header">

		<div class="panel-header-tab ">
			<a href="" class="active"><i class="fas fa-file-invoice"></i> Détails</a>
			<a  href=""><i class="fas fa-images"></i> Images</a>
		</div>

	</div>
	<div class="panel-content">
		<div class="tab-content">

			<div class="produit_form">

				<h3 style="margin-left: 6px">Fiche produit</h3>
				<input type="hidden" value="<?= $data["UID"]  ?>" id="UID">
				<input type="hidden" value="<?= $data["id"]  ?>" id="id">
				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<label for="produit_category">Catégorie</label>

						<select id="produit_category">
							<?php
							require_once($core."Produit_Category.php");

							foreach($produit_category->fetchAll() as $k=>$v){
								if($data["id_produit_category"] == $v["id"]){
									echo "<option selected value='" . $v["id"] . "'> " . $v["produit_category"] ."</option>";
								}else{
									echo "<option value='" . $v["id"] . "'> " . $v["produit_category"] ."</option>";
								}
								
							}

							?>								
						</select>
					</div>
				</div>			

				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<label for="produit_title">Titre / Code</label>
						<input type="text" placeholder="Code du produit" id="produit_title" value="<?= $data["title"] ?>">
					</div>
				</div>	

				<div class="row" style="margin-bottom: 20px">
					<div class="col_12-inline">
						<label for="produit_description">Déscription</label>
						<textarea id="produit_description" style="max-width: 100%; height: 170px"><?= $data["description"] ?></textarea>
					</div>			

				</div>

				<div class="row" style="margin-bottom: 20px">
					<div class="col_6-inline">
						<div class="on_off <?= ($data["status"] == 1)? "on" : "off" ?>" id="produit_status"></div>
					</div>						
				</div>


			</div>		
		</div>

		<div class="tab-content" style="display: none">

			<div class="row upload">
				<div class="col_4-inline">
					<h3>Images</h3>
				</div>

				<div class="col_8-inline" style="text-align: right; padding-top: 10px">
					<button class="btn btn-orange upload_btn" style="position: relative; overflow: hidden">
					<i class="fas fa-upload"></i> Choisir
					<input type="file" id="upload_file" class="" name="image" accept="image/*" capture style="opacity: 0; position: absolute; top: 0; left: 0; background-color: aqua; padding: 10px 0">
					</button>	
					<button class="btn btn-blue show_files" value="<?=  $data["UID"]  ?>"> Actualiser </button>					
				</div>

				<div class="col_12">
					<div id="progress" class="progress hide"><div id="progress-bar" class="progress-bar"></div></div>
				</div>
			</div>

			<div class="show_files_result"></div>	

		</div>

	</div>


</div>

<div class="debug_client"></div>

