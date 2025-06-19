<div class="module">

	<div style="font-size: 26px; padding: 10px">Module</div>
	
	<div class="liste d-flex">
		<div class="modules_liste" style="width: 320px">
			<div class="modules_liste_add d-flex p-10 hide">
				<input type="text" value="">
				<button class="green save"><i class="far fa-save"></i></button>
				<button class="red remove"><i class="far fa-trash-alt"></i></button>
				<button class="transparent abort"><i class="fas fa-times"></i></button>
			</div>
			<div class="items p-10">
				<div class="item d-flex space-between" style="background-color: rgba(232,232,232,1.00); font-size: 12px; border-top-right-radius: 5px; border-top-left-radius: 5px">
					<div class="d-flex">
						<div class="p-10" style="width: 50px">ID</div>
						<div class="p-10">MODULE</div>					
					</div>
					<div>
						<button class="mdl_add"><i class="fas fa-plus"></i></button>
						<button class="mdl_refresh hide"></button>
					</div>
				</div>
				<div class="modules_container">
					<?php
						foreach($modules as $k=>$v){
							$selected = $k===0? 'selected': '';
							echo '
						<div data-id="'.$v["id"].'" class="item '.$selected.' mdl d-flex space-between" style="border-bottom: 1px solid rgba(232,232,232,1.00); font-size: 12px;">
							<div class="d-flex">
								<div class="p-10 id" style="width: 50px">'.$v["id"].'</div>
								<div class="p-10 name">'.$v["module_name"].'</div>						
							</div>
							<div style="padding-top:2px">
								<button class="transparent edit"> <i class="fas fa-ellipsis-h"></i> </button>
							</div>
						</div>

							';
						}
					?>				
				</div>

			</div>
		</div>
	
		<div class="actions_liste" style="flex: 1; padding-top: 10px">
			<div class="actions_liste_add d-flex p-10 hide">
				<input type="text" value="">
				<button class="green save"><i class="far fa-save"></i></button>
				<button class="red remove"><i class="far fa-trash-alt"></i></button>
				<button class="transparent abort"><i class="fas fa-times"></i></button>
			</div>
			
			<div class="module_actions_container">
				<?= $Obj->Get_Module_Actions(['id_module'=>1]) ?>
			</div>
			
		</div>
	</div>
	
</div>