<div class="wrapper d-flex">
	<nav class="">
		<div class="nav-container">
			<div class="flex items-center">
				<div class="">
					<button class="bg-green-600 mr-4 show_hide_menu">
						<i class="fas fa-bars"></i>
					</button>
				</div>
				<div class="brand-name">
					<div class="logo">
						<img src="<?= HTTP.HOST."templates/default/images/manager-logo.png" ?>">
						<span>1.2.8</span>
					</div>
					<div class="project-name">
						<span class="separator"></span>
						Cabosand
					</div>
				</div>				
			</div>


			<ul class="">
				<li><button class="show_fullscreen"><i class="fas fa-compress"></i></button></li>
				<li><button class="show_calendar hide"><i class="fas fa-calendar-alt"></i></button></li>
				<li><button class="show_profile"><i class="far fa-user"></i></button></li>
				<li><button id="logout" class="text-red"><i class="fas fa-sign-out-alt"></i></button></li>
			</ul>
		</div>
	</nav>	
	<div class="navigation-menu hidden" style="padding-bottom: 80px">
		<?php
			require_once(CORE."Menu.php");
			echo $menu->Drow();
		?>

	</div>
	
	<div id="app" class="content">
		Loading content ...
	</div

