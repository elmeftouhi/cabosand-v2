<?php
$core = $_SESSION["CORE"];
if(isset($client)){
	//var_dump($propriete);
	$token = $client["UID"]==="0"? md5( uniqid('auth', true) ): $client["UID"];
}else{
	$token = md5( uniqid('auth', true) );
}

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter un Client</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="client" class="row" style="width: 780px">
			
			<!-- DETAILS -->
			<div class="col_8">
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Client
					</div>	
					<div id="color" style="background-color: <?= isset($client)? $client["hex_string"]:"" ?>; padding:16px 16px; border-radius:50%"></div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="created" type="date" value="<?= isset($client)? explode(" ", $client["created"])[0]: date('Y-m-d'); ?>" class="">
							<input id="UID" type="hidden" value="<?= $token ?>" class="field required">
							<?= isset($client)? '<input type="hidden" id="id" value="'.$client["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for=""></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="status" type="checkbox" <?= isset($client)? $client["status"]==="1"? "checked" : "" : "checked"  ?>>
										<span class="slider round"></span>
									</label>
								</div>
								<div class="pt-5 pl-5"> Status</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="id_status">Status</label>
							<select id="id_status" class="field">
									<?php  
											$selected = "";
											foreach( $statuss as $k=>$v){
												if( isset($client) )
													if ($client["id_status"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["client_status"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="id_color">Couleur</label>
							<select id="id_color" class="field">
								<option value="0" selected></option>
									<?php  
											$selected = "";
											foreach( $colors as $k=>$v){
												if( isset($client) )
													if ($client["id_color"] === $v["color_id"]) $selected = "selected"; else $selected = "";
												else
													$selected = "";
									?>	
								<option style="color:white;background-color:<?= $v["hex_string"] ?>" data-hex="<?= $v["hex_string"] ?>" <?= $selected ?> value="<?= $v["color_id"] ?>"> <?= strtoupper( $v["name"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="id_category">Catégorie</label>
							<select id="id_category" class="required field">
									<?php  
											$selected = "";
											foreach( $categories as $k=>$v){
												if( isset($client) )
													if ($client["id_category"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["client_category"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="id_type">Type</label>
							<select id="id_type" class="required field">
									<?php  
											$selected = "";
											foreach( $type as $k=>$v){
												if( isset($client) )
													if ($client["id_type"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["client_type"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="first_name">Prénom</label>
							<input id="first_name" type="text" value="<?= isset($client)? $client["first_name"]: ""; ?>" class="field required">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="last_name">Nom</label>
							<input id="last_name" type="text" value="<?= isset($client)? $client["last_name"]: ""; ?>" class="field required">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="societe_name">Société</label>
							<input id="societe_name" type="text" value="<?= isset($client)? $client["societe_name"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_1">Télé. (1)</label>
							<input id="phone_1" type="text" value="<?= isset($client)? $client["phone_1"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_2">Télé. (2)</label>
							<input id="phone_2" type="text" value="<?= isset($client)? $client["phone_2"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="cin">N° CIN</label>
							<input id="cin" type="text" value="<?= isset($client)? $client["cin"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="passport">N° Passport</label>
							<input id="passport" type="text" value="<?= isset($client)? $client["passport"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
					
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="ville">Ville</label>
							<input id="ville" type="text" value="<?= isset($client)? $client["ville"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="email">E-Mail</label>
							<input id="email" type="text" value="<?= isset($client)? $client["email"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="adresse">Adresse</label>
							<input class="field" type="text" id="adresse" value="<?= isset($client)? $client["adresse"]: "" ?>">
						</div>						
					</div>
				</div>		
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Notes
					</div>	
					<div class="text-right">
						<?php
							if( isset($client) )
								echo '<button class="archive_note" data-module="client" data-id_module="' . $client["id"] . '"><i class="fas fa-plus"></i></button>';
						?>	
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="notes">Notes</label>
							<textarea class="field archive" id="notes"><?= isset($client)? $client["notes"]: "" ?></textarea>
						</div>	
					</div>
				</div>


				
			</div>
			
			<!-- FILES -->
			<div class="col_4">
				<div class="row">
					<div class="col_12">
						<div class="add_image text-right">
							<button class="upload_btn" data-target="upload"><i class="fas fa-folder-plus"></i></button>
							<button class="reload-files hide" data-container="files-container" data-controler="Client" data-function="GetFilesAsList" data-folder="client" data-uid="<?= $token ?>">
								<i class="fas fa-sync-alt"></i>
							</button>
							<input class="hide" type="file" id="upload" data-uid="<?= $token ?>" data-folder="client" data-is_unique="0">
							<div class="progress hide">
								<div style="width:0%" class="progress-bar progress-value">0%</div>
							</div>
						</div>
						<div class="files-container" style="background-color: darkred">
							<?= $Obj->GetFilesAsList(['folder'=>'client', 'UID'=>$token]) ?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<?php 	
	
							if(isset($depenses)){
								require_once($core."Depense.php");
								echo $depense->ShortTable($depenses);
								
							}
							if(isset($notess)){
								require_once($core."Notes.php");
								echo $notes->ShortTable($notess, $client["id"], 'client');
								
							}
						?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Client">Enregistrer</button></li>
			<?php if(isset($client)) { ?>
			<li><button class="delete red" data-controler="Client" value="<?= $client["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
