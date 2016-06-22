<?php
	class UserController extends Controller{

		/* View function */
		
		static function register(){
			Controller::resetSession('user');
			if(!Controller::isAuth()){
				include "./View/user/register.php";
			}else{Controller::redirect("/member");}
		}


		static function panel(){
			if(Controller::isAuth()){
				include "./View/user/member.php";
			}else{
				Controller::redirect("/login");
			}
		}

		static function lostpass(){
			if(!Controller::isAuth()){
				include "./View/user/lostpass.php";
			}else{Controller::redirect("/member");}
		}
			
		static function profile($name){
			$user = $name;
			include "./View/user/profile.php";
		}

		static function login(){
			Controller::resetSession('user');
			if(!Controller::isAuth()){
				include "./View/user/login.php";
			}else{Controller::redirect("/member");}
		}

		static function logout(){
			session_destroy();
			Controller::redirect("/");
		}


		static function getDesc($user){
			return htmlentities(Database::query("SELECT description FROM sedios_users WHERE name = '$user'")->description);
		}

		/* Post function */

		static function validaccount($token){
			$r = Database::query("SELECT id FROM sedios_users WHERE token = '$token'");
			if($r){
				$u = Database::query("SELECT id FROM sedios_users WHERE token = '$token' AND valid = 0");
				if(!$u){
					$_SESSION['user'] = array(
						"start"  => time(),
						"type"	 => "warning",
						"error"	 => "Vous aviez déjà validé votre compte !"
					);

					Controller::redirect('/login');
				}else{
					$u = Database::query("SELECT * FROM sedios_users WHERE token = '$token' AND valid = 0");
					Database::exec("UPDATE sedios_users SET valid = 1, token = null WHERE id = ".$u->id);


					//Send the MP
					$time   = time();
					$pseudo = $u->name;
					$c = addslashes('Bonjour !<br>Merci à toi de t\'être inscrit sur notre site !<br>Viens toi aussi partager ta passion avec nous.<br><br>Nous souhaitons te voir bientôt sur le site :)');

					Database::exec("INSERT INTO sedios_users_mp (sender, receiver, subject, message, posted) VALUES ('Sedios', '$pseudo', 'Bienvenue sur notre site !', '$c', $time)");


					$_SESSION['user'] = array(
						"start"  => time(),
						"pseudo"   => null,
						"type"	 => "success",
						"error"	 => "Vous venez de valider votre compte !"
					);

					Controller::redirect('/login');
				}
			}else{
				$_SESSION['user'] = array(
					"start"  => time(),
					"pseudo" => null,
					"mail"   => null,
					"type"	 => "error",
					"error"	 => "Je ne reconnais pas votre compte :("
				);

				Controller::redirect('/register');
			}
		}

		static function sendLogin(){
			if(!empty($_POST['pseudo']) && !empty($_POST['password'])){
				$pseudo = addslashes(strip_tags($_POST['pseudo']));
				$password = Security::hash($_POST['password']);
				
				$u = Database::query("SELECT * FROM sedios_users WHERE name = '$pseudo' AND password = '$password'");
				if($u){
					if($u->valid == 1){
						if($u->banned != 1){
							Database::exec("UPDATE sedios_users SET token = '', lastlogin = ".time()." WHERE name = '$pseudo' AND password = '$password'");

							$_SESSION['Auth']['user'] = [
								"name" => $u->name,
								"pass" => $u->password
							];

							$_SESSION['user'] = null;

							Controller::redirect('/member');
						}else{
							$_SESSION['user'] = array(
								"start"  => time(),
								"type"	 => "error",
								"error"	 => "Vous avez été banni ! Celui-ci expirera le ". date('d/m/y H:i', $u->expire)
							);

							Controller::redirect('/login');
						}
						
					}else{
						$_SESSION['user'] = array(
							"start"  => time(),	
							"type"	 => "warning",
							"error"	 => "Vous n'avez pas validé votre compte !"
						);

						Controller::redirect('/login');
					}
				}else{
					//no u
					$_SESSION['user'] = array(
						"start"  => time(),	
						"type"	 => "warning",
						"error"	 => "Vous n'êtes pas inscrit ou le mot de passe n'est pas valide !"
					);

					Controller::redirect('/login');
				}
			}else{
				$_SESSION['user'] = array(
					"start"  => time(),
					"type"	 => "error",
					"error"	 => "Vous devez remplir tous les champs"
				);

				Controller::redirect('/login');
			}
		}

		static function sendRegister(){
			if(!empty($_POST["g-recaptcha-response"])){
				$json = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=xxxxxxxxx&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);
				$_SESSION['user'] = null;

				if($json["success"]){
					if(!empty($_POST["pseudo"]) && !empty($_POST["mail"]) && !empty($_POST["pass1"]) && !empty($_POST["pass2"])){
						if(($_POST["pass1"] == $_POST["pass2"]) && isset($_POST["cguaccept"])){
							extract($_POST);

							$search = Database::query("SELECT * FROM sedios_users WHERE email = '$mail'");
							$exist  = Database::query("SELECT * FROM sedios_users WHERE name = '$pseudo'");

							if($search){
								$_SESSION['user'] = [
									"start"  => time(),
									"pseudo" => $_POST['pseudo'],
									"mail"	 => null,
									"type"	 => "warning",
									"error"	 => "Cette adresse mail est déjà utilisée !"
								];

								Controller::redirect("/register");
								return;
							}

							if($exist){
								$_SESSION['user'] = [
									"start"  => time(),
									"pseudo" => null,
									"mail"	 => $_POST['mail'],
									"type"	 => "warning",
									"error"	 => "Ce pseudo est déjà utilisé !"
								];

								Controller::redirect("/register");
								return;
							}

							$password = Security::hash($pass1);
							$token = uniqid()."-".time();
							$now = time();
							Database::exec("INSERT INTO sedios_users (name, email, password, created, token) VALUES ('$pseudo', '$mail', '$password', $now, '$token')");
								
							#Sending the Mail
							$m = new Mail();
							$m->setFrom("noreply@sedios.fr");
							$m->setTo($mail);
							$m->setSubject("[Sedios] Votre inscription");
							$m->setContent("Cher $pseudo, <br><br>Vous venez de vous inscrire sur le site de Sedios et nous vous en remercions !<br>Pour valider votre inscription, merci de cliquez sur <a href='http://www.sedios.fr/valid/$token'>ce lien</a> !<br>Merci encore de votre inscription et à bientôt !<br><br>Cordialement,<br>L'équipe de Sedios.");
							$m->send();

							$_SESSION['user'] = [
								"start"  => time(),
								"pseudo" => null,
								"mail"	 => null,
								"type"	 => "success",
								"error"	 => "Vous voilà inscrit(e) ! Vous venez de recevoir un mail de validation de compte"
							];

							Controller::redirect("/login");
						}else{
							$_SESSION['user'] = [
								"start"  => time(),
								"pseudo" => $_POST['pseudo'],
								"mail"	 => $_POST['mail'],
								"type"	 => "error",
								"error"	 => "Vous n'avez pas entré deux fois le même mot de passe ou n'avez pas accepté les CGU"
							];

							Controller::redirect("/register");
						}
					}else{
						$_SESSION['user'] = [
							"start"  => time(),
							"pseudo" => $_POST['pseudo'],
							"mail"	 => $_POST['mail'],
							"type"	 => "error",
							"error"	 => "Vous n'avez pas rempli tous les champs !"
						];
						Controller::redirect("/register");
					}
				}else{
					$_SESSION['user'] = [
						"start"  => time(),
						"pseudo" => $_POST['pseudo'],
						"mail"	 => $_POST['mail'],
						"type"	 => "error",
						"error"	 => "Vous n'avez pas été détecté(e) comme humain :o"
					];
					Controller::redirect("/register");
				}
			}else{
				$_SESSION['user'] = [
					"start"  => time(),
					"pseudo" => $_POST['pseudo'],
					"mail"	 => $_POST['mail'],
					"type"	 => "warning",
					"error"	 => "Vous n'avez pas validé le captcha !"
				];
				Controller::redirect("/register");
			}
		}

		static function sendNewPass(){
			extract($_POST);
			$pseudo = addslashes($pseudo);
			$email  = addslashes($email);
			$u = Database::rowCount("SELECT email FROM sedios_users WHERE name = '$pseudo' AND email = '$email' AND valid = 1");
			if($u >= 1){
				$u = Database::query("SELECT email FROM sedios_users WHERE name = '$pseudo' AND email = '$email' AND valid = 1");
				$newpass = uniqid();
				$pass = Security::hash($newpass);

				Database::exec("UPDATE sedios_users SET password = '$pass' WHERE name = '$pseudo' AND email = '$email'");
								
				$m = new Mail();
				$m->setSubject("Mot de passe oublié");
				$m->setFrom("no-reply@sedios.fr");
				$m->setTo($u->email);
				$m->setContent("Cher $pseudo, <br><br>Suite à votre demande de changement de mot de passe, nous vous avons généré un nouveau mot de passe que voici :<br><b>$newpass</b><br>Modifiez-le le plus rapidement possible !<br><br>Cordialement,<br>Sedios.");


				$m->send();

				$_SESSION['user'] = [
					"start"  => time(),
					"pseudo" => null,
					"mail"	 => null,
					"type"	 => "success",
					"error"	 => "Vous venez de recevoir un mail avec votre nouveau mot de passe !"
				];

				Controller::redirect("/login");

			}else{
				$_SESSION['user'] = [
					"start"  => time(),
					"pseudo" => null,
					"mail"	 => null,
					"type"	 => "warning",
					"error"	 => "Aucun utilisateur détecté"
				];

				Controller::redirect("/lostpass");
			}
		}

		static function updateInfo(){
			extract($_POST);

			$pseudo = $_SESSION['Auth']['user']['name'];
			$description = addslashes($desc);

			if(!empty($_POST['pass1']) && !empty($_POST['pass2'])){
				if($_POST['pass1'] == $_POST['pass2']){
					$pass = Security::hash($pass1);
					Database::exec("UPDATE sedios_users SET description = '$description', password = '$pass' WHERE name = '$pseudo'");
					Database::exec("UPDATE sedios_users SET description = '$description' WHERE name = '$pseudo'");
					session_destroy();
					Controller::redirect('/login');
				}else{
					Database::exec("UPDATE sedios_users SET description = '$description' WHERE name = '$pseudo'");
					$_SESSION['pass'] = [
						'type' => "warning",
						'error'=> "Les deux mots de passe ne correspondent pas !"
					];
					Controller::redirect('/member');
				}
			}else{
				Database::exec("UPDATE sedios_users SET description = '$description' WHERE name = '$pseudo'");
				Controller::redirect('/member');
			}
		}

		static function delete_validation(){
			include "./View/user/delete.php";
		}

		static function delete(){
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$password = $_SESSION['Auth']['user']['pass'];

				Database::exec("DELETE FROM sedios_users WHERE name = '$pseudo'");
				Database::exec("DELETE FROM sedios_blog_com WHERE name = '$pseudo'");
				Database::exec("DELETE FROM sedios_forum_thread WHERE author = '$pseudo'");
				Database::exec("DELETE FROM sedios_forum_reply WHERE author = '$pseudo'");
				Database::exec("DELETE FROM sedios_users_mp WHERE sender = '$pseudo'");
				Database::exec("DELETE FROM sedios_users_mp WHERE receiver = '$pseudo'");

				Controller::redirect("/");
			}else{Controller::redirect("/");}
		}

		/* MP */

		//Get received msg
		static function inbox_view($p = 1){
			if(!Controller::isAuth()){Controller::redirect("/");}
			$page = $p;
			Controller::resetSession("state");

			include "./View/user/mp.php";
		}


		static function privatemsg($page = 1){
			$page -= 1;
			$pseudo = $_SESSION['Auth']['user']['name'];
			$r = Database::rowCount("SELECT id AS entries FROM sedios_users_mp WHERE receiver = '$pseudo'");
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			$f = Database::queryAll("SELECT * FROM sedios_users_mp WHERE receiver = '$pseudo' ORDER BY posted DESC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPagesForMsg($page = 1){
			$pseudo = $_SESSION['Auth']['user']['name'];
			$r = Database::rowCount("SELECT id AS entries FROM sedios_users_mp WHERE receiver = '$pseudo'");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}

		//Get sent message
		static function sent_view($p = 1){
			if(!Controller::isAuth()){Controller::redirect("/");}
			$page = $p;
			Controller::resetSession("state");

			include "./View/user/mps.php";
		}

		static function privatesend($page = 1){
			$page -= 1;
			$pseudo = $_SESSION['Auth']['user']['name'];
			$r = Database::rowCount("SELECT id AS entries FROM sedios_users_mp WHERE sender = '$pseudo'");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			$f = Database::queryAll("SELECT * FROM sedios_users_mp WHERE sender = '$pseudo' ORDER BY posted DESC LIMIT ".$page*$MaxEntry.",$MaxEntry");

			return $f;
		}

		static function listPagesForMsgSend($page = 1){
			$pseudo = $_SESSION['Auth']['user']['name'];
			$r = Database::rowCount("SELECT id AS entries FROM sedios_users_mp WHERE sender = '$pseudo'");
			
			$nbEntry = $r;
			$MaxEntry = 12;
			$nbPage = ceil($nbEntry/$MaxEntry);

			return $nbPage;
		}

		//Post message
		static function sendMsg(){
			if(Controller::isAuth()){
				if(!empty($_POST['destinator']) && !empty($_POST['subject']) && !empty($_POST['content'])){
					extract($_POST);

					$r = Database::rowCount("SELECT id FROM sedios_users WHERE name = '$destinator'");

					if($r != 0){
						$time = time();
						$pseudo = $_SESSION['Auth']['user']['name'];
						$subject = addslashes(strip_tags($subject));
						$content = addslashes(nl2br(strip_tags($content)));

						Database::exec("INSERT INTO sedios_users_mp (sender, receiver, subject, message, posted) VALUES ('$pseudo', '$destinator', '$subject', '$content', $time)");

						$_SESSION['state'] = [
							"start" => time(),
							"subject"=>null,
							"content"=>null,
							"type"  => "success",
							"error" => "Votre message vient d'être envoyé !"
						];

						Controller::redirect("/mp");
					}else{
						$_SESSION['state'] = [
							"start" => time(),
							"subject"=>$subject,
							"content"=>$content,
							"type"  => "warning",
							"error" => "Le destinataire n'existe pas"
						];

						Controller::redirect("/mp");
					}
				}else{
					$_SESSION['state'] = [
						"start" => time(),
						"subject"=>null,
						"content"=>null,
						"type"  => "error",
						"error" => "Vous devez remplir tous les champs !"
					];

					Controller::redirect("/mp");
				}
			}else{Controller::redirect("/login");}
		}

		static function displaymp_view($id = null){
			if(Controller::isAuth()){
				$u = $_SESSION['Auth']['user']['name'];

				$s = Database::query("SELECT * FROM sedios_users_mp WHERE receiver = '$u' AND id = $id");
				$s1 = Database::query("SELECT * FROM sedios_users_mp WHERE sender = '$u' AND id = $id");
				if($s){
					$f = [
						"pos" 	 => "receive",
						"sender" => $s->sender,
						"subject"=> $s->subject,
						"message"=> $s->message,
						"posted" => $s->posted
					];
					include "./View/user/showmp.php";
				}elseif($s1){
					$f = [
						"pos" 	 => "send",
						"sender" => $s1->receiver,
						"subject"=> $s1->subject,
						"message"=> $s1->message,
						"posted" => $s1->posted
					];
					include "./View/user/showmp.php";
				}else{
					$_SESSION['state'] = [
						"start" => time(),
						"subject"=>null,
						"content"=>null,
						"type"  => "error",
						"error" => "Ce message ne vous appartient pas !"
					];

					Controller::redirect("/mp");
				}
			}else{Controller::redirect("/login");}
			//if is sender or receiver
		}

		/* function */
		static function getUserByName($name){
			$r = Database::query("SELECT * FROM sedios_users WHERE name = '$name'");
			return $r;
		}

		static function getLevelOf($name){
			$r = Database::query("SELECT level FROM sedios_users WHERE name = '$name'");
			return $r->level;
		}

		static function isAdmin(){
			if(isset($_SESSION['Auth']['user'])){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$pass = Security::hash($_SESSION['Auth']['user']['pass']);
				$req = Database::query("SELECT level FROM sedios_users WHERE name = '$pseudo' AND password = '$pass'");
				if($req->level >= 1){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
?>