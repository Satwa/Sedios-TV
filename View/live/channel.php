	<title><?= $user ?> | TV | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/live.css">
</head>
<body>

	<?php include "Elements/header.php"; ?>
	<?php if(!LiveController::exist($user)){Controller::redirect('/live');} ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-gamepad"></i> Regardez <?= $user ?> en live !</div><br>
		<div id="share"><i class="fa fa-share-alt"></i> <a href="https://twitter.com/share" class="twitter-share-button" data-text="Je regarde un live !" data-via="SediosTV" data-lang="fr" data-hashtags="SediosLive">Tweeter</a></div>
		
		<div id="display">
		<?php if(LiveController::isOnAir($user)){ ?>
			<?php
				$_SESSION['tv'] = LiveController::getChannelOf($user);
			?>
			<div class="wrapper">
				<div id="live">
		          	<iframe frameborder="0" width="100%" height="100%" src="http://www.dailymotion.com/embed/video/<?= $u->playerid; ?>?autoPlay=1" allowfullscreen></iframe>
		        </div>
			</div>

	        <div id="chat">
		    	<iframe src="/chat/index.php" width="100%" height="100%" style="border:none;background-color:white;"></iframe>
		    </div>
		    <?php }else{ $_SESSION['tv'] = "WaitingRoom"; ?>

        		<center><iframe frameborder="0" width="100%" height="620px" id="game" src="/Tetris/index.html"></iframe><iframe frameborder="0" width="323px" height="610px" src="/chat/index.php"></iframe></center>
        	<?php } ?>
        </div>
		
		<div id="rs">
			<a href="<?php if($hasFb){echo "http://www.facebook.com/".$u->fb;}else{echo "#";} ?>" target="_blank">
				<div id="cut3" class="fb"><i class="fa fa-facebook"></i> </div>
			</a>
			<a href="<?php if($hasTw){echo "http://www.twitter.com/".$u->twitter;}else{echo "#";} ?>" target="_blank">
				<div id="cut3" class="tw"><i class="fa fa-twitter"></i> </div>
			</a>
			<a href="<?php if($hasYt){echo "http://www.youtube.com/user/".$u->yt;}else{echo "#";} ?>" target="_blank">
				<div id="cut3" class="yt"><i class="fa fa-youtube"></i> </div>
			</a>
		</div>

        <div id="programs">
			<?php
				$p = LiveController::nextPrograms($user);

				foreach($p as $s){
					if($s->channel == "main"){
						$s->channel = "";
					}else{$s->channel = "/".$s->channel;}
					echo "<a href='/live".$s->channel."'><div id='program'>";
						echo "<div id='title'>".$s->title."</div>";
						echo "<div id='time'>".date('d/m à H:i', $s->hour)."</div>";
						echo "<div style='clear:both;'></div>";
						echo "<div id='user'>".$s->users."</div>";
					echo "</div></a>";
				}
			?>
		</div>

	 
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>