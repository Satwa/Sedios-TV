<?php
	class AboutController extends Controller{
		static function about(){
			include "./View/about/about.php";
		}

		static function tos(){
			include "./View/about/tos.php";
		}

		static function legacy(){
			include "./View/about/legacy.php";
		}

		static function noresult(){
			include "./Exception/404.html";
		}
	}
?>