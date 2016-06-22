	<title>Espace membre | Sedios</title>

	<link rel="stylesheet" href="/assets/css/member.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-user"></i> Espace membre</div><br>

		<center><div id="arrow-mp"></div><a href="/mp"><div id="mp">Messagerie Privée</div></a><div id="mp-arrow"></div></center><br><br>

		<div id="form">
				<?php Controller::getAlert('pass'); ?>
			<h2>Modifier mes informations</h2>
			<form method="post" action="/update">
				<textarea placeholder="Description" name="desc"><?= UserController::getDesc($_SESSION['Auth']['user']['name']); ?></textarea>
				<br>
				<input type="password" placeholder="Mot de passe" name="pass1">
				<br>
				<input type="password" placeholder="Mot de passe" name="pass2">
				<br>
				<input type="submit" value="Modifier les informations">
			</form>
		</div>
		<br><br>
		<?php
			include "Elements/admin/channel_rights.php";
			include "Elements/admin/1_redac.php";
			include "Elements/admin/4_admin.php";
		?>
		<a href="/delete" id="delete">Supprimer mon compte</a>
	 
