	<title>Nouveau sujet | Forum | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>

	<div class="container"><?php Controller::inMaintenance(); ?>
	<div id="title"><i class="fa fa-tag"></i> Nouveau sujet</div><br>
		<div id="thread"><br>
			<form method="post" action="/post/thread">
			<input type="hidden" value="<?= $sid ?>" name="c">
				<center><label for="titre" required>Titre :</label><br>
				<input type="text" name="title" id="titre"><br>
				<label for="co" required>Contenu :</label><br>
				<textarea name="content" id="co"></textarea><br><br>
				<input type="submit" value="Poster le sujet"></center>
			</form>
		</div>
		<div style="clear:both;"></div>
		<br>
	 