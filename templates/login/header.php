<html xmlns="http://www.w3.org/1999/xhtml" dir="<?= $dir ?>" lang="<?= ($lang=="")? "fr" : $lang ?>">

<head>
	<?php $CONTENT = Util::getLanguageContent($lang, "content"); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	
	<title>Manager | Cabodand</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

	<link href="<?= HTTP.HOST ?>templates/global/css/api/Ycss-1.1.1.css" rel="stylesheet">
	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/app.css?version=1.0.2" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?= HTTP.HOST ?>templates/default/images/manager-icon.png" />	

</head>
<body>

<div class="wrapper"> <!-- START THE MAIN CONTAINER / WRAPPER -->