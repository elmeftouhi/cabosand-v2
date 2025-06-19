<?php session_start(); ?>
<div class="row">
	<div class="panel">
		<div class="panel-header">Librarygggg
			<div class="panel-header-right"><button class="btn btn-default close_this">X</button></div>
		</div>
		<div class="panel-content">
			<?php
			echo($_SESSION["STATICS"]);
			
			?>
			<br clear="all">
		</div>
		<div class="panel-footer">
			footer
		</div>
	</div>
</div>