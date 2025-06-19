<?php
$core = $_SESSION["CORE"];

?>


<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Ajouter un Complexe</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="complexe" class="row" style="width: 480px">
			
			<!-- DETAILS -->
			<div class="col_12">
				
				<div class="d-flex space-between pb-15">
					<div class="title" style="font-size: 14px; font-weight: bold; padding-top: 5px">
						Complexe
					</div>	
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="created" type="date" value="<?= isset($complexe)? explode(" ", $complexe["created"])[0]: date('Y-m-d'); ?>" class="">
							<?= isset($complexe)? '<input type="hidden" id="id" value="'.$complexe["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_6">
						<div class="form-element inline">
							<label for=""></label>
							
							<div class="col_12 d-flex">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="status" type="checkbox" <?= isset($complexe)? $complexe["status"]==="1"? "checked" : "" : "checked"  ?>>
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
							<label for="id_complexe_type">Type</label>
							<select id="id_complexe_type" class="field">
									<?php  
											$selected = "";
											foreach( $types as $k=>$v){
												if( isset($complexe) )
													if ($complexe["id_complexe_type"] === $v["id"]) $selected = "selected"; else $selected = "";
												else
													if ($v["is_default"])  $selected = "selected"; else $selected = "";
									?>	
								<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["complexe_type"] ) ?> </option>
									<?php } ?>
							</select>
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_8">
						<div class="form-element inline">
							<label for="name">Complexe</label>
							<input id="name" type="text" value="<?= isset($complexe)? $complexe["name"]: ""; ?>" class="field required">
						</div>						
					</div>
					<div class="col_4">
						<div class="form-element inline">
							<label for="ABR">ABR</label>
							<input id="ABR" type="text" value="<?= isset($complexe)? $complexe["ABR"]: ""; ?>" class="field required" maxlength="4">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="contact_1">Contact</label>
							<input id="contact_1" type="text" value="<?= isset($complexe)? $complexe["contact_1"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="contact_2">Contact</label>
							<input id="contact_2" type="text" value="<?= isset($complexe)? $complexe["contact_2"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
					
				<div class="row">
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_1">Télé. (1)</label>
							<input id="phone_1" type="text" value="<?= isset($complexe)? $complexe["phone_1"]: ""; ?>" class="field">
						</div>						
					</div>
					<div class="col_6">
						<div class="form-element inline">
							<label for="phone_2">Télé. (2)</label>
							<input id="phone_2" type="text" value="<?= isset($complexe)? $complexe["phone_2"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="ville">Ville</label>
							<input id="ville" type="text" value="<?= isset($complexe)? $complexe["ville"]: ""; ?>" class="field">
						</div>						
					</div>
				</div>
				
				<div class="row">
					<div class="col_12">
						<div class="form-element inline">
							<label for="adresse">Adresse</label>
							<input class="field" type="text" id="adresse" value="<?= isset($complexe)? $complexe["adresse"]: "" ?>">
						</div>						
					</div>
				</div>		
				
			</div>
						
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store green" data-controler="Complexe">Enregistrer</button></li>
			<?php if(isset($complexe)) { ?>
			<li><button class="delete red" data-controler="Complexe" value="<?= $complexe["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
