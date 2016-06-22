<?php
	class Security{
		static function hash($pass = null){
			return hash('SHA256', "54908a168a06b".$pass);
		}
	}
?>