<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } 

$core = $_SESSION["CORE"];
$table_name = "Menu";
require_once($core.$table_name.".php");  
$ob = new $table_name();
?>

	<div class="row page_title">
		<div class="col_6-inline icon">
			<i class="fas fa-address-card"></i> <?= $table_name ?>(s)
		</div>
		<div class="col_6-inline actions">
			<button class="btn btn-green _add" value="<?= $table_name ?>"><i class="fas fa-plus" aria-hidden="true"></i></button>
			<button class="btn btn-default refresh" value="<?= $table_name ?>"><i class="fas fa-sync-alt"></i></button>
		</div>
	</div>
	<hr>

<style>
	a.__sub, a.__menu{
		text-decoration: none;
		color: inherit;
		transition: all .5s
	}
	a.__menu:hover, a.__sub:hover{
		padding-left: 10px;
	}
	.__menu{
		padding: 8px 0 0 20px;
		border: #979696 1px solid;
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f6f8f9+0,e5ebee+50,d7dee3+51,f5f7f9+100;White+Gloss */
		background: rgb(246,248,249); /* Old browsers */
		background: -moz-linear-gradient(top, rgba(246,248,249,1) 0%, rgba(229,235,238,1) 50%, rgba(215,222,227,1) 51%, rgba(245,247,249,1) 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(top, rgba(246,248,249,1) 0%,rgba(229,235,238,1) 50%,rgba(215,222,227,1) 51%,rgba(245,247,249,1) 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom, rgba(246,248,249,1) 0%,rgba(229,235,238,1) 50%,rgba(215,222,227,1) 51%,rgba(245,247,249,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f6f8f9', endColorstr='#f5f7f9',GradientType=0 ); /* IE6-9 */
		display: grid;
		grid-template-columns: 25px 1fr 190px;
		line-height: 35px;
		margin-top: 10px;
		font-size: 12px;
		font-weight: bold;
	}
	.__menu div.icon, .__sub div.icon{
			padding-top: 6px;
			font-size: 20px;
		}
	.__sub div.icon{
			padding-top: 0px;
			font-size: 10px
		}
	.__sub{
		display: grid;
		grid-template-columns: 25px 1fr 150px;
		line-height: 35px;
		background-color: #ededed;
		padding: 15px 10px 15px 20px;
		border-bottom: 1px #B1B1B1 solid;
		font-size: 12px;
		font-weight: normal;
	}
</style>

<div class="row <?= $table_name ?>">
	<?php $ob->drawTable_2(); ?>		
</div>

	
	
<div class="debug"></div>

