<br>
<?php 
	$pseudo = $_SESSION['Auth']['user']['name'];
	if(UserController::getLevelOf($pseudo) == 4){
?>
<br>
	<center>Il y a actuellement <b><?= Database::rowCount("SELECT id FROM sedios_users"); ?></b> utilisateurs inscrits (Le dernier est <i><?= Database::query("SELECT name FROM sedios_users WHERE valid = 1 ORDER BY created DESC LIMIT 0,1")->name; ?></i> ).</center>
<br><br>
<fieldset style="width:700px;margin:auto;">
	<legend>Gestion des chaines</legend>
	<div id="form">
		<h2>Créer une chaine</h2>
		<form method="POST" action="/admin/create/channel">
			<label for="pseudo">Nom de la chaine :</label><input type="text" id="name" name="name">
			<br>
			<label for="player">ID du player :</label><input type="text" id="player" name="player">
			<br>
			<label for="streamkey">Clé de stream :</label><input type="text" id="streamkey" name="streamkey">
			<br>
			<label for="chat">ID du chat :</label><input type="text" id="chat" name="chat">
			<br>
			<label for="users">Utilisateurs (séparés par ", ") :</label><input type="text" id="users" name="users">
			<br><br>
			<input type="submit" value="Créer la chaine">
		</form>
	</div>
</fieldset>
<br>
<div id="form">
		<h2>Changer l'accès d'un utilisateur</h2>
		<form method="POST" action="/admin/access">
			<label for="pseudo">Pseudo :</label><input type="text" id="pseudo" name="pseudo">
			<br>
			<label for="access">Niveau :</label>
			<select name="access" id="access"><option value="0">Utilisateur</option><option value="1">Rédacteur</option><option value="2">Streamer</option><option value="5">Membre d'honneur</option><option value="3">Modérateur</option><option value="4">Administrateur (déconseillé)</option></select>
			<br><br>
			<input type="submit" value="Changer son niveau d'accès">
		</form>
</div>
<br>
<div id="form">
		<h2>Maintenance</h2>
		<form method="POST" action="/admin/set/maintenance">
			<label for="access">Niveau :</label>
			<select name="access" id="access"><option value="0">Le site est libre</option><option value="1">Petite modification</option><option value="2">Cas d'extrême urgence (déconseillé)</option></select>
			<br><br>
			<input type="submit" value="Maintenance Time !">
		</form>
</div>
<?php } ?>