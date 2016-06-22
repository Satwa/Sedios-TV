	<title><?= $title ?> | Forum | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">
	<style>a{text-decoration: none;}</style>
</head>
<body>
	<?php include "Elements/header.php"; ?>

	<?php 
		if(UserController::getLevelOf($t->author) == 1){
			$icon = "edit";
			$it = "Rédacteur";
		}elseif(UserController::getLevelOf($t->author) == 2){
			$icon = "videocam";
			$it = "Streamer";
		}elseif(UserController::getLevelOf($t->author) == 3){
			$icon = "beer";
			$it = "Modérateur";
		}elseif(UserController::getLevelOf($t->author) == 4){
			$icon = "star";
			$it = "Administrateur";
		}
	?>

	<div class="container"><?php Controller::inMaintenance(); ?>
		<?php
			$id = $t->id;
			$a = Database::query("SELECT authorize FROM sedios_forum_thread WHERE id = $id")->authorize;
			if(Controller::isAuth() && UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 2 && $a == 1){
		?>
				<a href="/forum/close/<?= $id[0]; ?>"><div id="new">Fermer le thread</div></a>
		<?php
			}
		?>
		<div style="clear:both;"></div>
		<div id="thread">
			<div id="info">
			<h3><?= $title ?></h3>
				<hr>
					<i class="fa fa-user"></i> <a href='/profile/<?= $t->author; ?>'><i class="fa fa-<?= $icon; ?>" title="<?= $it; ?>"></i>  <?= $t->author; ?></a> | <i class="fa fa-slack"></i> <?= ForumController::getNameOfCat($t->cat); ?> | <i class="fa fa-floppy-o"></i> Le <?= date('d/m/y à H:i', $t->posted); ?>
					<?php
						if(Controller::isAuth()){
							$pseudo = $_SESSION['Auth']['user']['name'];
							if(UserController::getLevelOf($pseudo) >= 3 || $t->author == $pseudo){
								echo "<a href='/forum/deletethread/".$t->id."' id='delete' style='color:darkred;'>Supprimer</a>";
							}
						}
					?>
				<hr>
			</div>
			<div id="content">
				<?= nl2br(htmlentities(stripslashes($t->content))); ?>
				<?php
					if(Controller::isAuth()){
						$pseudo = $_SESSION['Auth']['user']['name'];
						if(UserController::getLevelOf($pseudo) >= 3 || $t->author == $pseudo){
							echo "<br><br><hr>";
							echo "<a href='/thread/edit/".$t->id."' id='edit'>Editer</a>";
							echo "<div style='clear:both'></div>";
						}
					}
				?>
			</div>
			
		</div>
		<div style="clear:both;"></div>
		<br>
		<?php include "./Elements/forum/replies.php"; ?>
		<?php include "./Elements/forum/replyform.php"; ?>
		<br><br>
		<?php include "./Elements/forum/pagination_thread.php"; ?>
	 