	<title><?= $f->title ?> | Voir un article | Sedios</title>

	<link rel="stylesheet" href="/assets/css/blog.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<?php
			$parse = new Parsedown();

			$f = $req;

			if(empty($f)){
				Controller::redirect('/blog');
			}

			$pid = $f->id;

		?>
		<article>
			<div id="name"><?= $f->title; ?></div>
			<hr>
				<div id="info"><i class="fa fa-user"></i> <a href='/profile/<?= $f->author; ?>'><?= $f->author; ?></a> | <i class="fa fa-slack"></i> <?= BlogController::getCategory($f->category); ?> | <i class="fa fa-floppy-o"></i> <?= date('d/m/y H:i', $f->time); ?> | <i class="fa fa-envelope-o"></i>  <?= BlogController::countComments($pid); ?></div>
			<hr>
			<div id="content"><?= $parse->text($f->content); ?></div>	

			<div id="share"><i class="fa fa-share-alt"></i> <a href="https://twitter.com/share" class="twitter-share-button" data-text="Venez découvrir un article !" data-via="SediosTV" data-lang="fr">Tweeter</a></div>
		</article>

		<?php include "./Elements/blog/comform.php"; ?>
		<br>
		<?php include "./Elements/blog/comment.php"; ?>
		<br><br>
	 

		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>