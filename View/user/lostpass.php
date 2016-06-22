	<title>Mot de passe oublié | Sedios</title>

	<link rel="stylesheet" href="/assets/css/contact.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-users"></i> Mot de passe perdu ?</div><br>
		<div id="form"><br>
			<?php Controller::getAlert('user'); ?>
			<form method="post" action="/post/lostpass">
				<label for="pseudo">Pseudo</label> 		<input type="text" id="pseudo" name="pseudo" maxlength="255" required><br>
				<label for="email"> E-Mail</label>		<input type="email" id="email" name="email" required><br>
				
				<br>
				<center><input type="submit" value="Nouveau mot de passe !"></center>
			</form>
		</div>
	 
