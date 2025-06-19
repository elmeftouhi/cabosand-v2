<?php
$core = $_SESSION["CORE"];
if(isset($proprietaire)){
	//var_dump($propriete);
	$token = $proprietaire["UID"]==="0"? md5( uniqid('auth', true) ): $proprietaire["UID"];
}else{
	$token = md5( uniqid('auth', true) );
}

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter un Propriétaire</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="proprietaire" class="row" style="width: 780px">
			
			<!-- DETAILS -->
			<div class="col_8">
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Propriétaire - [ <?= $token ?> ]
					</div>					
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="created" type="date" value="<?= isset($proprietaire)? explode(" ", $proprietaire["created"])[0]: date('Y-m-d'); ?>" class="">
							<input id="UID" type="hidden" value="<?= $token ?>" class="field required">
							<?= isset($proprietaire)? '<input type="hidden" id="id" value="'.$proprietaire["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for=""></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="status" type="checkbox" <?= isset($proprietaire)? $proprietaire["status"]==="1"? "checked" : "" : ""  ?>>
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
							<label for="name">Nom</label>
							<input id="name" type="text" value="<?= isset($proprietaire)? $proprietaire["name"]: ""; ?>" class="required field">
						</div>						
					</div>
					
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="cin">N° CIN</label>
							<input id="cin" type="text" value="<?= isset($proprietaire)? $proprietaire["cin"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="passport">N° Passport</label>
							<input id="passport" type="text" value="<?= isset($proprietaire)? $proprietaire["passport"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_1">Télé. (1)</label>
							<input id="phone_1" type="text" value="<?= isset($proprietaire)? $proprietaire["phone_1"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_2">Télé. (2)</label>
							<input id="phone_2" type="text" value="<?= isset($proprietaire)? $proprietaire["phone_2"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
					
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="ville">Ville</label>
							<input id="ville" type="text" value="<?= isset($proprietaire)? $proprietaire["ville"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="email">E-Mail</label>
							<input id="email" type="text" value="<?= isset($proprietaire)? $proprietaire["email"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="adresse">Adresse</label>
							<input class="field" type="text" id="adresse" value="<?= isset($proprietaire)? $proprietaire["adresse"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="agence_1">Agence (1)</label>
							<input id="agence_1" type="text" value="<?= isset($proprietaire)? $proprietaire["agence_1"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="rib_1">RIB</label>
							<input id="rib_1" type="text" value="<?= isset($proprietaire)? $proprietaire["rib_1"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row mb-15">
					<div class="col_6">
						<div class="form-element inline">
							<label for="agence_2">Agence (2)</label>
							<input id="agence_2" type="text" value="<?= isset($proprietaire)? $proprietaire["agence_2"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="rib_2">RIB</label>
							<input id="rib_2" type="text" value="<?= isset($proprietaire)? $proprietaire["rib_2"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>		
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Notes
					</div>	
					<div class="text-right">
						<?php
							if( isset($proprietaire) )
								echo '<button class="archive_note" data-module="proprietaire" data-id_module="' . $proprietaire["id"] . '"><i class="fas fa-plus"></i></button>';
						?>	
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="notes">Notes</label>
							<textarea class="field archive" id="notes"><?= isset($proprietaire)? $proprietaire["notes"]: "" ?></textarea>
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
							<button class="reload-files hide" data-container="files-container" data-controler="Proprietaire" data-function="GetFilesAsList" data-folder="proprietaire" data-uid="<?= $token ?>">
								<i class="fas fa-sync-alt"></i>
							</button>
							<input class="hide" type="file" id="upload" data-uid="<?= $token ?>" data-folder="proprietaire" data-is_unique="0">
							<div class="progress hide">
								<div style="width:0%" class="progress-bar progress-value">0%</div>
							</div>
						</div>
						<div class="files-container" style="background-color: darkred">
							<?= $Obj->GetFilesAsList(['folder'=>'proprietaire', 'UID'=>$token]) ?>
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
								echo $notes->ShortTable($notess, $proprietaire["id"], 'proprietaire');
								
							}
						?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Proprietaire">Enregistrer</button></li>
			<?php if(isset($proprietaire)) { ?>
			<li><button class="delete red" data-controler="Proprietaire" value="<?= $proprietaire["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
