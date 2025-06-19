<?php
$core = $_SESSION["CORE"];

?>


<div id="popup" style="width: 550px">	

	<div class="popup-header d-flex space-between">
		<div class="">Alimentation Caisse</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content mouvement">

		
		<div class="d-flex space-between pb-15">
			<div class="pt-5" style="font-weight: bold">Caisse Mouvements</div>
			<div class=""><button class="create_mouvement green" value="<?= $id_caisse ?>">Ajouter</button></div>
			<div class="hide"><button class="refresh_mouvement green" value="<?= $id_caisse ?>">Actu</button></div>
		</div>
		
		<div class="mouvement_container hide"></div>	
		
		<div class="items">
			<div class="item d-flex space-between">
				<div class="d-flex" style="flex: 1">
					<div class="date">DATE</div>
					<div class="source">SOURCE</div>
					<div class="notess">NOTES</div>
					<div class="montant">MONTANT</div>
				</div>
				<div class="" style="width: 55px"></div>
			</div>	
			<?php
				foreach($mouvements as $k=>$v){
			?>
			<div class="item d-flex space-between">
				<div class="d-flex" style="flex: 1">
					<div class="date"><?= $v["created"] ?></div>
					<div class="source"><?= $v["source"] ?></div>
					<div class="notess"><?= $v["notes"] ?></div>
					<div class="montant"><?= $Obj->format($v["montant"]) ?></div>
				</div>
				<div class="" style="width: 55px; text-align: right">
					<button class='update_mouvement' value='<?= $v["id"] ?>'><i class='fas fa-ellipsis-v'></i></button>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="popup-actions">
		<ul>
			<li><button class="abort">Quitter</button></li>
		</ul>
	</div>
</div>
