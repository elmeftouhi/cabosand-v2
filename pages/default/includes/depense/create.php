<?php
$core = $_SESSION["CORE"];
if(isset($depense)){
	//var_dump($propriete);
	$token = $depense["UID"];
}else{
	$token = md5( uniqid('auth', true) );
}

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter une Dépense</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="depense" class="">
			
			<div class="form-element inline">
				<label for="date_depense">Date</label>
				<input id="date_depense" type="date" value="<?= isset($depense)? $depense["date_depense"]: date('Y-m-d'); ?>" class="field required">
				<input id="UID" type="hidden" value="<?= isset($depense)? $depense["UID"]: $token ?>" class="field required">
				<?= isset($depense)? '<input type="hidden" id="id" value="'.$depense["id"].'" class="field required">': '' ?>
			</div>
			
			<div class="form-element inline">
				<label for="depense_category">Catégorie</label>
				<select id="depense_category" class="required field">
						<?php require_once($core."Depense_Category.php"); 
								$selected = "";
								foreach( $depense_category->find('',['order'=>'depense_category asc'], '') as $k=>$v){
									if( isset($depense) )
										if ($depense["id_category"] === $v["id"]) $selected = "selected"; else $selected = "";
									else
										if ($v["is_default"])  $selected = "selected"; else $selected = "";
						?>	
					<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["depense_category"] ) ?> </option>
						<?php } ?>
				</select>
			</div>
			<div class="form-element inline">
				<label for="depense_caisse">Caisse</label>
				<select id="depense_caisse" class="required field">
						<?php require_once($core."Caisse.php"); 
								$selected = "";
								foreach( $caisse->find("",["conditions"=>["status="=>1], "order"=>"name"],"") as $k=>$v){
									if( isset($depense) )
										if ($depense["id_caisse"] === $v["id"]) $selected = "selected"; else $selected = "";
									else
										if ($v["is_default"])  $selected = "selected"; else $selected = "";
						?>	
					<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["name"] ) ?> </option>
						<?php } ?>
				</select>
			</div>
			<div class="form-element inline">
				<label for="libelle">Libellé</label>
				<input type="text" id="libelle" value="<?= isset($depense)? $depense["libelle"]: "" ?>" class="required field">
			</div>
			<div class="form-element inline">
				<label for="montant">Montant</label>
				<input class="text-right required field" type="number" id="montant" placeholder="0.00" value="<?= isset($depense)? $depense["montant"]: "" ?>" style="background-color: rgba(249,244,196,1.00); font-size: 16px; font-weight: bold">
			</div>
			
			<hr>
			
			<div class="depense-links collection checkbox">
				<ul class="checklist-selector">
					<li class="d-flex">
						<div>
							<label class="switch">
						<?php if(isset($propriete)){ ?>
								<input class="field" id="id_propriete" data-id="<?= $propriete["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_propriete" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Appartement </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Propriete" data-function="FindBy" type="text" <?= isset($propriete)? "": "disabled" ?> value="<?= isset($propriete)? $propriete["code"]: "" ?>">
							<?php if(isset($propriete)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>
					</li>
					
					<li class="d-flex">
						<div>
							<label class="switch">
						<?php if(isset($societe)){ ?>
								<input class="field" id="id_societe" data-id="<?= $societe["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_societe" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Société </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Entreprise" data-function="FindBy" type="text" <?= isset($societe)? "": "disabled" ?> value="<?= isset($societe)? $societe["raison_social"]: "" ?>">
							<?php if(isset($societe)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>
					</li>
					
					<li class="d-flex">
						
						<div>
							<label class="switch">
						<?php if(isset($person)){ ?>
								<input class="field" id="id_person" data-id="<?= $person["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_person" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Employé </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Person" data-function="FindBy" type="text" <?= isset($person)? "": "disabled" ?> value="<?= isset($person)? $person["first_name"]: "" ?>">
							<?php if(isset($person)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>

					</li>
					
					<li class="d-flex">
						
						<div>
							<label class="switch">
						<?php if(isset($contrat)){ ?>
								<input class="field" id="id_contrat" data-id="<?= $contrat["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_contrat" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Contrat </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Contrat" data-function="FindBy" type="text" <?= isset($contrat)? "": "disabled" ?> value="<?= isset($contrat)? $contrat["UID"]: "" ?>">
							<?php if(isset($contrat)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>

					</li>
					
					<li class="d-flex">
						
						<div>
							<label class="switch">
						<?php if(isset($client)){ ?>
								<input class="field" id="id_client" data-id="<?= $client["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_client" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Client </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Client" data-function="FindBy" type="text" <?= isset($client)? "": "disabled" ?> value="<?= isset($client)? $client["first_name"]: "" ?>">
							<?php if(isset($client)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>

					</li>
					
					<li class="d-flex">
						
						<div>
							<label class="switch">
						<?php if(isset($proprietaire)){ ?>
								<input class="field" id="id_proprietaire" data-id="<?= $proprietaire["id"] ?>" type="checkbox" value="" checked>
						<?php }else{ ?>
								<input class="field" id="id_proprietaire" data-id="0" type="checkbox" value="">
						<?php } ?>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="label pl-10 pr-10 pt-5"> Proprietaire </div>
						<div class="d-flex ui-widget" style="flex: 1"> 
							<input data-controler="Proprietaire" data-function="FindBy" type="text" <?= isset($proprietaire)? "": "disabled" ?> value="<?= isset($proprietaire)? $proprietaire["name"]: "" ?>">
							<?php if(isset($proprietaire)){ ?>
								<div class="is_exists success"><i class="fas fa-check"></i></div>
							<?php }else{ ?>
								<div class="is_exists"></div>
							<?php } ?>
						</div>

					</li>
					
				</ul>
			</div>
			
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Depense">Enregistrer</button></li>
			<?php if(isset($depense)) { ?>
			<li><button class="delete red" data-controler="Depense" value="<?= $depense["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
