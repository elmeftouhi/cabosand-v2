<?php session_start(); $core = $_SESSION['CORE']; 

$formToken = md5( uniqid('auth', true) ); 
$return_page = "Person";
?>
<div class="row page_title">
	<div class="col_6-inline icon">
		<i class="fas fa-address-card"></i> Utilisateur
		<input type="hidden" id="UID" value="<?= substr($formToken,0,8); ?>">
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
			<a href=""><i class="far fa-clock"></i> Droit</a>
			<a  href=""><i class="fas fa-images"></i> Activités</a>
		</div>
	</div>
	<div class="panel-content" style="padding: 0px">
		<div class="tab-content">
			<div class="row">
				<div class="col_4" style="background-color: rgba(102,100,100,1.00); padding: 10px 0">
					<button class="show_files person hide" value="<?= substr($formToken,0,8); ?>"></button>
					<div class="person_image" style="max-width: 250px; margin: 0 auto; height: auto; text-align: center">
						<div class="image" style="margin: 10px 0">
							<img src="http://<?= $_SESSION["HOST"] ?>templates/default/images/user.png" style="width: 100%; height: auto">
						</div>
						
						<div class="row person_actions">
							<div class="col_6-inline">
								<button class="btn btn-orange upload_btn" style="position: relative; overflow: hidden">
								<i class="fas fa-upload"></i> Choisir
								<input type="file" id="upload_file_person" data="<?= substr($formToken,0,8); ?>" class="" name="image" accept="image/*" capture style="position: absolute; top: 0; left: 0; background-color: aqua; padding: 10px 0; opacity: 0">
								</button>
							</div>
							<div class="col_6-inline" style="text-align: right">
								<button class="btn btn-red remove"><i class="fas fa-redo-alt"></i> Initialiser</button>
							</div>
							
							
							
						</div>						
					</div>
					<div id="progress" class="progress hide"><div id="progress-bar" class="progress-bar"></div></div>
				</div>
				
				
				
				<div class="col_8">
					<h3 style="margin-left: 6px">Fiche Utilisateur</h3>

					<div class="row" style="margin-bottom: 20px">
						<div class="col_6-inline">
							<label for="person_first_name">Prénom</label>
							<input type="text" id="person_first_name" placeholder="Prénom">
						</div>				
						<div class="col_6-inline">
							<label for="person_last_name">Nom</label>
							<input type="text" id="person_last_name" placeholder="Nom">
						</div>
					</div>
					<div class="row" style="margin-bottom: 20px">
						<div class="col_6-inline">
							<label for="person_profile">Profile</label>
							<select id="person_profile">
								<option selected value="-1"></option>
									<?php require_once($core."Person_Profile.php"); 
										foreach( $person_profile->fetchAll() as $k=>$v){
									?>	
								<option <?= ($v["is_default"]==1)? "selected":"" ?> value="<?= $v["id"] ?>"> <?= $v["person_profile"] ?> </option>
									<?php } ?>
							</select>
						</div>
					</div>	


					<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
						<div class="col_8-inline">
							<h3 style="margin-left: 6px">CONTACT</h3>					
						</div>
					</div>

					<div class="row" style="margin-bottom: 20px;">
						<div class="col_6-inline">
							<label for="person_telephone">Téléphone </label>
							<input type="text" placeholder="Téléphone" id="person_telephone" value="">
						</div>	
						<div class="col_6-inline">
							<label for="person_email">E-Mail </label>
							<input type="text" placeholder="E-Mail" id="person_email">
						</div>	

					</div>	

					<div class="row">
						<div class="col_12">
							<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
								<div class="col_12-inline">
									<h3 style="margin-left: 6px">CONNEXION</h3>					
								</div>
							</div>						
						</div>
					</div>
					<div class="row" style="margin-bottom: 20px;">
						<div class="col_6-inline">
							<label for="person_login">Login </label>
							<input type="text" placeholder="Login" id="person_login" value="">
						</div>	
						<div class="col_6-inline">
							<label for="person_password">Password </label>
							<input type="text" placeholder="Login" id="person_password" value="">
						</div>		
					</div>
					
					<div class="row" style="margin-bottom: 20px">
						<div class="col_6-inline">
							<div class="" style="position: relative; width: 125px">
								<div class="on_off off" id="person_status"></div>
								<span style="position: absolute; right: 0; top: 10px; font-weight: bold; font-size: 12px">
									  Status
								</span>
							</div>
						</div>						
					</div>				
				</div> <!-- COL-8 -->
			</div> <!-- ROW-->
		</div>

	
		<div class="tab-content" style="display: none">
			<div class="location_form">
				<div class="row">
					<div class="col_4-inline">
						<h3 style="margin-left: 6px">Droits</h3>
					</div>
					<div class="col_8-inline" style="text-align: right; padding: 10px 5px">
						<button class="btn btn-default refresh_droit" value="0"><i class="fas fa-sync-alt"></i></button>
					</div>
				</div>
				<div class="location_form_content">
					<?php 
					require_once($core."Propriete_Proprietaire_Location.php"); 
					echo $propriete_proprietaire_location->drawTable("", array("conditions" => array("id_propriete=" => 0 )) );
					?>				
				</div>

			</div>
		</div>
	
		<div class="tab-content" style="display: none" >
			<div class="row upload">
				<div class="col_4-inline">
					<h3>Activités</h3>
				</div>
			</div>
		</div>
	</div>	<!-- END PANEL CONTENT -->

</div>

<div class="debug_client"></div>

