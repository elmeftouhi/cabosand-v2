<html xmlns="http://www.w3.org/1999/xhtml" dir="<?= $dir ?>" lang="<?= ($lang=="")? "fr" : $lang ?>">

<head>
	<?php $TOPBAR = Util::getLanguageContent($lang, "topbar"); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	-->
	<title><?= $TOPBAR["name"]." : ".$TOPBAR["description"] ?></title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.1/tailwind.min.css" integrity="sha512-biy/TXdue7ElI4oop0vK1o0JVMwDtG2AeA1VEqJU3Z6LqZMMi6KTbc2ND1MC557MijurEJSPDVHV3WgwBgF1Pw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
	<link href="<?= HTTP.HOST ?>templates/global/css/api/Ycss-1.1.1.css" rel="stylesheet">
	<link href="<?= HTTP.HOST ?>templates/global/css/api/sweetalert2.min.css" rel="stylesheet">
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/app.css?version=<?= time() ?>" rel="stylesheet">
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/list.css?version=<?= time() ?>" rel="stylesheet">
	<!-- SUPPORT -->
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/support.css?version=<?= time() ?>" rel="stylesheet">
	<!-- END SUPPORT -->
	
	<!-- CALENDAR -->
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/calendar.css?version=<?= time() ?>" rel="stylesheet">
	<!-- END CALENDAR -->
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/manager.css?version=<?= time() ?>" rel="stylesheet">
	
	<link rel="icon" type="image/png" href="<?= HTTP.HOST ?>templates/default/images/manager-icon.png" />

</head>
<body>
