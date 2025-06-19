<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Client";
require_once($core.$table_name.".php");  
$ob = new $table_name();
?>

	<div class="row page_title">
		<div class="col_6-inline icon">
			<i class="fas fa-address-card"></i> Client
		</div>
		<div class="col_6-inline actions">
			<button class="btn btn-green add" value="<?= $table_name ?>"><i class="fas fa-plus" aria-hidden="true"></i></button>
			<button class="btn btn-default refresh" value="<?= $table_name ?>"><i class="fas fa-sync-alt"></i></button>
			<button class="btn btn-orange showSearchBar"><i class="fas fa-search-plus"></i></button>
		</div>
	</div>
	<hr>
	<div class="row searchBar hide" style="background-color: rgba(241,241,241,1.00); padding: 10px 0; margin: 10px 0px">
		<div class="col_6">
			
			<div class="input-group" style="overflow: hidden; margin-top: 10px">
				<input type="text" placeholder="Chercher" class="suf" name="" id="request">
				<div class="input-suf"><button title="Chercher" id="a_u_s" class="_propriete" data="_request"><i class="fa fa-search"></i></button></div>
			</div>

		</div>
		<div class="col_6">
			
			<div class="row _select" style="margin-top: 10px">
				<div class="col_4-inline">
					<select id="client_category" data="category">
						<option selected value="-1"> --  Catégorie  -- </option>
							<?php require_once($core."Client_Category.php"); 
								foreach( $client_category->find("", array(), "") as $k=>$v){
							?>	
						<option value="<?= $v["id"] ?>"> <?= $v["client_category"] ?> </option>
							<?php } ?>
					</select>
				</div>
				<div class="col_4-inline">
					<select id="client_type" data="type">
						<option selected value="-1"> --  Type  -- </option>
							<?php require_once($core."Client_Type.php"); 
								foreach( $client_type->find("", array(), "") as $k=>$v){
							?>	
						<option value="<?= $v["id"] ?>"> <?= $v["client_type"] ?> </option>
							<?php } ?>
					</select>
				</div>
				<div class="col_4-inline">
					<select id="client_status" data="status">
						<option selected value="-1"> --  Status  -- </option>
							<?php require_once($core."Client_Status.php"); 
								foreach( $client_status->find("", array(), "") as $k=>$v){
							?>	
						<option value="<?= $v["id"] ?>"> <?= $v["client_status"] ?> </option>
							<?php } ?>
					</select>
				</div>

			</div>



		</div>
		
		<div class="col_12 _choices" style="padding-top: 15px"></div>
			
	
	</div>
	
	<div class="row <?= strtolower($table_name) ?>">
		<?php
	$args = array(
		"p_p"		=>	(isset($_POST['data']['p_p']))? $_POST['data']['p_p'] : null,
		"sort_by"	=>	(isset($_POST['data']['sort_by']))? $_POST['data']['sort_by'] : "created",
		"current"	=>	(isset($_POST['data']['current']))? $_POST['data']['current'] : null,
		"style"	=>	(isset($_POST['data']['style']))? $_POST['data']['style'] : "list",
	);
		$args = ( isset($_SESSION["REQUEST"][$table_name]["args"]) )? $_SESSION["REQUEST"][$table_name]["args"]: $args;

	 //$ob->drawTable($args, null, "v_article") 
		 
	$ob->drawTable($args, null, "v_client"); 
	?>
	</div>
		
	<div class="debug_client"></div>
