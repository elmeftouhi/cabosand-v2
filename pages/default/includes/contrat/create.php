<?php
$core = $_SESSION["CORE"];
if(isset($contrat)){
	//var_dump($propriete);
	$token = $contrat["UID"];
}else{
	$token = md5( uniqid('auth', true) );
}

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter un Contrat</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="contrat" class="row" style="width: 780px">
			
			<!-- DETAILS -->
			<div class="col_8">
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Contrat Détails
					</div>					
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="date_contrat" type="date" value="<?= isset($contrat)? $contrat["date_contrat"]: date('Y-m-d'); ?>" class="field required">
							<input id="UID" type="hidden" value="<?= isset($contrat)? $contrat["UID"]: $token ?>" class="field required">
							<?= isset($contrat)? '<input type="hidden" id="id" value="'.$contrat["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for=""></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="status" type="checkbox" <?= isset($contrat)? $contrat["status"]==="1"? "checked" : "" : "checked"  ?>>
										<span class="slider round"></span>
									</label>
								</div>
								<div class="pt-5 pl-5"> Status</div>
							</div>
						</div>						
					</div>
					
				</div>	
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="id_societe">Société</label>
							<select id="id_societe" class="field">
									<?php  
											$selected = "";
											foreach( $societe as $k=>$v){
												if( isset($contrat) )
													if ($contrat["id_societe"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["raison_social"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="nbr_appartement">Nbr. App.</label>
							<input type="number" class="field required" id="nbr_appartement" value="<?= isset($contrat)? $contrat["nbr_appartement"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="montant">Montant</label>
							<input type="number" class="field required" id="montant" value="<?= isset($contrat)? $contrat["montant"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="nbr_nuite">Nuités</label>
							<input type="number" class="field required" id="nbr_nuite" value="<?= isset($contrat)? $contrat["nbr_nuite"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="nbr_periode">Périodes</label>
							<input type="number" class="field required" id="nbr_periode" value="<?= isset($contrat)? $contrat["nbr_periode"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Client
					</div>
					<div class="text-right">
						<button class="select_client"><i class="fas fa-ellipsis-h"></i></button>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="client_societe_name">Client</label>
							<input class="required field" type="text" id="client_societe_name" value="<?= isset($client)? $client["societe_name"]: "" ?>">
							<input type="hidden" class="<?= isset($client)? "required": "" ?> field" id="id_client" value="<?= isset($client)? $client["id"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="client_first_name">Prénom</label>
							<input type="text" class="field" id="client_first_name" value="<?= isset($client)? $client["first_name"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="client_last_name">Nom</label>
							<input type="text" class="field" id="client_last_name" value="<?= isset($client)? $client["last_name"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="client_cin">CIN</label>
							<input type="text" class="field" id="client_cin" value="<?= isset($client)? $client["cin"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="client_passport">Passport</label>
							<input type="text" class="field" id="client_passport" value="<?= isset($client)? $client["passport"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="client_ville">Ville</label>
							<input type="text" class="field" id="client_ville" value="<?= isset($client)? $client["ville"]: "" ?>">
						</div>						
					</div>
				</div>

				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Notes
					</div>	
					<div class="text-right">
				<?php
					if( isset($contrat) )
						echo '<button class="archive_note" data-module="contrat" data-id_module="' . $contrat["id"] . '"><i class="fas fa-plus"></i></button>';
				?>
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="notes">Notes</label>
							<textarea class="field archive" id="notes"><?= isset($contrat)? $contrat["notes"]: "" ?></textarea>
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
							<button class="reload-files hide" data-container="files-container" data-controler="Contrat" data-function="GetFilesAsList" data-folder="contrat" data-uid="<?= $token ?>">
								<i class="fas fa-sync-alt"></i>
							</button>
							<input class="hide" type="file" id="upload" data-uid="<?= $token ?>" data-folder="propriete" data-is_unique="0">
							<div class="progress hide">
								<div style="width:0%" class="progress-bar progress-value">0%</div>
							</div>
						</div>
						<div class="files-container" style="background-color: darkred">
							<?= $Obj->GetFilesAsList(['folder'=>'contrat', 'UID'=>$token]) ?>
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
								echo $notes->ShortTable($notess, $contrat["id"], 'contrat');
								
							}
						?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Contrat">Enregistrer</button></li>
			<?php if(isset($contrat)) { ?>
			<li><button class="delete red" data-controler="Contrat" value="<?= $contrat["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
