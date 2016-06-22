<?php
	class ContactController extends Controller{
		static function index(){
			Controller::resetSession('mail');
			include "./View/contact/index.php";
		}

		static function sendMail(){
			if(!empty($_POST["g-recaptcha-response"])){
				$json = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=xxxxxxxx&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

				$_SESSION["mail"] = array();

				if($json["success"]){
					if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["subject"]) && !empty($_POST["content"])){
						extract($_POST);
						$content = nl2br(htmlentities($content));
						
						$m = new Mail();
						$m->setFrom($email);
						$m->setTo("contact@sedios.fr");
						$m->setSubject("[Sedios] $subject");
						$m->setContent("Mail de : $name<br>Sujet : $subject<br> Mail : $email<br><br><b>Contenu :</b><br><br><center>".$content."</center>");
						$m->send();
						
						//Send mail
						$_SESSION['mail'] = array(
							"start"  => time(),
							"name" => null,
							"subject" => null,
							"email"	 => null,
							"content"=> null,
							"type"	 => "success",
							"error"	 => "Votre mail a correctement été envoyé, nous vous répondrons dans les plus brefs délais !"
						);

						Controller::redirect("/contact");
					}else{
						Controller::redirect("/contact");
					}
				}else{
					$_SESSION['mail'] = array(
						"start"  => time(),
						"name" => $_POST['name'],
						"subject" => $_POST['subject'],
						"email"	 => $_POST['email'],
						"content"=> $_POST['content'],
						"type"	 => "error",
						"error"	 => "Vous n'avez pas été détecté(e) comme humain :o"
					);
					Controller::redirect("/contact");
					//not a success
				}
			}else{
				$_SESSION['mail'] = array(
					"start"  => time(),
					"name" => $_POST['name'],
					"subject" => $_POST['subject'],
					"email"	 => $_POST['email'],
					"content"=> $_POST['content'],
					"type"	 => "warning",
					"error"	 => "Vous n'avez pas validé le captcha !"
				);
				Controller::redirect("/contact");
				//captcha not validated
			}

		}
	}
?>