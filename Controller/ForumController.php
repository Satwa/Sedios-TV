<?php
	class ForumController extends Controller{

		static function index(){
			Controller::resetSession("forum");
			$r = Database::queryAll("SELECT * FROM sedios_forum_cat");
			include "./View/forum/index.php";
		}

		static function viewcat($id = null, $p = 1){
			$page = $p;
			$o = $id;
			$id = explode("-", $id);

			include "./View/forum/cats.php";
		}

		static function showthread($id = null, $p = 1){
			Controller::resetSession("reply");
			$page = $p;
			$id = $id;
			$title = stripslashes(ForumController::getThreadNameById($id));

			$t = Database::query("SELECT * FROM sedios_forum_thread WHERE id = $id");
			if($t){
				include "./View/forum/thread.php";
			}else{Controller::redirect("/forum");}
		}

		static function newThread($sid){
			$sid = $sid;
			if(Controller::isAuth()){
				include "./View/forum/newthread.php";
			}else{
				Controller::redirect("/forum");
			}
		}	

		static function closeThread($id){
			if(Controller::isAuth() && UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 2){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$time   = time();
				Database::exec("UPDATE sedios_forum_thread SET authorize = 0 WHERE id = $id");
				Database::exec("INSERT INTO sedios_forum_reply (pid, author, content, posted) VALUES ($id, '$pseudo', 'Ce sujet a été fermé soit pour inactivité, soit à la demande de l‘auteur. Vous pouvez nous contacter par mail afin de le réouvrir (ou de le changer de section).', $time)");
				Controller::redirect("/forum/thread/".$id);
			}else{
				Controller::redirect("/forum");
			}
		}

		static function editreply_view($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$q = Database::query("SELECT * FROM sedios_forum_reply WHERE id = $id");
				$id = $id;

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					include "View/forum/editreply.php";
				}else{Controller::redirect('/forum');}
			}else{Controller::redirect('/login');}
		}
		static function editReply($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				extract($_POST);

				$q = Database::query("SELECT author,pid FROM sedios_forum_reply WHERE id = $id");

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					$content = strip_tags(addslashes($content));

					Database::exec("UPDATE sedios_forum_reply SET content = '$content' WHERE id = $id");

					Controller::redirect("/forum/thread/{$q->pid}");
				}else{Controller::redirect('/forum');}
			}else{Controller::redirect('/login');}
		}

		static function editthread_view($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$q = Database::query("SELECT * FROM sedios_forum_thread WHERE id = $id");
				$id = $id;

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					include "View/forum/editthread.php";
				}else{Controller::redirect('/forum');}
			}else{Controller::redirect('/login');}
		}
		static function editThread($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				extract($_POST);

				$q = Database::query("SELECT author,id FROM sedios_forum_thread WHERE id = $id");

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					$title = strip_tags(addslashes($title));
					$content = strip_tags(addslashes($content));

					Database::exec("UPDATE sedios_forum_thread SET content = '$content', title = '$title' WHERE id = $id");

					Controller::redirect("/forum/thread/{$q->id}");
				}else{Controller::redirect('/forum');}
			}else{Controller::redirect('/login');}
		}

		/* DELETE thread */

		static function deleteThread($id){
			if(Controller::isAuth()){
				$t = Database::query("SELECT author FROM sedios_forum_thread WHERE id = $id");
				if($t->author == $_SESSION['Auth']['user']['name'] || UserController::getLevelOf($t->author) >= 3){
					Database::exec("DELETE FROM sedios_forum_thread WHERE id = $id");
					Database::exec("DELETE FROM sedios_forum_reply WHERE pid = $id");
					$_SESSION["forum"] = [
						"start" => time(),
						"type"  => "success",
						"error" => "Votre sujet a correctement été supprimé !"
					];
					Controller::redirect("/forum");
				}else{
					$_SESSION["forum"] = [
						"start" => time(),
						"type"  => "error",
						"error" => "Ce sujet ne vous appartient pas !"
					];
					Controller::redirect("/forum");
				}
			}else{
				$_SESSION["forum"] = [
					"start" => time(),
					"type"  => "error",
					"error" => "Vous n'êtes pas connecté !"
				];
				Controller::redirect("/forum");
			}
		}

		static function delete_com($id){
			if(Controller::isAuth()){
				$i = Database::query("SELECT * FROM sedios_forum_reply WHERE id = $id");
				if($i->author == $_SESSION['Auth']['user']['name'] || UserController::getLevelOf($_SESSION['Auth']['user']['name']) >= 3){
					Database::exec("DELETE FROM sedios_forum_reply WHERE id = $id");

					Controller::redirect("/forum/thread/$id");
				}else{Controller::redirect("/forum/thread/$id");}
			}else{Controller::redirect("/login");}
		}

		/* POST message */

		static function sendReply(){
			if(Controller::isAuth()){
				if(isset($_POST)){
					extract($_POST);

					$content = addslashes($content);
					$author = $_SESSION['Auth']['user']['name'];
					$n = time();

					Database::exec("INSERT INTO sedios_forum_reply (pid, author, content, posted) VALUES ($t, '$author', '$content', $n)");

					$_SESSION["reply"] = [
						"start" => time(),
						"type"  => "success",
						"content"=> null,
						"error" => "Votre message vient d'être posté !"
					];

					Controller::redirect("/forum/thread/".$t);
				}else{Controller::redirect('/');}
			}else{Controller::redirect('/');}
		}

		static function sendThread(){
			if(Controller::isAuth()){
				if(!empty($_POST['title']) && !empty($_POST['content'])){
					extract($_POST);

					$author = $_SESSION['Auth']['user']['name'];
					$content = addslashes($content);
					$title = htmlentities(addslashes($title));
					$time = time();

					Database::exec("INSERT INTO sedios_forum_thread (author, cat, title, content, posted) VALUES ('$author', $c, '$title', '$content', $time)");

					Controller::redirect("/forum/cat/".$c."-".ForumController::getSlugOfCat($c));
				}else{
					//Emptyness
					Controller::redirect("/forum/cat/".$c."-".ForumController::getSlugOfCat($c));
				}
			}else{
				//Isn't auth
				Controller::redirect("/");
			}
		}


		//Threads page
		static function listThreadsByPage($page, $cat){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_forum_thread WHERE cat = $cat");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			$page -= 1;
			if($page > $nbPage){
				$page = 0;
			}

			$f = Database::queryAll("SELECT * FROM sedios_forum_thread WHERE cat = $cat ORDER BY posted DESC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPagesForThreads($page, $cat){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_forum_thread WHERE cat = $cat");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}

		//Thread page
		static function listCommentsByPage($page, $pid){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_forum_reply WHERE pid = $pid");
			
			$nbEntry = $r;
			$MaxEntry = 18;
			$nbPage = ceil($nbEntry/$MaxEntry);

			$page -= 1;
			if($page > $nbPage){
				$page = 0;
			}

			$f = Database::queryAll("SELECT * FROM sedios_forum_reply WHERE pid = $pid ORDER BY posted ASC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPagesForThread($page, $pid){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_forum_reply WHERE pid = $pid");
			
			$nbEntry = $r;
			$MaxEntry = 18;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}

		/* Others functions */

		static function getNameOfCat($id){
			return Database::query("SELECT title FROM sedios_forum_cat WHERE id = $id")->title;
		}
		static function getSlugOfCat($id){
			return Database::query("SELECT slug FROM sedios_forum_cat WHERE id = $id")->slug;
		}

		static function getThreadNameById($id){
			return Database::query("SELECT title FROM sedios_forum_thread WHERE id = $id")->title;
		}
	}
?>