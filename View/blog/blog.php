	<title>Blog | Sedios</title>

	<link rel="stylesheet" href="/assets/css/blog.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-pencil"></i> Quoi de neuf ?</div><br>
		<?php

			$parsedown = new Parsedown();

			if(!isset($page)){
				$page = 1;
			}
			$f = BlogController::listEntriesByPage($page);
			$entry = 0;

			while($entry != count($f)){
				echo "<article id='main'>";
				echo "<div id='name'>".$f[$entry]->title."</div>";
				echo "<hr>";
				echo "<div id='info'><i class='fa fa-user'></i> <a href='/profile/".$f[$entry]->author."'>".$f[$entry]->author."</a> | <i class='fa fa-slack'></i> ".BlogController::getCategory($f[$entry]->category)." | <i class='fa fa-floppy-o'></i> ".date('d/m/y H:i', $f[$entry]->time)." | <i class='fa fa-envelope-o'></i> ".BlogController::countComments($f[$entry]->id)."</div>";
				echo "<hr>";
				echo "<div id='desc'>".$parsedown->text(BlogController::short($f[$entry]->content, 32))."</div>";
				echo "<a href='/blog/".$f[$entry]->id."-".$f[$entry]->slug."'><button>Voir l'article <i class='fa fa-arrow-right'></i> </button></a>";
				echo "</article>";
				$entry++;
			}

		?>
		<div style="clear:both;"></div>
		<?php include "./Elements/blog/pagination.php"; ?>
	 
