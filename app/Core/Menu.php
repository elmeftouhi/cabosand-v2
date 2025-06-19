<?php
require_once('Helpers/Modal.php');
require_once('Helpers/Config.php');
require_once('Person.php');

class Menu extends Modal{

	private $columns = array(
		array("column" => "id", "label"=>"#ID"),
		array("column" => "libelle", "label"=>"LIBELLE"),
		array("column" => "url", "label"=>"URL"),
		array("column" => "icon", "label"=>"ICON"),
		array("column" => "parent", "label"=>"PARENT"),
		array("column" => "_order", "label"=>"ORDRE"),
		array("column" => "status", "label"=>"STATUS")
	);
	
// construct
	public function __construct(){
		try{
			parent::__construct();
			$this->setTableName("manager_links");
		}catch(Exception $e){
			$this->err->save("Template -> Constructeur","$e->getMessage()");
		}
	}	
	
	public function getColumns(){
		
		if ( isset($this->columns) ){
			return $this->columns;
		}else{
			$columns = array();
			//var_dump($this->getColumnsName("client"));
			foreach($this->getColumnsName("manager_links") as $k=>$v){
				//var_dump($v["Field"]);
				array_push($columns, array("column" => $v["Field"], "label" => $v["Field"]) );
			}
			return $columns;
		}
		
	}
	
	public function editOrder($id, $up_or_down, $id_preview, $id_next, $current_order){
		
		
		
		$this->id = $id;
		$m = $this->read();
		if($up_or_down==="UP"){
			$this->save(array(
				"id"		=>	$id,
				"_order"	=>	$current_order-1
			));
			
			$this->save(array(
				"id"		=>	$id_preview,
				"_order"	=>	$current_order
			));
			
		}else{
			$this->save(array(
				"id"		=>	$id,
				"_order"	=>	$current_order+1
			));
			
			$this->save(array(
				"id"		=>	$id_next,
				"_order"	=>	$current_order
			));
		}
		return 1;
	}
		
	public function Drow(){
		$config = new Config;
		$env = $config->get()["GENERAL"]["ENVIRENMENT"];
		$id_user = $_SESSION[$env]["USER"]["id"];
		
		$links = [
			'Index'					=>	'<li class="open show_hide_menu" data-page="index.index"><i class="fas fa-chart-line"></i> Dashboard</li>',
			'Dépense'				=>	'<li class="open show_hide_menu" data-page="depense.list"><i class="fas fa-hand-holding-usd"></i> Dépense </li>',
			'Caisse'				=>	'<li class="open show_hide_menu" data-page="caisse.list"><i class="fas fa-cash-register"></i> Caisses </li>',
			'Propriété'				=>	'<li class="has_sub" data-sub-target="propriete"><i class="fas fa-home"></i> Propriété <div class="down"><i class="fas fa-caret-down"></i></div></li>',
				'Appartements'			=>	'<li class="open show_hide_menu sub propriete hide" data-page="propriete.list"><i class="fas fa-home"></i> Liste Apparts. </li>',
			'Contrat'				=>	'<li class="open show_hide_menu" data-page="contrat.list"><i class="fas fa-file-contract"></i> Contrat </li>',
			'Périodes'				=>	'<li class="open show_hide_menu" data-page="propriete_location.list"><i class="fas fa-file-contract"></i> Périodes </li>',
			'Client'				=>	'<li class="open show_hide_menu" data-page="client.list"><i class="fas fa-user-tie"></i> Client </li>',
			'Propriétaire'			=>	'<li class="open show_hide_menu" data-page="proprietaire.list"><i class="fas fa-user"></i> Propriétaire </li>',
			'Complexe'				=>	'<li class="open show_hide_menu" data-page="complexe.list"><i class="fas fa-city"></i> Complexe </li>',
			'Options'				=>	'<li class="has_sub" data-sub-target="optionss"><i class="fas fa-cog"></i> Options <div class="down"><i class="fas fa-caret-down"></i></div></li>',
				'Général'				=>	'<li class="open show_hide_menu sub optionss hide" data-page="parametres.index"><i class="fas fa-tools"></i> Général </li>',
				'Listes'				=>	'<li class="open show_hide_menu sub optionss hide" data-page="listview.list"><i class="far fa-list-alt"></i> Listes </li>',
				'Log'					=>	'<li class="open show_hide_menu sub optionss hide" data-page="log.list"><i class="fas fa-clipboard-list"></i> Log</li>',
				'Propriété Categorie'	=>	'<li class="open show_hide_menu sub optionss hide" data-page="propriete_category.list"><i class="fas fa-clipboard-list"></i> App Catégorie</li>'
			
		];
			
		$person = new Person;
		$template = '
			<ul>
				{{li}}
			</ul>	
			<div class="" style="background-color:#333; position: absolute; bottom: 0; width: 145px; padding-top: 5px; padding-right: 10px; box-shadow: rgba(0, 0, 0, 0.13) 0px -3px 3px 0px ">
				<ul>';
		if( $person->Is_Permission_Granted(['key'=>'Utilisateur']) )
			$template .= '<li class="open" data-page="person.list"><i class="fas fa-user-friends"></i> Utilisateurs </li>';		
			
			
		$template .= '
					<li><i class="fas fa-question"></i> Support </li>
				</ul>
			</div>
		';
		
		
		
		
		$list = $this->find('', [ 'conditions AND'=>['status='=>1, 'id_parent='=>0], 'order'=>'_order asc' ], '');
		$li = '';
		foreach($links as $k=>$v){
			if( $person->Is_Permission_Granted(['key'=>$k]) || $k === 'Index' )
				$li .= $v;
		}
		return str_replace("{{li}}", $li, $template);
	}
	
	
}
$menu = new Menu;