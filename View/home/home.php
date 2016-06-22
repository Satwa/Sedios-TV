	<title>Accueil | Sedios</title>

	<link href='/assets/css/home.css' rel='stylesheet'>
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-home"></i> Bienvenue chez Sedios !</div>

		<div id="describe">
			<div id="title"><i class="fa fa-info"></i> Sedios ? Késako ?</div>
			<?php
				$parse = new Parsedown();
				echo $parse->text(file_get_contents("./Markdown/what-is.md"));
			?>	
		</div>
		<?php #TODO: List all channel on air w/ a red dot ?>
		<div id="home">
			<div id="head"><i class="fa fa-pencil"></i> Nos 3 derniers articles</div>
			<?php
				$q = Database::queryAll("SELECT id,title,category,author,slug,content,time FROM sedios_blog WHERE visibility = 1 ORDER BY time DESC LIMIT 0,3");
				$parsedown = new Parsedown();
				foreach($q as $p){
					echo "<div id='post'>";
					 echo "<div id='name'>{$p->title}</div>";
					 echo "<hr>";
					 echo "<div id='info'><i class='fa fa-user'></i> <a href='/profile/".$p->author."'>".$p->author."</a> | <i class='fa fa-slack'></i> ".BlogController::getCategory($p->category)." | <i class='fa fa-floppy-o'></i> ".date('d/m H:i', $p->time)."</div>";
					 echo "<hr>";
					 echo "<div id='desc'>".$parsedown->text(BlogController::short($p->content, 64))."</div>";
					 echo "<a href='/blog/".$p->id."-".$p->slug."'><button>Voir l'article <i class='fa fa-arrow-right'></i> </button></a>";
					echo "</div>";
					 
				}
			?>
			<div style='clear:both;'></div>
		</div>
		<br>

		<div id="home">
			<div id="head"><i class="fa fa-video-camera"></i> Nos 3 prochains live</div>

			<?php
				$p = LiveController::nextPrograms();
				if(!$p){
					echo "Il n'y a aucun programme de live pour le moment :(";
				}
				foreach($p as $s){
					if($s->channel == "main"){
						$s->channel = "";
					}else{$s->channel = "/".$s->channel;}
					echo "<a href='/live".$s->channel."'><div id='program'>";
						echo "<div id='title'>".$s->title."</div><br>";
						echo "<div id='user'>".$s->users."</div>";
						echo "<div id='time'>".date('d/m à H:i', $s->hour)."</div>";
					echo "</div></a>";
				}
			?>
		</div>
		<br>

		<div id="home">
			<div id="head"><i class="fa fa-spinner"></i>Nos 3 dernières rediffusions</div>
			<?php
				$r = HomeController::getVOD();

				$i = 0;
				while($i != $r['total'] && $i != 3){
					echo "<div id='post'>";
						echo "<div id='name'>{$r['list'][$i]['title']}</div>";
						echo "<hr>";
						echo "<center><img src='http://www.dailymotion.com/thumbnail/video/{$r['list'][$i]['id']}' style='width:200px;'></center>";
						echo "<hr>";
						echo "<a href='/vod/{$r['list'][$i]['id']}'><button>Visionner le replay<i class='fa fa-arrow-right'></i> </button></a>";
					echo "</div>";

					$i++;
				}
			?>
			<div style="clear:both;"></div>
		</div>
		<br>

		<div id="home">
			<div id="head"><i class="fa fa-tag"></i> Les 3 derniers sujets du forum</div>

			<?php
				$q = Database::queryAll("SELECT * FROM sedios_forum_thread ORDER BY id DESC LIMIT 0,3");

				foreach($q as $p){
					echo "<div class='forum post'>";
						echo "<div id='name'>{$p->title}</div>";
						echo "<hr>";
						echo "<div id='info'><i class='fa fa-user'></i> <a href='/profile/".$p->author."'>".$p->author."</a> | <i class='fa fa-floppy'></i> ".date('d/m H:i', $p->posted)."</div>";
						echo "<hr>";
						echo "<div id='desc'>".$parsedown->text(BlogController::short($p->content, 64))."</div>";
						echo "<a href='/forum/thread/{$p->id}'><button>Voir le sujet <i class='fa fa-arrow-right'></i> </button></a>";
					echo "</div>";
				}
			?>
			<div style='clear:both;'></div>
		</div>
	
	