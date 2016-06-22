<?php
	class Controller{
		static function redirect($url){
	    	echo('<script>
			  window.location="'.$url.'";
			  </script>'
			);
		}

		static function resetSession($sessionName){
			if(isset($_SESSION[$sessionName])){
				if(isset($_SESSION[$sessionName]['start'])){
					$time = (2 - (time() - $_SESSION[$sessionName]['start']));
					if($time <= 0){$_SESSION[$sessionName] = null;}	
				}
			}
			return;
		}

		static function defineSession($session,$array){
			$_SESSION[$session] = $array;
			return;
		}

		static function getAlert($alertName){
			if(!empty($_SESSION[$alertName])){
		    	echo "<div id='".$_SESSION[$alertName]['type']."'>".$_SESSION[$alertName]['error']."</div>";
		  	}
		  	return;
		}

		static function getDecoByLevel($level){
			if(UserController::getLevelOf($level) == 1){
				$icon = "pencil";
				$title = "Rédacteur";
			}elseif(UserController::getLevelOf($level) == 2){
				$icon = "videocam";
				$title = "Streamer";
			}elseif(UserController::getLevelOf($level) == 3){
				$icon = "beer";
				$title = "Modérateur";
			}elseif(UserController::getLevelOf($level) == 4){
				$icon = "star";
				$title = "Administrateur";
			}elseif(UserController::getLevelOf($level) == 5){
				$icon = "heart";
				$title = "Membre d'honneur";
			}else{
				$icon = "user";
				$title = "Utilisateur";
			}
			return ["icon"=>$icon,"title"=>$title];
		}

		/*
		* $controller = array()
		* Usage: loadController(['HomeController','LiveController']);
		*/
		static function loadController($controller){
			if(is_array($controller)){
				foreach($controller as $c){
					require_once $_SERVER['DOCUMENT_ROOT']."/Controller/".$c.".php";
				}
			}else{
				require_once $_SERVER['DOCUMENT_ROOT']."/Controller/".$controller.".php";
			}
		}
		
		static function isAuth(){
			if(isset($_SESSION['Auth']['user'])){
				$pseudo = $_SESSION['Auth']['user']['name'];
				$pass = $_SESSION['Auth']['user']['pass'];
				$req = Database::query("SELECT * FROM sedios_users WHERE name = '$pseudo' AND password = '$pass' AND banned = 0");

				if($req){
					if($req->password == $pass && $req->name == $pseudo){
						return true;
					}else{return false;}
				}else{return false;}
			}else{return false;}
		}

		static function search(){
			include "./View/search/search.php";
		}

		static function inMaintenance(){
			$a = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/CoreFiles/maintenance.json"));
			if($a->maintenance == 1){
				echo "<div id='warning'>Le site est actuellement en maintenance mais reste disponible. Il se peut donc que quelques problèmes surviennent.</div>";
			}elseif($a->maintenance == 2){
				if(Controller::isAuth() && UserController::getLevelOf($_SESSION['Auth']['user']['name']) == 4){
					return;
				}
				Controller::redirect("/maintenance");
			}else{
				return;
			}
			return $a->maintenance;
		}

		static function maintenance_view(){
			$a = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/CoreFiles/maintenance.json"));
			if($a->maintenance == 2){
				include "View/about/maintenance.php";
			}else{Controller::redirect("/");}
		}
	}
?>