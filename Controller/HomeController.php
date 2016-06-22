<?php
	class HomeController extends Controller{
		static function index(){
			include "./View/home/home.php";
		}

		static function getVOD(){
			require_once $_SERVER['DOCUMENT_ROOT']."/Modules/Dailymotion.php";
			$file = $_SERVER['DOCUMENT_ROOT']."/CoreFiles/vod.sedios";

			$api = new Dailymotion();

			if(time() - filemtime($file) > 3000){
				$result = $api->get('/user/xxxx/videos', array("limit"=>4));
				file_put_contents($file, serialize($result));
			}else{
				$result = unserialize(file_get_contents($file));
			}
			return $result;

		}
	}
?>