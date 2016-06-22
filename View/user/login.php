	<title>Connexion | Sedios</title>

	<link rel="stylesheet" href="/assets/css/contact.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-users"></i> Connexion</div><br>
			<div id="form"><br>
				<?php Controller::getAlert('user'); ?>
				<form method="post" action="/post/login">
					<label for="pseudo">  Pseudo	  </label> 		<input type="text" id="pseudo" name="pseudo" maxlength="255" required><br>
					<label for="password">Mot de passe</label>   	<input type="password" id="password" name="password" required><br>
					
					<br>
					<center><input type="submit" value="Connexion"></center>
				</form>
				<center>Vous souhaitez vous inscrire ? <a href="/register">Cliquez-ici</a> !</center>
				<center>Vous avez oublié votre mot de passe ? <a href="/lostpass">Trouvez-le ici</a> !</center>
			</div>
	 
