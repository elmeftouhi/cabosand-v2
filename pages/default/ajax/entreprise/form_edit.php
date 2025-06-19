<?php session_start(); $core = $_SESSION['CORE']; 

$table_name = $_POST["page"];
require_once($core.$table_name.".php");
$ob = new $table_name();
$ob->id = $_POST["id"];
$data = $ob->read()[0];

$formToken=uniqid();
$return_page = "Entreprise";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Fiche Société
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
			<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
				<div class="col_12-inline">
					<h3 style="margin-left: 6px">Société</h3>
					<input type="hidden" id="id" value="<?= $data["id"] ?>">
				</div>
			</div>
			
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_9">
					<label for="raison_social">Raison Social </label>
					<input type="text" placeholder="Nom de la société" id="raison_social" value="<?= $data["raison_social"] ?>">
				</div>
				<div class="col_3">
					<label for="forme_juridique">Forme Juridique </label>
					<select id="forme_juridique">
						<option selected value="-1"></option>
							<?php require_once($core."Entreprise_Forme_Juridique.php"); 
								foreach( $entreprise_forme_juridique->fetchAll() as $k=>$v){
							?>	
						<option <?= ($v["id"]==$data["id_forme_juridique"])? "selected":"" ?> value="<?= $v["id"] ?>"> <?= "[ " . $v["ABR"] . " ] " . $v["forme_juridique"] ?> </option>
							<?php } ?>
					</select>

				</div>				

			</div>	
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12">
					<label for="slogon">Slogon </label>
					<input type="text" placeholder="Slogon de la société" id="slogon" value="<?= $data["slogon"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_2">
					<label for="capital">Capital </label>
					<input type="number" placeholder="10 000,00" id="capital" value="<?= $data["capital"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
				<div class="col_12-inline">
					<h3 style="margin-left: 6px">Contact</h3>				
				</div>
			</div>
			
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_12">
					<label for="adresse">Siège Social </label>
					<input type="text" placeholder="Adresse de la société" id="adresse" value="<?= $data["adresse"] ?>">
				</div>			

			</div>

			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="telephone_1">Téléphone (1) </label>
					<input type="text" placeholder="+212661098984" id="telephone_1" value="<?= $data["telephone_1"] ?>">
				</div>			
				<div class="col_6-inline">
					<label for="telephone_2">Téléphone (2) </label>
					<input type="text" placeholder="+212661098984" id="telephone_2" value="<?= $data["telephone_2"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="fax_1">Fax (1) </label>
					<input type="text" placeholder="+212539565251" id="fax_1" value="<?= $data["fax_1"] ?>">
				</div>			
				<div class="col_6-inline">
					<label for="fax_2">Fax (2) </label>
					<input type="text" placeholder="+212539565251" id="fax_2" value="<?= $data["fax_2"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="email">E-Mail </label>
					<input type="text" placeholder="exemple@email.com" id="email" value="<?= $data["email"] ?>">
				</div>			
				<div class="col_6-inline">
					<label for="site_internet">Site Web </label>
					<input type="text" placeholder="http://" id="site_internet" value="<?= $data["site_internet"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
				<div class="col_12-inline">
					<h3 style="margin-left: 6px">Fiscalité</h3>					
				</div>
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<div class="col_6-inline">
					<label for="ice">ICE </label>
					<input type="text" placeholder="123456" id="ice" value="<?= $data["ice"] ?>">
				</div>			
				<div class="col_6-inline">
					<label for="registre_commerce">Registre Commerce </label>
					<input type="text" placeholder="123456" id="registre_commerce" value="<?= $data["registre_commerce"] ?>">
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px">
				<div class="col_4-inline">
					<label for="patente">Patente </label>
					<input type="text" placeholder="123456" id="patente" value="<?= $data["patente"] ?>">
				</div>			
				<div class="col_4-inline">
					<label for="cnss">CNSS </label>
					<input type="text" placeholder="123456" id="cnss" value="<?= $data["cnss"] ?>">
				</div>
				<div class="col_4-inline">
					<label for="identification_fiscale">Identification Fiscale </label>
					<input type="text" placeholder="123456" id="identification_fiscale" value="<?= $data["identification_fiscale"] ?>">
				</div>
			</div>
			
			<div class="row" style="margin-bottom: 20px">
				<span style="padding-left: 10px; font-size:14px">Par Défaut</span>
				<div class="col_12-inline">
					<div class="on_off <?= ($data["is_default"] == "1")? "on":"off" ?>" id="is_default"> </div>
				</div>	
			</div>					
			<div class="row" style="margin-bottom: 20px">
				<span style="padding-left: 10px; font-size:14px">Status</span>
				<div class="col_12-inline">
					<div class="on_off <?= ($data["status"] == "1")? "on":"off" ?>" id="status"></div>
				</div>	
			</div>
			
			<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
				<div class="col_12-inline">
					<h3 style="margin-left: 6px">NOTES</h3>					
				</div>
			</div>
			<div class="row" style="margin-bottom: 20px;">
				<div class="col_12">
					<textarea id="notes" style="max-width: 100%; height: 150px"><?= stripslashes($data["notes"]) ?></textarea>					
				</div>

			</div>

		</div>		

	</div>


</div>


<div class="debug_client"></div>

