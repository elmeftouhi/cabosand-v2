<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Propriete";
require_once($core.$table_name.".php");  
$ob = new $table_name();
?>
<style>
	

</style>

<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Propriété(s)
	</div>
	<div class="col_6-inline actions">
		<button class="btn btn-green add_" value="<?= $table_name ?>"><i class="fas fa-plus" aria-hidden="true"></i></button>
		<button class="btn btn-default refresh_propriete" value="<?= $table_name ?>"><i class="fas fa-sync-alt"></i></button>
	</div>
</div>
<hr>

<!-- Search -->
<div style="display:flex; overflow:auto">

	<!-- Search -->
	<div style="width:15rem; min-width:15rem;" class="search">
		<div class="titre"><i class="fas fa-search"></i> Chercher</div>
		<div class="options">
			
			<div class="form-element">
				<input type="text" id="request" class="r">
			</div>
			
			<div class="form-element">
				<label for="id_propriete_complexe">Complexe</label>
				<select id="id_propriete_complexe" class="r">
					<option selected value="-1"> --  Complexe  -- </option>
						<?php require_once($core."Complexe.php"); 
							foreach( $complexe->find("", array("conditions" => array("status="=>1), "order"=>"name" ), "v_complexe") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["name"] ?>  ( <?= $v["nbr_propriete"] ?> ) </option>
						<?php } ?>
				</select>
			</div>
			
			<div class="form-element">
				<label for="id_type">Type</label>
				<select id="id_type" class="r">
					<option selected value="-1"> --  Type  -- </option>
						<?php require_once($core."Propriete_Type.php"); 
							foreach( $complexe->find("", array(), "propriete_type") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["propriete_type"] ?> </option>
						<?php } ?>
				</select>
			</div>
			
			<div class="form-element">
				<label for="id_propriete_category">Catégorie</label>
				<select id="id_propriete_category" class="r">
					<option selected value="-1"> --  Catégorie  -- </option>
						<?php require_once($core."Propriete_Category.php"); 
							foreach( $complexe->find("", array(), "propriete_category") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["propriete_category"] ?> </option>
						<?php } ?>
				</select>
			</div>
			
			<div class="form-element">
				<label for="id_status">Status</label>
				<select id="id_status" class="r">
					<option selected value="-1"> --  Status  -- </option>
						<?php require_once($core."Propriete_Status.php"); 
							foreach( $complexe->find("", array(), "propriete_status") as $k=>$v){
						?>	
					<option value="<?= $v["id"] ?>"> <?= $v["propriete_status"] ?> </option>
						<?php } ?>
				</select>
			</div>
			
			
			<div class="form-element">
				<label for="is_for_location">Location</label>
				<select id="is_for_location" class="r">
					<option selected value="-1"> --  Tout type  -- </option>
					<option value="1">Disponible pour la location</option>
					<option value="0">Seul pour les sociétés</option>
				</select>
			</div>
			
			<hr>
			
			<div class="form-element" style="text-align: center">
				<button class="btn btn-orange start">Chercher</button>
			</div>
			
		</div>
	</div>
	
	<!-- Content -->
	<div style="flex:auto">
	
		<div class="row <?= $table_name ?>">
			<?php 

		$args = array(
			"p_p"		=>	20,
			"sort_by"	=>	"created ASC",
			"current"	=>	0,
			"style"	=>	(isset($_POST['data']['style']))? $_POST['data']['style'] : "list",
		);

		//$args = ( isset($_SESSION["REQUEST"][$table_name]["args"]) )? $_SESSION["REQUEST"][$table_name]["args"]: $args;

		$ob->drawTable($args, null, "v_propriete"); 

			?>		
		</div>
		<div class="debug"></div>
	</div>
</div>