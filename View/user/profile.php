	<title><?= $user; ?> | Sedios</title>

	<link rel="stylesheet" href="/assets/css/member.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<?php if(!$u = UserController::getUserByName($user)){Controller::redirect('/');} ?>
	<?php 
		$c = Controller::getDecoByLevel($u->name);
	?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-user"></i> Profil de <?= $user ?></div><br>
		<div id="form"><br>
			<h1><i class="fa fa-<?= $c['icon'] ?>" title="<?= $c['title']  ?>"></i> <?= $user ?></h1>
			Inscrit le <?= date('d/m/y à H:i', $u->created); ?><br>
			Dernière connexion le <?= date('d/m/y à H:i', $u->lastlogin); ?><br>
			<center><h2><b>Description</h2></b></center>
			<br>
			<?= nl2br($u->description); ?>
		</div>
	 
