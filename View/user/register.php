	<title>S'inscrire | Sedios</title>

	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link rel="stylesheet" href="/assets/css/contact.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-users"></i> S'enregistrer</div><br>
		<div id="form"><br>
			<?php Controller::getAlert('user'); ?>
			<form method="post" action="/post/register">
				<label for="pseudo">Votre pseudo</label> 		 	<input type="text" id="pseudo" name="pseudo" maxlength="255" value="<?php if(!empty($_SESSION['user'])){echo $_SESSION['user']["pseudo"];} ?>" required><br>
				<label for="email">Votre adresse mail</label>   	<input type="email" id="mail" name="mail" value="<?php if(!empty($_SESSION['user'])){echo $_SESSION['user']["mail"];} ?>" required><br>
				<label for="pass1">Votre mot de passe</label>       <input type="password" id="pass1" name="pass1" value="" required><br>
				<label for="pass2">Répétez-le</label>  				<input type="password" id="pass2" name="pass2" value="" required><br>
				
				<input type="checkbox" name="cguaccept" value="accept"><span>J'accepte les <a href="/cgu" target="_blank">CGU</a></span>
				<center><div class="g-recaptcha" data-sitekey="6LcQgP8SAAAAAGIZV5tYd431C5xkrsc8cgnkspv6"></div>
				
				<input type="submit" value="S'inscrire"></center>
			</form>
			<center>Déjà inscrit ? <a href="/login">Cliquez-ici</a> !</center>
		</div>
	 
