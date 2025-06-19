<?php if( !defined ( "CORE" ) ) { die("error!"); } ?>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>404</h1>
			</div>
			<h2><?= $CONTENT["message_1"] ?></h2>
			<p><?= $CONTENT["message_2"] ?></p>
			<a href="<?= HTTP.HOST ?>"><?= $CONTENT["message_3"] ?></a>
		</div>
	</div>