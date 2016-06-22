<?php
	class BlogController extends Controller{
		//View function
		static function index($page = 1){
			include "./View/blog/blog.php";
		}

		static function view($id){
			$i = explode("-", $id);
			$req = Database::query("SELECT * FROM sedios_blog WHERE id = $i[0] AND visibility = 1");
			Controller::resetSession('comment');
			include "./View/blog/view.php";
		}

		static function editcom_view($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$q = Database::query("SELECT * FROM sedios_blog_com WHERE id = $id");
				$id = $id;

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					include "View/blog/editcom.php";
				}else{Controller::redirect('/blog');}
			}else{Controller::redirect('/login');}
		}

		/* Required code */

		static function short($text, $length){
			$short = mb_substr($text, 0, $length);

			if($short != $text) {
				$lastspace = strrpos($short, ' ');
				$short = substr($short , 0, $lastspace);
			}

			$short = str_replace("’","'", $short);
			$short = stripslashes($short);
			$short = nl2br($short);

			echo $short."...";
		}

		static function getCategory($id){
			$req = Database::query("SELECT name FROM sedios_blog_cat WHERE id = $id");
			return $req->name;
		}

		//Gestion des entry
		static function listEntriesByPage($page){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_blog WHERE visibility = 1");
			
			$nbEntry = $r;
			$MaxEntry = 6;
			$nbPage = ceil($nbEntry/$MaxEntry);

			$page -= 1;
			if($page > $nbPage){
				$page = 0;
			}

			$f = Database::queryAll("SELECT * FROM sedios_blog WHERE visibility = 1 ORDER BY time DESC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPages($page){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_blog WHERE visibility = 1");
			
			$nbEntry = $r;
			$MaxEntry = 6;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}

		//Gestion du cube
		static function getCube($face = 0){
			$f = Database::queryAll("SELECT * FROM sedios_blog ORDER BY time DESC LIMIT 0,5");
			if($face == 1){
				$s = Database::queryAll("SELECT * FROM sedios_live_channel WHERE onair = 1");
				echo "<span id='small'>";
					echo "<span id='t'><i class='fa fa-signal'></i> Live en cours...</span><hr><br>";
					foreach($s as $c){
						echo $c->title;
						echo "<hr>";
					}
				echo "</span>";
				
				return;
			}elseif($face == 2){
				echo "<a href='/blog/".$f[0]->id."-".$f[0]->slug."'>";
					echo "<span id='small'>";
						echo "<span id='t'>".$f[0]->title."</span><hr><br>";
						echo "<span id='desc'>".BlogController::short($f[0]->content, 64)."</span>";
					echo "</span>";
				echo "</a>";
								return;
			}elseif($face == 3){
				echo "<a href='/blog/".$f[1]->id."-".$f[1]->slug."'>";
					echo "<span id='small'>";
						echo "<span id='t'>".$f[1]->title."</span><hr><br>";
						echo "<span id='desc'>".BlogController::short($f[1]->content, 64)."</span>";
					echo "</span>";
				echo "</a>";
				
				return;
			}elseif($face == 4){
				echo "<a href='/blog/".$f[2]->id."-".$f[2]->slug."'>";
					echo "<span id='small'>";
						echo "<span id='t'>".$f[2]->title."</span><hr><br>";
						echo "<span id='desc'>".BlogController::short($f[2]->content, 64)."</span>";
					echo "</span>";
				echo "</a>";
				
				return;
			}elseif($face == 5){
				echo "<a href='/blog/".$f[3]->id."-".$f[3]->slug."'>";
					echo "<span id='small'>";
						echo "<span id='t'>".$f[3]->title."</span><hr><br>";
						echo "<span id='desc'>".BlogController::short($f[3]->content, 64)."</span>";
					echo "</span>";
				echo "</a>";
				
				return;
			}elseif($face == 6){
				echo "<a href='/blog/".$f[4]->id."-".$f[4]->slug."'>";
					echo "<span id='small'>";
						echo "<span id='t'>".$f[4]->title."</span><hr><br>";
						echo "<span id='desc'>".BlogController::short($f[4]->content, 64)."</span>";
					echo "</span>";
				echo "</a>";
				
				return;
			}
		}

		static function delete_com($id){
			if(Controller::isAuth()){
				$t = Database::query("SELECT author,pid FROM sedios_blog_com WHERE id = $id");
				if($t->author == $_SESSION['Auth']['user']['name'] || UserController::getLevelOf($_SESSION['Auth']['user']['name']) >= 3){
					$p = Database::query("SELECT id,slug FROM sedios_blog WHERE id = ".$t->pid);
					Database::exec("DELETE FROM sedios_blog_com WHERE id = $id");
					Controller::redirect("/blog/".$p->id."-".$p->slug);
				}else{
					Controller::redirect("/blog");
				}
			}else{
				Controller::redirect("/blog");
			}
		}

		static function delete_post($id){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0){
					Database::exec("DELETE FROM sedios_blog WHERE id = $id");
					Controller::redirect("/admin/listpost");
				}else{
					Controller::redirect("/404");
				}
			}else{
				Controller::redirect("/404");
			}
		}

		//Gestion des com'
		static function sendComment($pid = null){
			$_SESSION['comment'] = null;

			if(!empty($_POST["content"])){
				extract($_POST);
				$pseudo = $_SESSION['Auth']['user']['name'];
				$content = addslashes($content);
				Database::exec("INSERT INTO sedios_blog_com (pid, author, content) VALUES ($pid, '$pseudo', '$content')");
				
				$_SESSION['comment'] = [
					"start"  => time(),
					"content"=> null,
					"type"	 => "success",
					"error"	 => "Votre commentaire vient d'être envoyé !"
				];

				Controller::redirect("/blog/$pid");
			}else{
				$_SESSION['comment'] = [
					"start"  => time(),
					"content"=> $_POST["content"],
					"type"	 => "error",
					"error"	 => "Vous n'avez pas rempli tous les champs !"
				];
				Controller::redirect("/blog/$pid");
			}
		}

		static function editComment($id){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				extract($_POST);

				$q = Database::query("SELECT author,pid FROM sedios_blog_com WHERE id = $id");

				if($q->author == $pseudo || UserController::getLevelOf($pseudo) >= 3){
					$content = strip_tags(addslashes($content));

					Database::exec("UPDATE sedios_blog_com SET content = '$content' WHERE id = $id");

					$a = Database::query("SELECT slug FROM sedios_blog WHERE id = {$q->pid}")->slug;

					$_SESSION['comment'] = [
						"start" => time(),
						"type"  => "success",
						"error" => "Votre message vient d'être modifié !",
						"content"=>null
					];

					Controller::redirect("/blog/{$q->pid}-$a");
				}else{Controller::redirect('/blog');}
			}else{Controller::redirect('/login');}
		}

		static function getComments($pid){
			return Database::queryAll("SELECT * FROM sedios_blog_com WHERE pid = $pid ORDER BY id ASC");
		}

		static function authorizeComments($pid = null){
			return Database::query("SELECT comments FROM sedios_blog WHERE id = $pid");
		}

		static function countComments($id){
			return Database::rowCount("SELECT id FROM sedios_blog_com WHERE pid = $id");
		}

	}
?>