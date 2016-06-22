	<title>Jeux Vidéo | TV | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/live.css">
</head>
<body>

	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-gamepad"></i> Jouons en live !</div><br>
		<div id="share"><i class="fa fa-share-alt"></i> <a href="https://twitter.com/share" class="twitter-share-button" data-text="Je regarde un live !" data-via="SediosTV" data-lang="fr" data-hashtags="SediosLive">Tweeter</a></div>
		
		<div id="display">
		<?php if(LiveController::isOnAir("Jeux")){ ?>
			<?php
				$_SESSION['tv'] = "Jeux";
			?>
			<div class="wrapper">
				<div id="live">
		          	<iframe frameborder="0" width="100%" height="100%" src="http://www.dailymotion.com/embed/video/x1qipjc?autoPlay=1" allowfullscreen></iframe>
		        </div>
			</div>
			
	        <div id="chat">
		    	<iframe src="/chat/index.php" width="100%" height="100%" style="border:none;background-color:white;"></iframe>
		    </div>
		    <?php }else{ $_SESSION['tv'] = "WaitingRoom"; ?>
        		<center><iframe frameborder="0" width="100%" height="620px" id="game" src="/Tetris/index.html"></iframe> <iframe frameborder="0" width="323px" height="610px" src="/chat/index.php"></iframe></center>
        	<?php } ?>
        </div>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>