<?php
	require $_SERVER['DOCUMENT_ROOT']."/Modules/Connect2PDO.php";
	require $_SERVER['DOCUMENT_ROOT']."/Modules/Database.php";
	require $_SERVER['DOCUMENT_ROOT']."/Modules/Parsedown.php";
	require $_SERVER['DOCUMENT_ROOT']."/Controller/Controller.php";
	require $_SERVER['DOCUMENT_ROOT']."/Controller/BlogController.php";
	require $_SERVER['DOCUMENT_ROOT']."/Controller/ForumController.php";

	if(isset($_GET["motclef"])){
		extract($_GET);
		$motclef = addslashes($motclef);
		$motclef = "%".$motclef."%";

		$parsedown = new Parsedown();

		$r0 = Database::queryAll("SELECT * FROM sedios_blog WHERE title LIKE '$motclef'");
		$r1 = Database::queryAll("SELECT * FROM sedios_forum_thread WHERE title LIKE '$motclef'");
		$r2 = Database::queryAll("SELECT * FROM sedios_live_channel WHERE name LIKE '$motclef' AND onair = 1 OR users LIKE '$motclef'");
		$r3 = Database::queryAll("SELECT * FROM sedios_users WHERE name LIKE '$motclef'");

		if(count($r0) == 0 && count($r1) == 0 && count($r2) == 0 && count($r3) == 0){echo "<h1>Aucun résultat</h1>";return;}
		
		//Live
		foreach($r2 as $f){
			if($f->name == "Main"){$url = "/live";}else{$url = "/live/".$f->name;}

			echo "<article id='main'>";
			echo "<div id='name'>".$f->name."</div>";
			echo "<a href='".$url."'><button>Accéder à la chaine <i class='fa fa-arrow-right'></i> </button></a>";
			echo "</article>";
		}

		//Blog
		foreach($r0 as $f){
			echo "<article id='main'>";
			echo "<div id='name'>".$f->title."</div>";
			echo "<hr>";
			echo "<div id='info'><i class='fa fa-user'></i> <a href='/profile/".$f->author."'>".$f->author."</a> | <i class='fa fa-slack'></i> ".BlogController::getCategory($f->category)." | <i class='fa fa-floppy-o'></i> ".date('d/m/y H:i', $f->time)." | <i class='fa fa-envelope-o'></i> ".BlogController::countComments($f->id)."</div>";
			echo "<hr>";
			echo "<div id='desc'>".$parsedown->text(BlogController::short($f->content, 32))."</div>";
			echo "<a href='/blog/".$f->id."-".$f->slug."'><button>Voir l'article <i class='fa fa-arrow-right'></i> </button></a>";
			echo "</article>";
		}

		//Forum
		foreach($r1 as $f){
			echo "<article id='main'>";
			echo "<div id='name'>".$f->title."</div>";
			echo "<hr>";
			echo "<div id='info'><i class='fa fa-user'></i> <a href='/profile/".$f->author."'>".$f->author."</a> | <i class='fa fa-slack'></i> ". ForumController::getNameOfCat($f->cat)." | <i class='fa fa-floppy-o'></i> ".date('d/m/y H:i', $f->posted)."</div>";
			echo "<hr>";
			echo "<div id='desc'> </div>";
			echo "<a href='/forum/thread/".$f->id."'><button>Voir le sujet <i class='fa fa-arrow-right'></i> </button></a>";
			echo "</article>";
		}

		//Profile
		foreach($r3 as $f){
			echo "<article id='main'>";
			echo "<div id='name'>".$f->name."</div>";
			echo "<hr>";
			echo "<div id='desc'>".nl2br($f->description)."<br><br></div>";
			echo "</article>";
		}
	}else{
		echo "<h1>Aucun résultat</h1>";
	}
?>