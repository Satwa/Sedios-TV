<?php
	session_start();
	require "Modules/autoloader.php";
	require "Modules/Dailymotion.php";

	$SECURE_KEY = '54908a168a06b';

	switch($_GET["action"]){
	    case "chat_login":
	    	$array_send = array();
	    	if(!empty($_POST['pseudo']) && !empty($_POST['password']) && $_POST['key'] == $SECURE_KEY){
	    		$pseudo = $_POST['pseudo'];
				$pass = $_POST['password'];
    			if(UserController::getLevelOf($_POST['pseudo']) > 0){
    				$data = Database::query("SELECT name,password,email,level FROM sedios_users WHERE name = '$pseudo' AND password = '$pass'");
    			}else{
    				$data = Database::query("SELECT name,password,email,level FROM sedios_users WHERE name = '$pseudo' AND password = '$pass' AND chatban = 0");
    			}

    			if($data){
    				$array_send["data"]["pseudo"]	= $data->name;
					$array_send["data"]["password"]	= $data->password;
					$array_send["data"]["mail"]		= $data->email;
					$array_send["data"]["rang"]		= $data->level;
    			}else{
	    			$array_send['error'] = "Impossible de vous connecter !";
	    		}
	    	}else{
	    		$array_send['error'] = "Vous n'avez pas rempli tous les champs !";
	    	}
			echo json_encode($array_send);
	        break;
	    case "chat_ban":
	    	if(!empty($_POST['pseudo']) && $_POST['key'] == $SECURE_KEY){	
    			Database::exec("UPDATE sedios_users SET chatban = 1 WHERE name = '{$_POST['pseudo']}'");
    		}
	        break;
	    case "chat_unban":
	    	if(!empty($_POST['pseudo']) && $_POST['key'] == $SECURE_KEY){	
    			Database::exec("UPDATE sedios_users SET chatban = 0 WHERE name = '{$_POST['pseudo']}'");
    		}
	        break;

	    case "live-get":
            if(isset($_GET["plateforme"]) && isset($_GET["status"])){
                $api = new Dailymotion();
                $api->setGrantType(
                    Dailymotion::GRANT_TYPE_PASSWORD, 
                    "xxxxxx",
                    "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
                    array('write', 'delete', 'manage_videos', 'manage_records'),
                    array(
                        'username' => "SediosTV",
                        'password' => "BlueLife42",
                    )
                );
                
                $p = $_GET["plateforme"];
                $data = Database::query("SELECT * FROM sedios_live_channel WHERE id = $p");
                if(!$data){
                    echo("Erreur");    
                }else{
                    if($_GET["status"] <> "get" && ($_GET["status"] == "started" || $_GET["status"] == "stopped")){
                     	if(isset($_GET["key"]) && $_GET["key"] == $SECURE_KEY){
                            $result = $api->post(
                                '/video/'.$data->playerid,
                                array('record_status' => $_GET["status"])
                            );
                     	}else{
                        	$result = array("error" => "Invalid secure key");
                     	}
                    }elseif($_GET["status"] == "live_launch_ad_break" && isset($_GET["data_ad"])){
                    	if(isset($_GET["key"]) && $_GET["key"] == $SECURE_KEY){
                        	$result = $api->post(
	                                '/video/'.$data->playerid,
	                                array('live_launch_ad_break' => $_GET["data_ad"])
	                            );
                    	}else{
                        	$result = array("error" => "Invalid secure key");
                     	}
                    }elseif($_GET["status"] == "get"){
                        $result = $api->get(
                            '/video/'.$data->playerid,
                            array('fields' => array('onair','audience','record_status','views_total','live_ad_break_end_time'))
                        );
                    }else{
                        $result = array("error" => "Invalid function");
                    }
                }
                echo(json_encode($result));
            }
            break;
        case "voteVOD":
            Controller::loadController("LiveController");
            if(!LiveController::hasVoted($_POST["vod"], $_POST["pseudo"])){
                $vod    = $_POST["vod"];
                $pseudo = $_POST["pseudo"];
                $type   = $_POST["type"];
                Database::exec("INSERT INTO sedios_vod (vod, type, user) VALUES ('$vod', '$type', '$pseudo')");
            }
            break;
	}
?>