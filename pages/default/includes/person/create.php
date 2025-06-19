<?php
$core = $_SESSION["CORE"];
if(isset($person)){
	//var_dump($propriete);
	$token = $person["UID"]==="0"? md5( uniqid('auth', true) ): $person["UID"];
}else{
	$token = substr(md5( uniqid('auth', true) ),0,8) ;
}

?>

<div id="popup">	

	<div class="popup-header d-flex space-between">
		<div class="">Mon Profile</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content">
		<div id="profile" class="">
			<div class="image-container">
				<div class="image">
					<img src="<?= $Obj->GetPictures(['folder'=>'person', 'UID'=> isset($person)? $person["UID"]: "0"])[0]; ?>">
					<div class="image-actions">
						<button class="image-edit upload_btn" data-target="upload"><i class="fas fa-camera-retro"></i></button>
						<button class="image-reload" data-folder="person" data-uid="<?= isset($person)? $person["UID"]: $token ?>"><i class="fas fa-sync-alt"></i></button>										
					</div>

					<input class="hide" type="file" id="upload" data-uid="<?= isset($person)? $person["UID"]: $token ?>" data-folder="person" data-is_unique="1">
				</div>
				<div class="progress">
					<div style="width:0%" class="progress-bar progress-value">0%</div>
				</div>
			</div>

			<div class="profile-content">
				
				<div class="row" style="padding: 0">
					<div class="col_8" style="padding: 0">
						<div class="form-element inline">
							<label for="created">Date</label>
							<input id="created" readonly type="date" value="<?= isset($person)? explode(" ", $person["created"])[0]: date('Y-m-d'); ?>" class="">
							<input id="UID" type="hidden" value="<?= $token ?>" class="field required">
							<?= isset($person)? '<input type="hidden" id="id" value="'.$person["id"].'" class="field required">': '' ?>
						</div>						
					</div>
					
					<div class="col_4" style="padding: 0">
						<div class="form-element inline">
							
							<div class="col_12 d-flex" style="padding-top: 3px">
								<div>
									<label class="switch" style="width: 40px">
										<input class="field" id="user_status" type="checkbox" <?= isset($person)? $person["status"]==="1"? "checked" : "" : "checked"  ?>>
										<span class="slider round"></span>
									</label>
								</div>
								<div class="pt-5 pl-5"> Status</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-element">
					<label for="user_first_name">Prénom</label>
					<input type="text" id="user_first_name" value="<?= isset($person)? $person["first_name"]:"" ?>">
				</div>
				<div class="form-element">
					<label for="user_last_name">Nom</label>
					<input type="text" id="user_last_name" value="<?= isset($person)? $person["last_name"]:"" ?>">
				</div>
				<div class="form-element inline">
					<label for="user_profile">Profile</label>
					<select id="user_profile" class="required field">
							<?php  
									$selected = "";
									foreach( $profiles as $k=>$v){
										if( isset($person) )
											if ($person["id_profil"] === $v["id"]) $selected = "selected"; else $selected = "";
										else
											if ($v["is_default"])  $selected = "selected"; else $selected = "";
							?>	
						<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["person_profile"] ) ?> </option>
							<?php } ?>
					</select>
				</div>
				<div class="form-element">
					<label for="user_telephone">Téléphone</label>
					<input type="text" id="user_telephone" value="<?= isset($person)? $person["telephone"]:"" ?>">
				</div>
				<div class="form-element">
					<label for="user_email">E-Mail</label>
					<input type="email" id="user_email" value="<?= isset($person)? $person["email"]:"" ?>">
				</div>

				<hr>

				<div class="form-element">
					<label for="user_login">Login</label>
					<input class="" type="email" id="user_login" value="<?= isset($person)? $person["login"]:"" ?>">
				</div>
				<div class="form-element">
					<label for="user_password">Password</label>
					<input type="password" id="user_password" value="<?= isset($person)? $person["password"]:"" ?>"  disabled style="background-color:rgba(0,0,0,0.1)">
					<button class="edit-password-profile"><i class="far fa-keyboard"></i></button>
				</div>
			</div>
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="store-profile green">Enregistrer</button></li>
			<?php if(isset($person)) { ?>
			<li><button class="delete red" data-controler="person" value="<?= $person["id"] ?>">Supprimer</button></li>
			<?php } ?>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
