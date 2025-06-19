<html xmlns="http://www.w3.org/1999/xhtml" dir="<?= $dir ?>" lang="<?= ($lang=="")? "fr" : $lang ?>">

<head>
	<?php $CONTENT = Util::getLanguageContent($lang, "content"); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:500" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:700,900" rel="stylesheet">
	<title><?= $CONTENT["name"]." : ".$CONTENT["description"] ?></title>

	<link href="<?= HTTP.HOST ?>templates/<?= APP_TEMPLATE ?>/css/app.css" rel="stylesheet">
		
	
	<link rel="icon" type="image/png" href="<?= HTTP.HOST ?>templates/global/images/logo.png" />	
		

</head>
<body>
