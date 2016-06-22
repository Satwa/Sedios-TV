	<title>Espace membre | Sedios</title>

	<link rel="stylesheet" href="/assets/css/member.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-user"></i> Espace membre</div><br>
		<div id="form">
			<form method="post" action="/post/delete">
				<center><input type="submit" value="Supprimer mon compte"></center>
			</form>
			<br>
		</div>
	 
