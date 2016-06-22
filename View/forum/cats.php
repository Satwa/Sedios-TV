	<title><?= ForumController::getNameOfCat($id[0]); ?> | Forum | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">

	<script src="/assets/js/jquery.livestamp.js"></script>
	<script src="/assets/js/forum.js"></script>
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<?php
		$f = ForumController::listThreadsByPage($page, $id[0]);
	?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-tag"></i> <?= ForumController::getNameOfCat($id[0]); ?></div><br>
				<?php
					if(Controller::isAuth()){
				?>
						<a href="/forum/new/<?= $id[0]; ?>"><div id="new">Nouvelle discussion</div></a>
				<?php
					}
				?>
			<div id="cats">
				<table>
					<thead>
						<td id="head">Titre</td> <td id="head">Auteur</td> <td id="head">Date de publication</td>
					</thead>
					<?php
						foreach($f as $t){
							echo "<tr>";
								echo "<td>";
									echo "<a href='/forum/thread/".$t->id."'>".$t->title."</a>";
								echo "</td>";
								echo "<td>";
									echo "<a href='/profile/".$t->author."'>".$t->author."</a>";
								echo "</td>";
								echo "<td>";
									echo "<span data-livestamp='".$t->posted."' style='color:gray;'></span>";
								echo "</td>";
							echo "</tr>";
						}
					?>	
				</table>
			</div>
			<br><br>
			<?php include "./Elements/forum/pagination.php"; ?>
	 
