	<title>Rechercher | Sedios</title>

	<link rel="stylesheet" href="/assets/css/search.css">
	<link rel="stylesheet" href="/assets/css/blog.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-search"></i> Rechercher</div><br>
			<div id="search">
				<input type="text" placeholder="Rechercher" id="res">
			</div>
			<br>	<br>
			<div id="results"></div>
			<br>	<br>
	 

<script src="/assets/js/search.js"></script>