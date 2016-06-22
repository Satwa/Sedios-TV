	<title>Replay | Sedios</title>

	<link href='/assets/css/live.css' rel='stylesheet'>
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-spinner"></i> Visionner un replay</div><br>
		
			<?php
				if($p["type"] == "error"){
					echo "<div id='error'>Erreur !<br> Ce replay n'existe pas</div><br><br>";
				}else{
			?>
				<center>
					<div class="wrapper">
						<div id="live">
				          	<iframe frameborder="0" width="100%" height="100%" src="http://www.dailymotion.com/embed/video/<?= $vod ?>?autoPlay=1" allowfullscreen></iframe>
				        </div>
					</div>
				</center>
				<?php if(Controller::isAuth()){ ?>
					<div id="avis">
						<div id="cut2" class="like">   <i class="fa fa-thumbs-up"></i>   J'aime		   (<span id='nblike'><?= $like ?></span>)</div>
						<div id="cut2" class="dislike"><i class="fa fa-thumbs-down"></i> Je n'aime pas (<span id='nbdis'><?= $dislike ?></span>)</div>
					</div>
					<?php if(!LiveController::hasVoted($vod, $_SESSION['Auth']['user']['name'])){ echo "<script> var pseudo = '".$_SESSION['Auth']['user']['name']."';var vod = '".$vod."'; </script>"; ?><script src="/assets/js/likevod.js"></script><?php } ?>
				<?php
					}}
				?>
			<div style='clear:both;'></div>
	
