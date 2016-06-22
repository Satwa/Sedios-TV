 <?php
	class LiveController extends Controller{
		
		static function index(){
			include "./View/live/index.php";
		}

		static function channel($user = null){
			$user = $user;
			$u = Database::query("SELECT * FROM sedios_live_channel WHERE name = '$user'");
			if($u->fb != ''){$hasFb = true;}else{$hasFb = false;}
			if($u->yt != ''){$hasYt = true;}else{$hasYt = false;}
			if($u->twitter != ''){$hasTw = true;}else{$hasTw = false;}

			include "./View/live/channel.php";
		}

		static function dev(){
			$u = Database::query("SELECT * FROM sedios_live_channel WHERE name = 'Dev'");

			include "./View/live/dev.php";
		}

		static function games(){
			$u = Database::query("SELECT * FROM sedios_live_channel WHERE name = 'Jeux'");

			include "./View/live/games.php";
		}

		static function loadVOD($vod){
			require_once $_SERVER['DOCUMENT_ROOT']."/Modules/Dailymotion.php";

			$api = new Dailymotion();
			try{
				$v = $api->get("/video/{$vod}");
			}catch(DailymotionApiException $e){
				$v["owner"] = null;
			}
			if($v["owner"] == " "){
				$p = ["type"=>"success", "vod"=>$vod];

				$like    = Database::rowCount("SELECT * FROM sedios_vod WHERE vod = '{$vod}' AND type = 'like'");
				$dislike = Database::rowCount("SELECT * FROM sedios_vod WHERE vod = '{$vod}' AND type = 'dislike'");

			}else{
				$p = ["type"=>"error"];
			}

			include "./View/live/vod.php";
		}

		static function hasVoted($vod, $user){
			$r = Database::rowCount("SELECT * FROM sedios_vod WHERE vod = '{$vod}' AND user = '{$user}'");
			if($r){return true;}else{return false;}
		}

		static function exist($user){
			$r = Database::rowCount("SELECT id FROM sedios_live_channel WHERE name = '$user'");
			if($r >= 1){return true;}else{return false;}
		}

		static function isOnAir($channel = "main"){
			$s = Database::query("SELECT onair FROM sedios_live_channel WHERE name = '$channel'")->onair;
			if($s == 1){
				return true;
			}else{
				return false;
			}
		}

		static function getChannelOf($user){
			return Database::query("SELECT chatid FROM sedios_live_channel WHERE name = '$user'")->chatid;
		}

		static function getOnAirChan(){
			return Database::queryAll("SELECT * FROM sedios_live_channel WHERE onair = 1");
		}

		static function nextPrograms($channel = "*"){
			$time = time();
			if($channel == "*"){
				$s = Database::queryAll("SELECT * FROM sedios_live_show WHERE hour > $time AND hour > $time AND passed = 0 ORDER BY hour DESC LIMIT 0,5");
			}else{
				$s = Database::queryAll("SELECT * FROM sedios_live_show WHERE channel = '$channel' AND hour > $time AND passed = 0 ORDER BY hour DESC LIMIT 0,5");
			}
			return $s;
		}
	}
?>