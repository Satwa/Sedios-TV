<?php
	class AdminController extends Controller{

		static function accessChannel($name){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0 && self::hasAccess($name) || UserController::getLevelOf($_SESSION['Auth']['user']['name']) == 4){
					$name = $name;
					$channel = Database::query("SELECT * FROM sedios_live_channel WHERE name = '$name'");
					Controller::resetSession('live');
					include $_SERVER['DOCUMENT_ROOT']."/View/admin/channel.php";
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function displayPost($page = 1){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0){
					$page = $page;
					$f = self::listPosts($page);

					include "View/admin/listpost.php";
				}else{Controller::redirect('/404');}
			}else{Controller::redirect('/404');}
		}

		static function editChannel(){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) == 4){
					extract($_POST);
					Database::exec("UPDATE sedios_live_channel SET users = '$users', streamkey = '$streamkey' WHERE name = '$channel'");
					Controller::redirect("/admin/channel/$channel");
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function editPost($id){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0){
					$db = Database::query("SELECT * FROM sedios_blog WHERE id = $id");
					if($db){
						$id = $id;

						include "View/admin/editpost.php";
					}else{Controller::redirect('/404');}
				}else{Controller::redirect('/404');}
			}else{Controller::redirect('/404');}
		}

		static function publishEditPost(){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0){
					if(isset($_POST['title']) && isset($_POST['cat']) && isset($_POST['content'])){
						extract($_POST);

						$title = strip_tags($title);
						$slug  = self::toAscii($title);
						$title = addslashes($title);

						$pseudo = $_SESSION['Auth']['user']['name'];

						$content = addslashes($content);

						$published = Database::query("SELECT visibility FROM sedios_blog WHERE id = $id")->visibility;

						if($published == 0){
							$time = time();
						}else{
							$time = Database::query("SELECT time FROM sedios_blog WHERE id = $id")->time;
						}


						if(isset($_POST['comment'])){
							$com = 1;
						}else{
							$com = 0;
						}

						if(isset($_POST['visible'])){
							Database::exec("UPDATE sedios_blog SET category = $cat, title = '$title', slug = '$slug', content = '$content', comments = $com, visibility = 1, time = $time WHERE id = $id");
							$a = Database::query("SELECT id,slug FROM sedios_blog WHERE time = $time");
							if($published == 0){self::sendTweet("Un nouvel article vient d'être publié ! http://www.sedios.fr/blog/".$a->id."-".$a->slug, $pseudo);}
							Controller::redirect('/blog/'.$a->id.'-'.$a->slug);
						}else{
							Database::exec("UPDATE sedios_blog SET category = $cat, title = '$title', slug = '$slug', content = '$content', comments = $com, visibility = 0, time = $time WHERE id = $id");
							Controller::redirect('/member');
						}
				}else{Controller::redirect('/404');}
				}else{Controller::redirect('/404');}
			}else{Controller::redirect('/404');}
		}

		static function listPosts($page = 1){	
			$page -= 1;
			

			$nbEntry = self::listPagesForPosts($page);
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			if($page > $nbPage){
				$page = 0;
			}	

			$f = Database::queryAll("SELECT * FROM sedios_blog ORDER BY time DESC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPagesForPosts($page){
			$r = Database::rowCount("SELECT id AS entries FROM sedios_blog");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}



		static function sendTweet($msg, $pseudo){
			require $_SERVER['DOCUMENT_ROOT']."/Modules/twitteroauth.php";

			$pslen = strlen($pseudo) + 3;
			$mslen = strlen($msg);

			if($mslen > 140){
				$msg = self::truncate($msg, (140 - $pslen));
				$mslen = count($msg);
			}

			$t = new TwitterOAuth("xxxxx","XXXXX", "xxxxx", "XXXXX");
			
			if(is_array($msg)){
				$i = 0;
				foreach($msg as $m){
					$tweet = $t->post('statuses/update', array('status' => $m." - ".$pseudo));
					sleep(0.5);
				}
				echo "Publié!";
			}else{
				$tweet = $t->post('statuses/update', array('status' => $msg." - ".$pseudo));
				echo "Publié!";
			}
		}

		static function banUser(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) > 0){
					if(isset($_POST['pseudo']) && isset($_POST['date'])){
						$user = $_POST['pseudo'];
						$u = Database::query("SELECT name,level FROM sedios_users WHERE name = '$user'");
						$a = Database::query("SELECT name,level FROM sedios_users WHERE name = '$pseudo'");
						if(!$u){$_SESSION['ban'] = array("start"  => time(),"type"	 => "error","error"	 => "Utilisateur inexistant !");Controller::redirect("/member");}
						if($u->level >= $a->level || $a->name == $u->name){
							var_dump($_POST);die();
							$_SESSION['ban'] = array(
								"start"  => time(),
								"type"	 => "error",
								"error"	 => "Il vous est impossible de bannir cet utilisateur !"
							);
							Controller::redirect("/member");
						}else{
							$date = DateTime::createFromFormat('d-m-Y H:i', $_POST["date"].' 23:59');
							$time = $date->getTimestamp();
							Database::exec("UPDATE sedios_users SET banned = 1, expire = $time WHERE name = '{$u->name}'");
							$_SESSION['ban'] = array(
								"start"  => time(),
								"type"	 => "success",
								"error"	 => "L'utilisateur vient d'être banni !"
							);
							Controller::redirect("/member");
						}
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function unbanUser(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) > 0){
					if(isset($_POST['pseudo'])){
						$user = $_POST['pseudo'];
						$u = Database::query("SELECT name,level FROM sedios_users WHERE name = '$user'");
						$a = Database::query("SELECT name,level FROM sedios_users WHERE name = '$pseudo'");
						if(!$u){$_SESSION['ban'] = array("start"  => time(),"type"	 => "error","error"	 => "Utilisateur inexistant !");Controller::redirect("/member");}
						if($u->level >= $a->level){
							$_SESSION['ban'] = array(
								"start"  => time(),
								"type"	 => "error",
								"error"	 => "Il vous est impossible de débannir cet utilisateur !"
							);
							Controller::redirect("/member");
						}else{
							Database::exec("UPDATE sedios_users SET banned = 0, expire = 0 WHERE name = '{$u->name}'");
							$_SESSION['ban'] = array(
								"start"  => time(),
								"type"	 => "success",
								"error"	 => "L'utilisateur vient d'être débanni !"
							);
							Controller::redirect("/member");
						}
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function setMaintenance(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) == 4){
					if(isset($_POST['access'])){
						$a = ["maintenance"=>$_POST['access']];
						file_put_contents($_SERVER['DOCUMENT_ROOT']."/CoreFiles/maintenance.json",json_encode($a));	

						Controller::redirect("/member");
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function publishPost(){
			if(Controller::isAuth()){
				if(UserController::getLevelOf($_SESSION['Auth']['user']['name']) > 0){
					extract($_POST);

					$title = strip_tags($title);
					$slug  = self::toAscii($title);
					$title = addslashes($title);

					$pseudo = $_SESSION['Auth']['user']['name'];

					$content = addslashes($content);

					$time = time();

					if(isset($_POST['comment'])){
						$com = 1;
					}else{
						$com = 0;
					}

					if(isset($_POST['visible'])){
						Database::exec("INSERT INTO sedios_blog (category, author, title, slug, content, comments, time, visibility) VALUES ($cat, '$pseudo', '$title', '$slug', '$content', $com, $time, 1)");
						$a = Database::query("SELECT id,slug FROM sedios_blog WHERE time = $time");
						self::sendTweet("Un nouvel article vient d'être publié ! http://www.sedios.fr/blog/".$a->id."-".$a->slug, $pseudo);
						Controller::redirect('/blog/'.$a->id.'-'.$a->slug);
					}else{
						Database::exec("INSERT INTO sedios_blog (category, author, title, slug, content, comments, time, visibility) VALUES ($cat, '$pseudo', '$title', '$slug', '$content', $com, $time,0)");
						Controller::redirect('/member');
					}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function addStream(){
			if(Controller::isAuth()){
				extract($_POST);
				if(self::hasAccess($channel)){
					$date = DateTime::createFromFormat('d/m/Y H:i', $hour);
					$time = $date->getTimestamp();
					$title = addslashes($title);
					Database::exec("INSERT INTO sedios_live_show (channel,hour,title,users) VALUES ('$channel',$time,'$title','$users')");
					Controller::redirect("/admin/channel/".$channel);
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function deleteStream($id){
			if(Controller::isAuth()){
				$chan = Database::query("SELECT channel,users FROM sedios_live_show WHERE id = $id")->channel;
				if(self::hasAccess($chan)){
					$pseudo = "%".$_SESSION['Auth']['user']['name']."%";
					$q = Database::query("SELECT id FROM sedios_live_show WHERE users LIKE '$pseudo'");
					if($q){
						Database::exec("DELETE FROM sedios_live_show WHERE id = $id");
						Controller::redirect("/admin/channel/".$chan);
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function finishStream($name = null){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) > 0){
					if(self::hasAccess($name)){
						$progid = Database::query("SELECT progid FROM sedios_live_channel WHERE name = '$name'")->progid;
						Database::exec("UPDATE sedios_live_channel SET progid = 0, onair = 0 WHERE name = '$name'");
						Database::exec("UPDATE sedios_live_show SET passed = 1 WHERE channel = '$name' AND id = $progid");
						$_SESSION['live'] = [
							"start"  => time(),
							"type"	 => "success",
							"error"	 => "Votre live a bien été terminé !"
						];

						Controller::redirect("/admin/channel/".$name);
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function startStream($id = null){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) > 0){
					
					$name = Database::query("SELECT channel FROM sedios_live_show WHERE id = '$id'")->channel;
					
					if(self::hasAccess($name)){
						Database::exec("UPDATE sedios_live_channel SET progid = $id, onair = 1 WHERE name = '$name'");
						$_SESSION['live'] = [
							"start"  => time(),
							"type"	 => "success",
							"error"	 => "Vous venez de démarrer votre live !"
						];

						Controller::redirect("/admin/channel/".$name);
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		static function changeAccess(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) == 4){
					extract($_POST);

					$user = $pseudo;

					$q = Database::query("SELECT id FROM sedios_users WHERE name = '$user'");
					if($q){
						Database::exec("UPDATE sedios_users SET level = $access WHERE name = '$user'");

						Controller::redirect("/profile/".$user);
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}


		static function createChannel(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) == 4){
					if(isset($_POST["name"]) && isset($_POST["player"]) && isset($_POST["chat"]) && isset($_POST["streamkey"]) && isset($_POST["users"])){
						extract($_POST);
						
						$name = strip_tags(addslashes($name));

						$q = Database::query("SELECT id FROM sedios_live_channel WHERE name = '$name'");

						if($q){
							Controller::redirect("/admin/channel/".$name);
						}else{
							Database::exec("INSERT INTO sedios_live_channel (name, playerid, streamkey, chatid, users) VALUES ('$name', '$player', '$streamkey', '$chat', '$users')");
							Controller::redirect("/admin/channel/".$name);
						}
					}else{Controller::redirect("/404");}
				}else{Controller::redirect("/404");}
			}else{Controller::redirect("/404");}
		}

		//
		static function listCategories(){
			$c = Database::queryAll("SELECT * FROM sedios_blog_cat");
			foreach($c as $cat){
				echo "<option value=".$cat->id.">".$cat->name."</option>";
			}
		}

		static function hasAccess($chan){
			$pseudo = "%".$_SESSION['Auth']['user']['name']."%";
			$q = Database::queryAll("SELECT name FROM sedios_live_channel WHERE users LIKE '$pseudo' AND name = '$chan'");
			if($q){
				return true;
			}else{
				return false;
			}
		}

		//required function
		static function truncate($string,$length=100){
			$string = trim($string);

			if(strlen($string) > $length) {
			  $string = wordwrap($string, $length);
			  $string = explode("\n", $string);
			}

		  	return $string;
		}

		static function toAscii($str, $replace=array(), $delimiter='-') {
			if( !empty($replace) ) {
				$str = str_replace((array)$replace, ' ', $str);
			}

			$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower(trim($clean, '-'));
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

			return $clean;
		}
	}
?>	