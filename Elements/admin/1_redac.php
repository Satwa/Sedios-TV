<?php
	$pseudo = $_SESSION['Auth']['user']['name'];
	if(UserController::getLevelOf($pseudo) > 0){
?>
<link rel="stylesheet" href="/assets/css/mdeditor.css">
<fieldset style="width:700px;margin:auto;">
	<legend>La rédac'</legend>

	<div id="form">
		<h2>Ecrire</h2>
		<form method="post" action="/admin/newpost">
			<label for="titre">Titre</label>  <input type="text" id="titre" name="title" required><br>
			<label for="cat">Catégorie</label> <select name="cat" id="cat" required><?php AdminController::listCategories(); ?></select><br><br>
			<label for="contenu">Contenu</label> <br><br> <textarea id="contenu" style="background:#FFF;" name="content" cols="30" rows="10">Contenu</textarea>
			<label for="comment">Autoriser les commentaires</label> <input type="checkbox" id="comment" name="comment" value="accept" checked="checked"><br>
			<label for="publish">Publier maintenant</label> <input type="checkbox" id="publish" name="visible" value="accept" checked="checked"><br>
			<input type="submit" value="Envoyer">
		</form>
	</div>
	
	<br><br>
	
	<div id="form">
		<a href="/admin/listpost"><h2>Edition</h2></a>
	</div>
</fieldset>
<script src="/assets/js/mdeditor.js"></script>
<script src="/assets/js/a_write.js"></script>
<?php } ?>