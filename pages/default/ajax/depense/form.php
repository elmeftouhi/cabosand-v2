<?php session_start(); 
if(!isset($_SESSION['CORE'])){ die("-1"); }
if(!isset($_POST["page"])){ die("-2"); }




$core = $_SESSION['CORE']; 
$action = $_POST["action"];
$table_name = $_POST["page"];

if(!file_exists($core.$table_name.".php")){ die("-3"); }
$formToken = md5( uniqid('auth', true) );

if($action === "edit"){
	require_once($core.$table_name.".php");
	$ob = new $table_name();
	$id = $_POST["id"];
	$data = $ob->find('', [ 'conditions'=>['id=' => $id] ], 'v_depense');
	if( count( $data ) < 1) die("-4"); else $data = $data[0];
}

?>

<div style="max-width: 450px">

	<div class="row page_title d-flex space-between">
		<div class="col_6-inline icon">
			<i class="fas fa-address-card"></i> Depenses</span>
			<?= $action==="edit"? '<input type="hidden" id="id" value="'.$id.'">': '' ?>
			<input type="hidden" id="UID" value="<?= $action === "edit"? $data["UID"]==="0"? $formToken: $data["UID"] : $formToken  ?>">
		</div>
		<div class="col_6-inline actions <?= strtolower($table_name) ?>">
			<button class="btn btn-green save" value="<?= $table_name ?>"><i class="fas fa-save"></i> Enregistrer</button>
			<button class="btn btn-red close_form" value="<?= $table_name ?>"><i class="fas fa-times"></i></button>
		</div>
	</div>

	<hr>

	<!-- Categorie -->
	<div class="row mb-20">
		<div class="col_12">
			<label for="depense_category">Catégorie</label>
			<select id="depense_category">
				<option selected value="-1"></option>
					<?php require_once($core."Depense_Category.php"); 
			 				$selected = "";
							foreach( $depense_category->find('',['order'=>'depense_category asc'], '') as $k=>$v){
								if($action === "edit")
									if($v["id"] === $data["id_category"]) $selected = "selected"; else $selected = "";
								else
									if ($v["is_default"])  $selected = "selected"; else $selected = "";
					?>	
				<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["depense_category"] ) ?> </option>
					<?php } ?>
			</select>
		</div>	
	</div>

	<!-- Caisse -->
	<div class="row mb-20">
		<div class="col_12">
			<label for="depense_caisse">Caisse</label>
			<select id="depense_caisse">
				<option selected value="-1"></option>
					<?php require_once($core."Caisse.php"); 
							foreach( $caisse->find("",["conditions"=>["status="=>1], "order"=>"name"],"") as $k=>$v){
								if($action === "edit")
									if($v["id"] === $data["id_caisse"]) $selected = "selected"; else $selected = "";
					?>	
				<option <?= $selected ?> value="<?= $v["id"] ?>"> <?= strtoupper( $v["name"] ) ?> </option>
					<?php } ?>
			</select>
		</div>

	</div>

	<!-- Montant -->
	<div class="row mb-20">
		<div class="col_6">
			<label for="depense_montant">Montant</label>
			<input type="number" placeholder="Montant" value="<?= $action==="edit"? $data["montant"]: "0.00"  ?>" id="depense_montant" style="background-color: #FFF9C4; text-align: center; font-weight: bold; font-size: 16px">
		</div>	
	</div>

	<!-- Libelle -->
	<div class="row mb-20">
		<div class="col_12">
			<label for="depense_libelle">Libellé</label>
			<input type="text" placeholder="Libellé" id="depense_libelle" value="<?= $action==="edit"? $data["libelle"]: ""  ?>">
		</div>
	</div>

	<!-- Status -->
	<div class="row mb-20">
		<div class="col_6-inline">
			<div class="on_off <?= $action==="edit"? $data["status"]? "on": "off": "on"  ?>" id="depense_status"></div>
		</div>						
	</div>

	<div class="row" style="margin-bottom: 20px; border-bottom: 1px solid rgba(197,197,197,1.00)">
		<div class="col_8-inline">
			<h3 style="margin-left: 6px">APPARTEMENT</h3>					
		</div>
		<div class="col_4-inline" style="text-align: right">
			<button class="btn btn-default select_propriete"><i class="fas fa-list-alt"></i> Select</button>				
		</div>
	</div>
	<div class="row" style="margin-bottom: 20px">
		<div class="col_12">
			<label for="propriete_complexe">Complexe </label>
			<input type="text" placeholder="Complexe" id="propriete_complexe" value="<?= $action==="edit"? $data["name"]: '' ?>" disabled  style="background-color: #ededed; font-weight: bold">
			<input type="hidden" id="propriete_id" value="<?= $action==="edit"? $data["id_propriete"]: 0 ?>">
		</div>	
	</div>	
	<div class="row" style="margin-bottom: 20px;">
		<div class="col_4-inline">
			<label for="propriete_code">Code </label>
			<input type="text" placeholder="Code" id="propriete_code" value="<?= $action==="edit"? $data["code"]: "" ?>" disabled style="background-color: #ededed; font-weight: bold">
		</div>	
		<div class="col_3-inline">
			<label for="propriete_numero">Numero </label>
			<input type="text" placeholder="Numéro" id="propriete_numero" value="<?= $action==="edit"? $data["appartement_number"]: "" ?>" disabled style="background-color: #ededed; font-weight: bold">
		</div>
		<div class="col_2-inline">
			<label for="propriete_zone">Zone </label>
			<input type="text" placeholder="Zone" id="propriete_zone" value="<?= $action==="edit"? $data["zone_number"]: "" ?>" disabled style="background-color: #ededed; font-weight: bold">
		</div>	
		<div class="col_2-inline">
			<label for="propriete_bloc">Bloc </label>
			<input type="text" placeholder="Bloc" id="propriete_bloc" value="<?= $action==="edit"? $data["bloc_number"]: "" ?>" disabled style="background-color: #ededed; font-weight: bold">
		</div>	
	</div>

	<h3 style="margin-left: 6px">NOTE(S)</h3>
	<div class="row" style="margin-bottom: 20px">
		<div class="col_12-inline">
			<textarea id="notes" style="max-width: 100%; height: 120px"></textarea>
		</div>					
	</div>

</div>


<div class="debug"></div>	


