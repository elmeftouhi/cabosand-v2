
<div class="general">
	<div style="font-size: 26px; padding: 10px">Général</div>
	<div class="section pt-10 pb-20">
		<div class="title">
			<span>Projet</span>
		</div>
		<div class="row">
			<div class="col_6">
				<div class="form-element inline">
					<label for="project_name">Projet </label>
					<input id="project_name" type="text" value="<?= isset($manager)? $manager["name"]: "" ?>">
				</div>						
			</div>
		</div>

		<div class="row">
			<div class="col_6">
				<div class="form-element inline">
					<label for="project_email">E-Mail </label>
					<input id="project_email" type="text" style="max-width: 310px" value="<?= isset($manager)? $manager["email"]: "" ?>">
				</div>						
			</div>
		</div>

		<div class="row">
			<div class="col_6">
				<div class="form-element inline">
					<label for="project_telephone">Téléphone </label>
					<input id="project_telephone" type="text" style="max-width: 310px" value="<?= isset($manager)? $manager["telephone"]: "" ?>">
				</div>						
			</div>
		</div>	

		<div class="row">
			<div class="col_6 d-flex">
				<div style="width: 70px; font-size: 10px; padding-top: 8px; text-align: right;padding-right: 15px;">
					Langues
				</div>
				<div>
					<ul class="checklist-selector">
						<li class="d-flex">
							<div>
								<label class="switch">
									<input <?= isset($manager)? $manager["lang"]==="fr"? "checked": "" : "checked" ?> class="option" type="checkbox"><span class="slider round"></span>
								</label>
							</div>
							<div class="pt-5 pl-5">Français </div>
						</li>
						<li class="d-flex">
							<div>
								<label class="switch">
									<input <?= isset($manager)? $manager["lang"]==="ar"? "checked": "" : "" ?> class="option" type="checkbox"><span class="slider round"></span></label>
							</div>
							<div class="pt-5 pl-5">العربية </div>
						</li>
						<li class="d-flex">
							<div>
								<label class="switch">
									<input <?= isset($manager)? $manager["lang"]==="en"? "checked": "" : "" ?> class="option" type="checkbox"><span class="slider round"></span></label>
							</div>
							<div class="pt-5 pl-5">English </div>
						</li>			
					</ul>								
				</div>

			</div>
		</div>
	</div>

	<div class="section pt-10 pb-20">
		<div class="title">
			<span>Format Monétique</span>
		</div>
		<div class="row">
			<div class="col_6">
				<div class="form-element inline">
					<label for="currency">Symbole </label>
					<input id="currency" type="text" style="max-width: 90px; text-align: center" value="<?= isset($manager)? $manager["currency"]: "Dh" ?>">
				</div>						
			</div>
		</div>
	</div>
	
	
	<div class="section pt-10 pb-20">
		<div class="row">
			<div class="col_6">
				<button class="save_parametre_general green">Enregistrer</button>						
			</div>
		</div>
	</div>
</div>
				