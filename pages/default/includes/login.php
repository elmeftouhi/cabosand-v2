<div id="login" class="">
	<div class="login-container">
		<div class="logo-container">
			<div class="logo d-flex">
				<div class="pt-20">
					<img src="<?= "http://" . $_SESSION["HOST"] . "templates/default/images/manager-logo.png" ?>">
				</div>
				<div class="name pl-10">
					Manager
					<div class="version">1.2.8</div>
				</div>
			</div>
			
			<div class="form white">
				<div class="form-container">
					<div class="form-element">
						<label for="email">E-Mail</label>
						<input type="email" id="email" name="email">
					</div>
					
					<div class="form-element">
						<label for="password">Password</label>
						<input type="password" id="password" name="password">
					</div>
					
					<div class="form-element d-flex">
						<div>
							<label class="switch">
								<input class="remember" type="checkbox">
								<span class="slider round"></span>
							</label>
						</div>
						<div class="pt-5 pl-5"> Se souvenir de moi </div>
					</div>
					
					<div class="form-element">
						<button>Se connecter <i class="fas fa-caret-right"></i></button>
					</div>
					
				</div>
			</div>
			
		</div>
		
		<div class="section text-center">
			<h3><a href="https://www.extensionmedia.ma">Extension Media Company</a></h3>
			<p class="">
				Manager Is a product of Extension Media Company.
			</p>
			<p class="">
				We Made it with much <span class="love"><i class="fas fa-heart animate__animated animate__heartBeat animate__repeat-3"></i></span> and lots of <span class="coffee"><i class="fas fa-mug-hot"></i></span>
			</p>
		</div>
		
	</div>

</div>