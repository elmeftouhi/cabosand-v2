<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "ListView";
require_once($core.$table_name.".php");  
$ob = new $table_name();
?>
	<div class="row page_title">
		<div class="col_12-inline icon">
			Listes
		</div>
	</div>
	<hr>
	
	<div class="row">
		<div class="col_12">
			<?php
			//$ob->addStyle("v_propriete", array("name"=>"Standard", "is_default"=>0,"data"=>array(0=> array("column"=>"date","label"=>"daterrrr","display"=>1,"style"=>""))));
			//var_dump($ob->getColumnsName("v_propriete"));
			//echo $ob->deleteStyle("v_propriete","test");
			?>
		</div>
	</div>
	
	<div class="panel">
		<div class="panel-content" style="padding: 0; padding-bottom: 20px">
			<div class="row listview">

				<div class="col_2-inline" style="padding: 0; height: 450px">
					<div class="modules" style="height: 100%; overflow: auto; ">
						<div class="row" style="margin-bottom:10px; padding: 5px 0px 0 2px;">
							<div class='col_6-inline' style=''>
								<span style='font-size:16px; font-weight:bold;'>Modules</span>
							</div>
							<div class='col_6-inline' style='text-align:right; padding:0'>
								<div class='btn-group'>
									<button class='btn btn-red module_del'><i class='fas fa-minus-circle'></i></button>
									<button class='btn btn-orange module_refresh'><i class="fas fa-sync"></i></button>
								</div>
							</div>							
						</div>

						
						<div class="row">
							<div class="col_12">
								<ul class="unstyle">
									<?php foreach($ob->readAll() as $k=>$v){
										echo "<li> <a href='#list' class='listview_module' data-module='".$k."'> <i class='far fa-caret-square-right'></i> ".$k."</a></li>";
									}
									 ?>
								</ul>									
							</div>
					
						</div>						
						
					</div>


				</div>

				<div class="col_2-inline" style="padding: 0; height: 450px">
					<div class="styles" style="overflow: auto; height: 100%">
						<h3 style="margin-left: 7px">Styles</h3>
					</div>
					
				</div>

				<div class="col_8-inline" style="padding: 0; height:auto">
					<div class="definitions">
						<h3 style="margin-left: 7px">Définitions</h3>
					</div>
					
				</div>

			</div>			
		</div>
	</div>

	
	
	<div class="row hide">
		<div class="col_2">
			<select id='listview_module'>
				<option value="">  </option>

			<?php foreach($ob->readAll() as $k=>$v){
				echo "<option value='".$k."'>".$k."</option>";
			}
			 ?>
			</select>
		</div>
		
		<div class="col_2 listview_name"></div>
	</div>
		
	<div class="debug"></div>
