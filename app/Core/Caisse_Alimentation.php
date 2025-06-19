<?php
require_once('Helpers/Modal.php');

class Caisse_Alimentation extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID", "style"=>"display:none", "display"=>0),
		array("column" => "name", "label"=>"CAISSE LIBELLE", "style"=>"font-weight:bold"),
		array("column" => "created", "label"=>"CREATION", "style"=>"min-width:80px; width:130px"),
		array("column" => "solde", "label"=>"SOLDE", "style"=>"min-width:160px; width:160px; background-color:#DCEDC8; font-weight:bold; font-size:16px; text-align:right"),
		array("column" => "status", "label"=>"STATUS", "style"=>"min-width:80px; width:80px"),
		array("column" => "actions", "label"=>"", "style"=>"min-width:105px; width:105px")
	);
	
	private $tableName = __CLASS__; //"Caisse_Alimentation";
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
	
	public function getColumns($style = null){
		
		$style = (is_null($style))? strtolower($this->tableName): $style;
		
		$columns = array();
		$l = new ListView();
		foreach($l->getDefaultStyle($style, $this->columns)["data"] as $k=>$v){
			array_push($columns, array("column" => $v["column"], "label" => $v["label"], "style"=>$v["style"], "display"=>$v["display"], "format"=>$v["format"]) );
		}
		array_push($columns, array("column" => "actions", "label" => "", "style"=>"min-width:105px; width:105px", "display"=>1) );
		return $columns;
		
	}
		
	public function drawTable_($columns = null, $conditions=null){
		if($columns == null){
			$columns = $this->getColumns();
		}
		$returned = '<table class="table">';
		$returned .= '	<thead>';
		$returned .= '		<tr>';
		
		$remove_sort = array("actions");

		foreach($columns as $key=>$value){

			$style = ""; 
			$is_sort = ( in_array($value["column"], $remove_sort) )? "" : "sort_by";
			$is_display = ( isset($value["display"]) )? "hide" : "";

			$returned .= "<th class='".$is_sort. " ". $is_display . "' data-sort='" . $value['column'] . "'>" . $value['label'] . "</th>";

		}
		$returned .= '		</tr>';
		$returned .= '	</thead>';
		$returned .= '<tbody>';

		if($conditions==null) $values = $this->fetchAll(); else $values = $this->find("",$conditions,"v_caisse_alimentation");
		
		$values = (is_null($values))? array() : $values;
		$content = '<div class="info info-success"><div class="info-success-icon"><i class="fa fa-info" aria-hidden="true"></i> </div><div class="info-message">Liste vide ...</div></div>';
		$i = 0;
		
		foreach($values as $k=>$v){
			$returned .= '	<tr class="edit_c_a" data="'.$v["id"].'">';
			foreach($columns as $key=>$value){
				
				$style = (isset($columns[$key]["style"]))? $columns[$key]["style"]:"";
				
				if(isset($v[ $columns[$key]["column"] ])){
					if($columns[$key]["column"] == "id"){
						$returned .= "<td style='".$style."'><span class='id-ligne'>" . $v[ $columns[$key]["column"] ] . "</span></td>";
					}elseif($columns[$key]["column"] == "montant"){
						$returned .= "<td style='".$style."'>" . number_format($v[ $columns[$key]["column"] ],2,",",".")  . " Dh</td>";
					}elseif($columns[$key]["column"] == "created_by"){
						$returned .= "<td style='".$style."'>" . $v['first_name'] . "</td>";
					}else{
						$returned .= "<td style='".$style."'>" . $v[ $columns[$key]["column"] ] . "</td>";
					}											
				}else{
					if($columns[$key]["column"] == "actions"){
						$returned .=   "<td style='".$style."'><button style='margin-right:10px' class='btn btn-red c_a remove_ligne' data-page='Caisse_Alimentation' value='".$v["id"]."'><i class='fas fa-trash-alt'></i></button><button class='btn btn-orange edit_c_a' data='".$v["id"]."'><i class='fas fa-edit'></i></button></td>";												
					}else{
						$returned .=  "<td>NaN</td>";
					}
				}
			}
			$returned .= '					</tr>';
		$i++	;
		}
		if($i == 0){
			$returned .= "<tr><td colspan='" . (count($columns)+1) . "'>".$content."</td></tr>";
		}
		$returned .= '</tbody>';	
		$returned .= '</table>';
		return $returned;
		
	}
	
	public function Create($params){
		$template = '
		
			<div class="title">Ajouter / Modifier</div>
			<div class="form-element inline">
				<label for="created">Date</label>
				<input id="created" type="date" value="' . date('Y-m-d') . '" class="field required" style="flex: none; width: 160px">
			</div>
			<div class="form-element inline">
				<label for="montant">Montant</label>
				<input id="montant" type="number" value="" class="field required" style="flex: none; width: 160px; background-color: rgba(224,248,188,1.00); text-align: right; font-weight:bold">
			</div>
			<div class="form-element inline">
				<label for="source">Source</label>
				<input id="source" type="text" value="" class="field">
			</div>

			<div class="form-element inline">
				<label for="notes">Notes</label>
				<input id="notes" type="text" value="" class="field">
			</div>
			
			<div class="p-10">
				<button class="mouvement_store blue mr-15" value="'. $params['id_caisse'] .'">Enregistrer</button>
				<button class="mouvement_abort">Quitter</button>
			</div>
		
		';
		return $template;
	}
	
	public function Update($params){
		
		$data = $this->find('', ['conditions'=>['id='=>$params['id']]], '');
		
		$template = '
		
			<div class="title">Ajouter / Modifier</div>
			<div class="form-element inline">
				<label for="created">Date</label>
				<input id="created" type="date" value="' . explode(" ", $data[0]["created"])[0] . '" class="field required" style="flex: none; width: 160px">
				<input id="id" type="hidden" value="' . $params['id'] . '">
			</div>
			<div class="form-element inline">
				<label for="montant">Montant</label>
				<input id="montant" type="number" value="' . $data[0]["montant"] . '" class="field required" style="flex: none; width: 160px; background-color: rgba(224,248,188,1.00); text-align: right; font-weight:bold">
			</div>
			<div class="form-element inline">
				<label for="source">Source</label>
				<input id="source" type="text" value="' . $data[0]["source"] . '" class="field">
			</div>

			<div class="form-element inline">
				<label for="notes">Notes</label>
				<input id="notes" type="text" value="' . $data[0]["notes"] . '" class="field">
			</div>
			
			<div class="p-10">
				<button class="mouvement_store blue mr-15" value="'. $data[0]['id_caisse'] .'">Enregistrer</button>
				<button class="mouvement_remove red mr-15" value="'. $data[0]['id'] .'">Supprimer</button>
				<button class="mouvement_abort">Quitter</button>
			</div>
		
		';
		return $template;
	}
	
	public function Remove($params){
		if(isset($params["id"])){
			$data = $this->find('', ['conditions' => [ 'id=' => $params['id'] ] ], '');
			if(count($data) == 1){
				$data = $data[0];
				$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
				$msg = "Montant: " . $data["montant"];
				$this->delete($params["id"]);
				$this->saveActivity("fr", $created_by, ['Caisse_Alimentation', -1], $data["id"], $msg);
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function Store($params){
		
		$created = date('Y-m-d H:i:s');
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		
		$data = [
			'created'					=>	$params['created'] . " " . date('H:i:s'),
			'created_by'				=>	$created_by,
			'updated'					=>	$created,
			'id_caisse'					=>	$params['id_caisse'],
			'montant'					=>	$params['montant'],
			'source'					=>	addslashes( $params['source'] ),
			'notes'						=>	addslashes( $params['notes'] ),
		];
		
		if( isset($params["id"]) ){
			unset($data["created"], $data["created_by"]);
			$data["updated_by"] = $created_by;
			$data["id"] = $params["id"];
		}
		
		
		if($this->save($data)){
			if(isset($data["id"])){
				$msg = $data["montant"];
				$this->saveActivity("fr", $created_by, ['Caisse_Alimentation', 0], $data["id"], $msg);				
			}else{
				$msg = $data["montant"];
				$this->saveActivity("fr", $created_by, ['Caisse_Alimentation', 1], $this->getLastID(), $msg);
			}

			return 1;
			
		}else{
			return $this->err;
		}
		
	}
	
	public function Get($params){
		$template = '			
			<div class="item d-flex space-between">
				<div class="d-flex" style="flex: 1">
					<div class="date">DATE</div>
					<div class="source">SOURCE</div>
					<div class="notess">NOTES</div>
					<div class="montant">MONTANT</div>
				</div>
				<div class="" style="width: 55px"></div>
			</div>';
		
		foreach( $this->find('', [ 'conditions'=> [ 'id_caisse='=>$params['id_caisse'] ], 'order'=>'created DESC' ], '') as $k=>$v){
			$template .= '
				<div class="item d-flex space-between">
					<div class="d-flex" style="flex: 1">
						<div class="date">' . $v["created"] . '</div>
						<div class="source">' . $v["source"]  . '</div>
						<div class="notess">' .  $v["notes"]  . '</div>
						<div class="montant">' . $this->format($v["montant"])  . '</div>
					</div>
					<div class="" style="width: 55px; text-align: right">
						<button class="update_mouvement" value="' . $v["id"] . '"><i class="fas fa-ellipsis-v"></i></button>
					</div>
				</div>
			';			
		}
		return $template;

	}
	
}
$caisse_alimentation = new Caisse_Alimentation;