	<title>Edition de sujet | Forum | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="comments">
			<div id="title"><i class="fa fa-users"></i> Editer un sujet</div><br>
			<form method="post" action="/post/editthread/<?= $id ?>">
				<label for="titre">Titre :</label> <input type="text" id="titre" name="title" value="<?= $q->title; ?>">
				<label for="content">Votre message :</label> 	 <textarea id="content" name="content" required><?= $q->content; ?></textarea><br>
				<br>
				<center><input type="submit" value="Valider la modification"></center>
			</form>
		</div>
		 <br>
	</div><!-- Closing .container -->