	<title>Direct | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/live.css">
</head>
<body>

	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-gamepad"></i> Quel programme pour aujourd'hui ?</div><br>
		
		<div id="display">
			<div id="games">
				<div id="chaname"><i class="fa fa-gamepad"></i>  Jeux Vidéo</div>
				<div id="list">
					<?php
						$time = time();
						
						$q = Database::queryAll("SELECT * FROM sedios_live_show WHERE channel = 'Jeux' AND passed = 0 ORDER BY hour ASC LIMIT 0,2");
						$pid = Database::query("SELECT progid FROM sedios_live_channel WHERE name = 'Jeux' AND onair = 1");
						
						foreach($q as $p){
							echo "<div id='stream'>";
								echo "<div id='leftinfo'>";
									echo "<h2>{$p->users}</h2>";
									echo "<h3>{$p->title}</h3>";
								echo "</div>";
								echo "<div id='rightinfo'>";
									if($pid && $pid->progid == $p->id){
										echo "<div id='onair' class='blink'></div>";
									}else{
										echo "<br>";
										echo "<h3>".date("d/m", $p->hour)."</h3>";
										echo "<h3>".date("H", $p->hour)."h".date("m", $p->hour)."</h3>";
									}
								echo "</div>";
								echo "<div style='clear:both;'></div>";
							echo "</div>";
						}
					?>
				</div>
				<div style="clear:both;"></div>
				<a href='/games'><div id="join">Rejoindre cette chaine <i class="fa fa-arrow-right"></i> </div></a>
			</div>

			<div id="dev">
				<div id="chaname"><i class="fa fa-code"></i>  Développement</div>
				<div id="list">
					<?php
						$q = Database::queryAll("SELECT * FROM sedios_live_show WHERE channel = 'dev' AND passed = 0 ORDER BY hour ASC LIMIT 0,2"); //AND hour > ".time()."
						$pid = Database::query("SELECT progid FROM sedios_live_channel WHERE name = 'dev' AND onair = 1"); //AND hour > ".time()."
						
						foreach($q as $p){
							echo "<div id='stream'>";
								echo "<div id='leftinfo'>";
									echo "<h2>{$p->users}</h2>";
									echo "<h3>{$p->title}</h3>";
								echo "</div>";
								echo "<div id='rightinfo'>";
									if($pid && $pid->progid == $p->id){
										echo "<div id='onair' class='blink'></div>";
									}else{
										echo "<br>";
										echo "<h3>".date("d/m", $p->hour)."</h3>";
										echo "<h3>".date("H", $p->hour)."h".date("m", $p->hour)."</h3>";
									}
								echo "</div>";
							echo "</div>";
						}
					?>
				</div>
				<a href='/dev'><div id="join">Rejoindre cette chaine <i class="fa fa-arrow-right"></i> </div></a>
			</div>
			<br>
			<div style="clear:both;"></div>
			<?php
				#WIP

				//ON AIR !
				$q = Database::queryAll("SELECT * FROM sedios_live_channel WHERE onair = 1 AND name NOT IN ('Jeux', 'Dev')");
				if($q){
					echo "<h1>Actuellement en live :</h1>";
					echo "<div id='centered'>";
				}
				foreach($q as $t){
					echo "<a href='/live/{$t->name}'><div id='tv' style='background: url(http://www.dailymotion.com/thumbnail/video/".$t->playerid.")'>";
						echo "<div id='rightinfo'>{$t->name}<br><br><h3><i class='fa fa-arrow-right'></i> </h3></div>";
					echo "</div></a>";
					
				}
				if($q){
					echo "</div>";
					echo "<div style='clear:both;'></div>";
				}
				//OFF AIR
				$q = Database::queryAll("SELECT * FROM sedios_live_channel WHERE onair = 0 AND name NOT IN ('Jeux', 'Dev')");
				if($q){
					echo "<h1>Les autres chaines :</h1>";
					echo "<div id='centered'>";
				}

				foreach($q as $t){
					echo "<a href='/live/{$t->name}'><div id='tv' style='background: url(http://www.dailymotion.com/thumbnail/video/".$t->playerid.")'>";
						echo "<div id='rightinfo'>{$t->name}<br><br><h3><i class='fa fa-arrow-right'></i> </h3></div>";
					echo "</div></a>";
				}
				if($q){
					echo "</div>";
				}
			?>
		</div>
		<div style="clear:both;"></div>