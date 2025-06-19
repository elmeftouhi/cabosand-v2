<?php
$core = $_SESSION["CORE"];
if(isset($propriete)){
	//var_dump($propriete);
	$token = $propriete["UID"];
}else{
	$token = substr(md5( uniqid('auth', true) ),0,8);
}

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter un Appartement</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="propriete" class="row" style="width: 780px">
			
			<!-- DETAILS -->
			<div class="col_8">
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Appartement Détails
					</div>					
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="created" type="date" value="<?= isset($propriete)? explode(" ", $propriete["created"])[0]: date('Y-m-d'); ?>" class="field required">
							<input id="UID" type="hidden" value="<?= isset($propriete)? $propriete["UID"]: $token ?>" class="field required">
							<?= isset($propriete)? '<input type="hidden" id="id" value="'.$propriete["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for="propriete_status">Status</label>
							<select id="propriete_status" class="required field">
									<?php  
											$selected = "";
											foreach( $propriete_status as $k=>$v){
												if( isset($propriete) )
													if ($propriete["id_propriete_status"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["propriete_status"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="propriete_complexe">Complexe</label>
							<select id="propriete_complexe" class="required field">
									<?php  
											$selected = "";
											foreach( $complexe as $k=>$v){
												if( isset($propriete) )
													if ($propriete["id_complexe"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													$selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["name"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="propriete_code">
								<span style="color: green" class="propriete_code_correct hide">
									<i class="fas fa-check"></i> 
								</span> Code 
								<span class="propriete_code_loding hide" style="color: red">
									<i class="fas fa-sync fa-spin"></i> Vérification...
								</span>
							</label>
							<input type="text" class="field required" placeholder="Code" data-code="-1" id="propriete_code" value="<?= isset($propriete)? $propriete["code"]: "" ?>" style="background-color: #ededed; font-weight: bold">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="propriete_category">Catégorie</label>
							<select id="propriete_category" class="required field">
									<?php  
											$selected = "";
											foreach( $propriete_category as $k=>$v){
												if( isset($propriete) )
													if ($propriete["id_propriete_category"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["propriete_category"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for="propriete_type">Type</label>
							<select id="propriete_type" class="required field">
									<?php  
											$selected = "";
											foreach( $propriete_type as $k=>$v){
												if( isset($propriete) )
													if ($propriete["id_propriete_type"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["propriete_type"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
					
				</div>	
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="zone_number">Zone</label>
							<input class="field" type="text" id="zone_number" value="<?= isset($propriete)? $propriete["zone_number"]: "" ?>">
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for="bloc_number">Bloc</label>
							<input class="field" type="text" id="bloc_number" value="<?= isset($propriete)? $propriete["bloc_number"]: "" ?>">
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="appartement_number">Numéro</label>
							<input class="field"  type="number" id="appartement_number" value="<?= isset($propriete)? $propriete["appartement_number"]: "" ?>">
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for="etage_number">Etage</label>
							<input class="field"  type="text" id="etage_number" value="<?= isset($propriete)? $propriete["etage_number"]: "" ?>">
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="surface">Supérificie</label>
							<input class="field" type="number" id="surface" value="<?= isset($propriete)? $propriete["surface"]: "" ?>">
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for="nbr_chambre">Chambres</label>
							<input class="field" type="number" id="nbr_chambre" value="<?= isset($propriete)? $propriete["nbr_chambre"]: "" ?>">
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="maximum_person">Personnes</label>
							<input class="field" type="number" id="maximum_person" value="<?= isset($propriete)? $propriete["maximum_person"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Propriétaire
					</div>
					<div class="text-right">
						<button class="select_proprietaire"><i class="fas fa-ellipsis-h"></i></button>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline" style="position: relative">
							<label for="proprietaire_name">Propriétaire</label>
							<input class="required field" type="text" id="proprietaire_name" value="<?= isset($proprietaire)? $proprietaire["name"]: "" ?>">
							<div style="position: absolute; top: 0; right: 0px">
								<button class="remove_this_proprietaire_from_propriete"> <i class="fas fa-user-times"></i> </button>
							</div>
							<input type="hidden" class="field" id="id_proprietaire" value="<?= isset($proprietaire)? $proprietaire["id"]: "" ?>">
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="proprietaire_telephone">Téléphone 1</label>
							<input type="text" class="field" id="proprietaire_telephone" value="<?= isset($proprietaire)? $proprietaire["phone_1"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="proprietaire_telephone_2">Téléphone 2</label>
							<input type="text" class="field" id="proprietaire_telephone_2" value="<?= isset($proprietaire)? $proprietaire["phone_2"]: "" ?>">
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="proprietaire_email">Email</label>
							<input type="text" class="field" id="proprietaire_email" value="<?= isset($proprietaire)? $proprietaire["email"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="proprietaire_agence">Agence</label>
							<input type="text" class="field" id="proprietaire_agence" value="<?= isset($proprietaire)? $proprietaire["agence_1"]: "" ?>">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="proprietaire_rib">RIB</label>
							<input type="text" class="field" id="proprietaire_rib" value="<?= isset($proprietaire)? $proprietaire["rib_1"]: "" ?>">
						</div>						
					</div>
				</div>
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Notes
					</div>	
					<div class="text-right">
				<?php
					if( isset($propriete) )
						echo '<button class="archive_note" data-module="propriete" data-id_module="' . $propriete["id"] . '"><i class="fas fa-plus"></i></button>';
				?>
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="notes">Notes</label>
							<textarea class="field archive" id="notes"><?= isset($propriete)? $propriete["notes"]: "" ?></textarea>
						</div>	
					</div>
				</div>
				
				<div class="d-flex space-between pt-15 pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Paramêtres
					</div>					
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="proprietaire_rib"></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="is_for_sell" type="checkbox" <?= isset($propriete)? $propriete["is_for_sell"]==="1"? "checked" : "" : ""  ?>>
										<span class="slider round"></span>
									</label>
								</div>
								<div class="pt-5 pl-5"> Disponible pour la vente</div>
							</div>							
							
						</div>	
						
						<div class="form-element inline">
							<label for="proprietaire_rib"></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="is_for_location" type="checkbox" <?= isset($propriete)? $propriete["is_for_location"]==="1"? "checked" : "" : ""  ?>>
										<span class="slider round"></span>
									</label>
								</div>
								<div class="pt-5 pl-5"> Disponible pour la location</div>
							</div>							
							
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
							<button class="reload-files hide" data-container="files-container" data-controler="Propriete" data-function="GetFilesAsList" data-folder="propriete" data-uid="<?= $token ?>">
								<i class="fas fa-sync-alt"></i>
							</button>
							<input class="hide" type="file" id="upload" data-uid="<?= $token ?>" data-folder="propriete" data-is_unique="0">
							<div class="progress hide">
								<div style="width:0%" class="progress-bar progress-value">0%</div>
							</div>
						</div>
						<div class="files-container" style="background-color: darkred">
							<?= $Obj->GetFilesAsList(['folder'=>'propriete', 'UID'=>$token]) ?>
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
								echo $notes->ShortTable($notess, $propriete["id"], 'propriete');
								
							}
						?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Propriete">Enregistrer</button></li>
			<?php if(isset($propriete)) { ?>
			<li><button class="delete red" data-controler="Propriete" value="<?= $propriete["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
