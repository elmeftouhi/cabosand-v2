<?php
require_once('Helpers/Modal.php');

class Notes extends Modal{
	
	private $tableName = __CLASS__;
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName(strtolower($this->tableName));
		}catch(Exception $e){
			die($e->getMessage());
		}
	}	
	
	
	
	public function Get_As_Table_By_Module($module, $id){
		$notes = $this->find("", array("conditions AND"=>array("id_module="=>$id, "module="=>$module), "order"=>"created DESC"), "");
		if(count($notes) > 0){
			$template = '
						<table class="table">
							<thead>
								<tr>
									<th>Date</th>
									<th>NOTES</th>
									<th>STATUS</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								{{body}}
							</tbody>
						</table>
					';
			$trs = '';
			foreach($notes as $k=>$v){

				$s = ($v["status"])? "on" : "off";
				$status = "<div data-notes_id='" . $v["id"] . "' class='on_off ".$s." notes_set_status'></div></td>";

				$trs .= '
								<tr>
									<td style="width:105px; max-width:105px">'.$v["created"].'</td>
									<td>'.$v["notes"].'</td>
									<td style="width:65px; max-width:65px">'.$status.'</td>
									<td style="width:105px; max-width:65px"><button class="btn btn-orange edit_note" style="padding:5px; margin-right:3px" data-id="'.$v["id"].'">Edit</button><button  style="padding:5px" class="btn btn-red delete_note" data-id="'.$v["id"].'">Supp.</button></td>
								</tr>';
			}
			return str_replace("{{body}}", $trs, $template);			
		}else{
			return "";
		}

	}
	
	public function NotesBy($params){
		$module = $params['module'];
		$notes = $this->find('', ['conditions AND' => ['module='=>$module, 'id_module='=>$params['id_module'] ], 'order'=>'created desc' ], '');
		
		$btn_show_more = '<div class="pt-10"><button class="notes_show_more" data-module="'.$module.'" data-module_id="'.$params['id_module'].'">Afficher plus</button></div>';
		
		$items = '';
		$items_status = '';
		
		foreach($notes as $k=>$v){
			if($v["status"] === "1"){
				$items .= '
							<div class="item">
								<div class="d-flex space-between">
									<div class="date">'.$v["created"].'</div>
									<div><button data-status="0" class="hide_unhide_note transparent" value="'.$v["id"].'"><i class="far fa-eye-slash"></i></button></div>
								</div>
								<div class="description">'. addslashes( $v["notes"] ).'</div>
							</div>
				';				
			}else{
				$items_status .= '
							<div class="item status hide" style="border:1px solid gray; background-color:#ededed">
								<div class="d-flex space-between">
									<div class="date">'.$v["created"].'</div>
									<div><button data-status="1" class="hide_unhide_note transparent" value="'.$v["id"].'"><i class="far fa-eye"></i></button></div>
								</div>
								<div class="description">'. addslashes( $v["notes"] ).'</div>
							</div>
				';				
			}

		}
		$empty = '<div class="label label-default"> Aucune Note! </div>';
		
		$items = $items === ''? $empty: $items;
		$btn_show_more = $items_status === ''? '': $btn_show_more;
		return $items.$items_status.$btn_show_more;
		
	}
	
	public function ShortTable($notes, $id_module=0, $module=0){
		$id_module = count($notes) > 0 ? $notes[0]["id_module"]: $id_module;
		$module = count($notes) > 0 ? $notes[0]["module"]: $module;
		$template = '
			
			<div class="notes">
				<div class="d-flex space-between pb-10">
					<div class="title">Notes</div>
					<div class="hide"><button data-module="'.$module.'" data-id_module="'.$id_module.'" class="reload_notes">load</button></div>
				</div>
				{{items}}
				{{show_more}}
			</div>
		
		';
		$btn_show_more = '<div class="pt-10"><button class="notes_show_more" data-module="'.$module.'" data-module_id="'.$id_module.'">Afficher plus</button></div>';
		
		$items = '';
		$items_status = '';
		
		foreach($notes as $k=>$v){
			if($v["status"] === "1"){
				$items .= '
							<div class="item">
								<div class="d-flex space-between">
									<div class="date">'.$v["created"].'</div>
									<div><button data-status="0" class="hide_unhide_note transparent" value="'.$v["id"].'"><i class="far fa-eye-slash"></i></button></div>
								</div>
								<div class="description">'. addslashes( $v["notes"] ).'</div>
							</div>
				';				
			}else{
				$items_status .= '
							<div class="item status hide">
								<div class="d-flex space-between">
									<div class="date">'.$v["created"].'</div>
									<div><button data-status="1" class="hide_unhide_note transparent" value="'.$v["id"].'"><i class="far fa-eye"></i></button></div>
								</div>
								<div class="description">'. addslashes( $v["notes"] ).'</div>
							</div>
				';				
			}

		}
		$empty = '<div class="label label-default"> Aucune Note! </div>';
		
		$items = $items === ''? $empty: $items;
		$btn_show_more = $items_status === ''? '': $btn_show_more;
		return str_replace(["{{items}}", "{{show_more}}"], [$items.$items_status, $btn_show_more], $template);
		
	}
	
	public function Store($params){
		$created_by	=	$_SESSION[ $this->config->get()['GENERAL']['ENVIRENMENT'] ]['USER']['id'];
		$created = date('Y-m-d H:i:s');
		$module = addslashes( $params['module'] );
		$id_module = addslashes( $params['id_module'] );
		$notes = addslashes( $params['notes'] );
		$data = [
			'created'		=>	$created,
			'created_by'	=>	$created_by,
			'module'		=>	$module,
			'id_module'		=>	$id_module,
			'notes'			=>	$notes,
			'status'		=>	1
		];
		
		$this->save(['id'=>$id_module, 'notes'=>''], $module);
		
		$this->save($data);
		$msg = "Notes: " . $notes;
		$this->saveActivity("fr", $created_by, ['Notes', 1], $this->getLastID(), $msg);
		return 1;
	}
	
	public function Hide_Unhide($params){
		$id = isset($params['id'])? $params['id']: 0;
		$status = isset($params['status'])? $params['status']: 0;
		
		$this->save([
			'id'		=>	$id,
			'status'	=>	$status
		]);
		return 1;
		
	}
}
$notes = new Notes;