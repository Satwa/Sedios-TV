	<title>Edition | Admin | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/admin.css">
	<link rel="stylesheet" href="/assets/css/member.css">
</head>
<body>
<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?><br>
	<link rel="stylesheet" href="/assets/css/mdeditor.css">

	<?php
		$q = Database::query("SELECT * FROM sedios_blog WHERE id = $id");
		if($q->comments == 1){
			$com = "checked='checked'";
		}else{
			$com = "";
		}
		if($q->visibility == 1){
			$visi = "checked='checked'";
		}else{
			$visi = "";
		}
	?>

	<div id="form">
		<h2>Editer</h2>
		<form method="post" action="/admin/editpost">
			<input type="text" name="id" value="<?= $id ?>" hidden="hidden"><br>
			<label for="titre">Titre</label>  <input type="text" id="titre" name="title" value="<?= $q->title ?>" required><br>
			<label for="cat">Catégorie</label> <select name="cat" id="cat" required><?php AdminController::listCategories(); ?></select><br><br>
			<label for="contenu">Contenu</label> <br><br> <textarea id="contenu" style="background:#FFF;" name="content" cols="30" rows="10"><?= $q->content ?></textarea>
			<input type="checkbox" name="comment" value="accept" <?= $com ?>>Autoriser les commentaires<br>
			<input type="checkbox" name="visible" value="accept" <?= $visi ?>>Publier maintenant
			<br>
			<input type="submit" value="Envoyer">
		</form>
	</div>
	<?= "<script>$('select option[value=\"".$q->category."\"]').attr(\"selected\",true);</script>" ?>
	<script src="/assets/js/mdeditor.js"></script>
	<script src="/assets/js/a_write.js"></script>
	 