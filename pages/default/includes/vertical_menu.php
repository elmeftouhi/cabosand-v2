<?php $envirenment = "CABOSANDE-MANAGER"; ?>
	<div class="vertical_menu toLeft">
		<ul class="unstyle">
			<li class="user">
				<div class="image"> <img src="<?= HTTP.HOST ?>templates/default/images/user.png"> </div>
				<div class="text"><?= substr($_SESSION[$envirenment]["USER"]["last_name"], 0, 4). ". ". $_SESSION[$envirenment]["USER"]["first_name"] ?></div>
				<span class='down'><i class="fas fa-cog"></i></span>
			</li>
			<li class="home open">
				<span class="url hide">index</span>
				<i class="fas fa-tachometer-alt"></i> Dashbord
			</li>
		<?php

require_once(CORE."Menu.php");
										 
foreach($links->find(null,array("conditions AND"=>array("id_parent="=>0,"status="=>1), "order"=>"_order ASC"),null) as $k=>$v){
	
	$data = $links->find(null,array("conditions AND"=>array("id_parent="=>$v["id"],"status="=>1), "order"=>"_order ASC"),null);
	
	if(count($data)>0){
		
		echo "<li class='show_submenu'>".$v["icon"]." ".$v["libelle"]."<span class='down'><i class='fa fa-plus-square on'></i><i class='fa fa-minus-square off hide'></i></span>";
		echo '<ul class="unstyle sub_menu hide">';
		
		foreach($data as $kk=>$vv){
			echo "<li class='open'><span class='url hide'>".$vv["url"]."</span><i class='fa fa-angle-right'></i> ".$vv["libelle"]."</li>";
		}	
		
		echo '</ul>';
		
	}else{
		if($v["url"] === "menu"){
			if($_SESSION[$envirenment]["USER"]["id_profil"] === "1"){
				echo "<li class='open'><span class='url hide'>".$v["url"]."</span>".$v["icon"]." ".$v["libelle"].'</li>';
			}
		}else{
			echo "<li class='open'><span class='url hide'>".$v["url"]."</span>".$v["icon"]." ".$v["libelle"].'</li>';
		}
		
		
		
		
	}
	
}

?>
		<li class='btn_login'>
			<button class="btn btn-red btn_login" value="logout">
				<span class="is_doing hide"><i class="fas fa-sign-out-alt"></i> Traitement ... </span> 
				<span class="do">Se Désonnecter </span>
			</button>
		</li>
		</ul>
	</div>
	
