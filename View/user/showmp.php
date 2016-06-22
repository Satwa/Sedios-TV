	<title>Messagerie Privée | Sedios</title>

	<link rel="stylesheet" href="/assets/css/member.css">

	<script src="/assets/js/jquery.livestamp.js"></script>
	<script src="/assets/js/forum.js"></script>
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<?php
			if($f['pos'] == "send"){$s = "Envoyé à";}else{$s = "Reçu de";}
		?>
		<div id="title"><i class="icon-user"></i> <?= $s ?> <?= $f['sender']; ?></div><br>

		<center>
			<a href="/member"><div id="mp">Accueil membre</div></a>
			<a href="/mp"><div id="mp">	   Boite de réception</div></a>
			<a href="/mps"><div id="mp">   Messages envoyés</div></a>
		</center>
		<br><br>

		<div class="comment">
			<span><a href='/profile/<?= $f['sender']; ?>' style="color:lightgray;"><?= $f['sender']; ?></a></span>
			<hr>
				<?= $f['message']; ?>
			<hr>
			<span id="right"><i class='icon-floppy'></i> <?= date('d/m/y à H:i', $f['posted']); ?></span>
			<div style="clear:both;"></div>
		</div>

	 
