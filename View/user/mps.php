	<title>Messagerie Privée | Sedios</title>

	<link rel="stylesheet" href="/assets/css/member.css">

	<script src="/assets/js/jquery.livestamp.js"></script>
	<script src="/assets/js/forum.js"></script>
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-user"></i> Espace membre</div><br>

		<center>
			<a href="/member"><div id="mp">Accueil membre</div></a>
			<a href="/mp"><div id="mp">Boite de réception</div></a>
			<a href="/mps"><div id="mp">Messages envoyés</div></a>
		</center>
		<br><br>

		<?php $f = UserController::privatesend($page); ?>

		<div id="form">
			<table>
				<thead>
					<td id="head">Titre</td> <td id="head">Destinataire</td> <td id="head">Date de publication</td>
				</thead>
				<?php
					foreach($f as $t){
						echo "<tr>";
							echo "<td>";
								echo "<a href='/mp/show/".$t->id."'>".$t->subject."</a>";
							echo "</td>";
							echo "<td>";
								echo "<a href='/profile/".$t->sender."'>".$t->sender."</a>";
							echo "</td>";
							echo "<td>";
								echo "<span data-livestamp='".$t->posted."' style='color:gray;'></span>";
							echo "</td>";
						echo "</tr>";
					}
				?>	
			</table>
		</div>
		<br>
		<?php include "./Elements/users/mp/pagination_mps.php"; ?>
	 
