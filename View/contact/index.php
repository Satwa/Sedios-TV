	<title>Contact | Sedios</title>
	
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link rel="stylesheet" href="/assets/css/contact.css">
</head>
<body>

	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-envelope-o"></i> Contactez-nous !</div>
		
		<div id="contact"><br>
			<div id="i"><?php Controller::getAlert('mail'); ?></div>
			<form method="post" action="/post/mail">
				<label for="name">Votre nom</label> 		 	<input type="text" id="name" name="name" maxlength="255" value="<?php if(!empty($_SESSION['mail'])){echo $_SESSION['mail']["name"];} ?>" required><br>
				<label for="subject">Sujet du mail</label>   	<input type="text" id="subject" name="subject" value="<?php if(!empty($_SESSION['mail'])){echo $_SESSION['mail']["subject"];} ?>" required><br>
				<label for="email">Votre adresse mail</label>   <input type="email" id="email" name="email" value="<?php if(!empty($_SESSION['mail'])){echo $_SESSION['mail']["email"];} ?>" required><br>
				<label for="content">Votre message</label>   	<textarea id="content" name="content" required><?php if(!empty($_SESSION['mail'])){echo $_SESSION['mail']["content"];} ?></textarea><br>
				<br>
				<center><div class="g-recaptcha" data-sitekey="6LeFTv8SAAAAAPwW9lacanAWUKVqx50ffelM8gVw"></div>
				
				<input type="submit" id="submit" value="Envoyer le mail"></center>
			</form>
		</div>
	 
	<script src="/assets/js/contact.js"></script>