
<div id="propriete_to_periode" style="text-align: left; border: 1px dashed red; margin-bottom: 10px; padding: 10px 3px">
	<div class="row">
		<div class="col_12">
			<div class="form-element">
				<label for="client">Client</label>
				<select class="client" data-id_propriete="<?= $id_propriete ?>">
					<option selected value="-1">-- Clients </option>
					<?php
						foreach($clients as $k=>$v){
							echo '<option data-id="'.$v["id_client"].'" value="' . $v["UID"].'">' . $v["societe_name"] . '</option>';
						}
					?>
				</select>
			</div>
		</div>
	</div>

	<div class="periodes_container items" style="height: 125px; overflow: auto">
		
	</div>
</div>
