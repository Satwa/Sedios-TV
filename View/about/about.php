	<title>A Propos | Sedios</title>

	<link rel="stylesheet" href="/assets/css/about.css">
</head>
<body>

	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-info"></i> à Propos</div>
		<div id="content">
			<h2>Pourquoi réaliser une WebTV ?</h2>
				Nous (membres de l'équipe, collégiens & lycéens) avons décidé de créer une WebTV ainsi qu'un blog pour pouvoir partager notre passion à chacun.
				<br>
				Un forum est en place pour vous permettre à vous aussi de partager votre passion à votre tour !
				<br>
				Le site est donc composé ainsi : Un blog, une WebTV et un forum. Chacun ayant sa spécificité. 
				<br><br>
				Je m'explique : 
				<br>
				<ul>
					<li>Le blog (Nous partageons et échangeons notre passion par écrit)</li>
					<li>La WebTV (Nous jouons ou programmons avec vous en direct, en mode détente ou sérieux.)</li>
					<li>Le forum (Il vous permettra de partager entre-vous (et avec nous aussi !) votre passion et délibérer sur différents sujets que <b>VOUS</b> aurez choisi !)</li>
				</ul>

			<h2>Comment nous aider à survivre ?</h2>
			Il n'y a qu'un seul moyen : Désactiver Adblock !<br>
			L'argent gagné via les pubs des live nous permettent de payer nos serveurs.<br>
			Il vous suffit de couper le son de la pub par exemple pour ne pas qu'elle vous dérange. Cela ne vous coûte rien !<br>
			Incroyable non ?<br>
			Vous êtes bien-sûr inviter à partager le site si celui-ci vous plaît :)
			<br>
			<h2>Avec quoi le site est-il fait ?</h2>
			<ul>
				<li>Le site est codé en HTML, CSS et Javascript pour le frontend (ce que vous voyez la, le rendu visuel donc) et en PHP pour le backend (ce qui vous permet de lire le blog, vous inscrire, aller sur le forum, ...).</li>
				<li>Le site utilise une structure <i>MVC</i>  aménagée (Il y a juste des Views et des Controllers).</li>
				<li>La réecriture d'URL est gérée par AltoRouter (Une classe PHP).</li>
				<li>Le stockage de donnée est géré par un serveur MySQL</li>
				<li>Les live sont réalisés par l'intermédiaire de Dailymotion et le chat est réalisé <i>maison</i>  (Du bon vieux NodeJS !)</li>
				<li><b>Toute la structure du site est fièrement hébergée chez <a href="http://www.ovh.com/fr" target='_blank'>OVH</a>.</b></li>
			</ul>

			<h2>Comment tu fais pour sauvegarder le site en toute sécurité ?</h2>
			Pendant la phase de développement, le site est sauvegardé via <a href="http://fr.wikipedia.org/wiki/Git">Git</a> (sur un repo <a href="http://www.bitbucket.org/" target="_blank">Bitbucket</a> privé :p).<br>
			En production, de multiples tâches <a href="http://fr.wikipedia.org/wiki/Cron">cron</a> sont effectuées afin de gérer les différentes tâches automatiquement (sauvegarde, gestion des bannis, ...).
			<br>

			<h2>Qui sont les membres de l'équipe ?</h2>
			Voici la liste des membres de l'équipe ainsi que leur grade (afin d'éviter toute usurpation d'identité !) :<br><br>
			<?php
				$q = Database::queryAll("SELECT name,level FROM sedios_users WHERE level > 0 ORDER BY level DESC");

				foreach($q as $u){
					$c = Controller::getDecoByLevel($u->name);

					echo "<a href='/profile/{$u->name}'><div id='team' title='{$c['title']}'>";
						echo "<div id='level'><i class='fa fa-{$c['icon']}'></i> </div>".$u->name;
						echo "<div id='go'><i class='fa fa-arrow-right'></i> </div>";
					echo "</div></a>";
				}
			?>
			<div style='clear:both'></div>
		</div>
	 
