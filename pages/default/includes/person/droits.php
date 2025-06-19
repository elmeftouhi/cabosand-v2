<div id="popup" class="pb-5" style="width:620px; margin:50px auto; height:450px">	
<style>
		
</style>
	<div class="popup-header d-flex space-between">
		<div class="">Les Droits</div>
		<div class="red-text"><button class="modal_close"><i class="fas fa-times"></i></button></div>
	</div>

	<div class="popup-content" style="padding-bottom:40px; max-height:100%">
		
		<div class="d-flex space-between pb-10">
			<div style=""></div>
			<div><button value="<?= $id_user ?>" class="permission_save green"><i class="far fa-save"></i></button></div>
		</div>
		
		<div id="person_droits" class="row" style="text-align: left">
			<div class="droits" style="font-size: 10px">
			<?php foreach($modules as $m): 
				if( $Obj->Is_Permission_Granted(['id_user'=>$id_user, 'key'=>$m['module_name']]) )
					$is_module = true;
				else
					$is_module = false;
			?>
				<div class="item d-flex" style="border: 1px solid rgba(233,233,233,1.00); margin-bottom: 8px">
					<div class="module" style="width: 120px; padding: 5px 0; background-color: rgba(233,233,233,.50)">
						  <input data-id="<?= $m["module_name"] ?>" class="module-input" <?= $is_module? "checked": "" ?> style="vertical-align:middle;" type="checkbox" id="module-<?= $m['id'] ?>">
						  <label style="vertical-align:middle;" for="module-<?= $m['id'] ?>"><?= $m['module_name'] ?></label>
					</div>
					<div class="actions d-flex module-<?= $m['id'] ?>" style="flex: 1">
						<?php foreach($actions as $ac): 
								$is_action = $Obj->Is_Permission_Granted(['id_user'=>$id_user, 'key'=>$m['module_name'], 'value'=>$ac['module_action']]);
						?>
							<?php if($ac["id_module"] === $m["id"] ):?>
								<div class="action" style="min-width: 65px; padding: 5px 5px">
									  <input data-id="<?= $ac["module_action"] ?>" <?= !$is_module? "disabled": "" ?> style="vertical-align:middle;" <?= $is_action? "checked": "" ?> type="checkbox" id="action-<?= $ac['id'] ?>">
									  <label style="vertical-align:middle;" for="action-<?= $ac['id'] ?>"><?= $ac['module_action'] ?></label>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					
					
				</div>
			<?php endforeach; ?>
			</div>			
		</div>
	</div>
</div>