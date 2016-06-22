<?php
	class Database{
		static function exec($req){
			require $_SERVER['DOCUMENT_ROOT']."/Modules/Connect2PDO.php";
			$sql = $pdo->prepare($req);
			$sql->execute();
		}
		static function query($req){
			require $_SERVER['DOCUMENT_ROOT']."/Modules/Connect2PDO.php";
			$sql = $pdo->prepare($req);
			$sql->execute();
			$f = $sql->fetch();
			return $f;
		}

		static function queryAll($req){
			require $_SERVER['DOCUMENT_ROOT']."/Modules/Connect2PDO.php";
			$sql = $pdo->prepare($req);
			$sql->execute();
			$f = $sql->fetchAll();
			return $f;
		}

		static function rowCount($req){
			require $_SERVER['DOCUMENT_ROOT']."/Modules/Connect2PDO.php";
			$sql = $pdo->prepare($req);
			$sql->execute();
			$c = $sql->rowCount();
			return $c;
		}
	}
?>