<?php session_start() ?>
<div id="page" class="" style="">
	<div class="page-head">
		<div class="title d-flex space-between">
			<div class="name"><i class="fas fa-tools"></i>	Paramêtres</div> 		
		</div>
	</div>
	<div class="page-body white" style="padding-left: 10px; padding-top: 125px; width: 100%">
		<div class="parametre d-flex">
			<div class="parametre_menu shadow" style="width:248px">
				<ul>
					<li class="selected" data-page="Get_General"><i class="fas fa-angle-right"></i> Général</li>
					<li data-page="Appartement_Category"><i class="fas fa-angle-right"></i> Appatement Catégories</li>
					<li data-page="Appartement_Type"><i class="fas fa-angle-right"></i> Appatement Type</li>
					<li data-page="Appartement_Status"><i class="fas fa-angle-right"></i> Appatement Statut</li>
					<li data-page="Depense_Category"><i class="fas fa-angle-right"></i> Dépense Catégories</li>
					<li data-page="Get_Module"><i class="fas fa-angle-right"></i> Modules</li>
				</ul>
			</div>

			<div class="parametre_content shadow">
				<?php require_once($_SESSION["CORE"]."Parametre.php"); echo $parametre->Get_General(); ?>
			</div>
		</div>
	</div>
</div>
