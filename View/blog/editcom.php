	<title>Edition de commentaire | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="comments">
			<div id="title"><i class="fa fa-users"></i> Editer un commentaire</div><br>
			<form method="post" action="/post/editcom/<?= $id ?>">
				<label for="content">Votre message :</label> 	 <textarea id="content" name="content" required><?= $q->content; ?></textarea><br>
				<br>
				<center><input type="submit" value="Envoyer mon commentaire"></center>
			</form>
		</div>
		 
	</div><!-- Closing .container -->