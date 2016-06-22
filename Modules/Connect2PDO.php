<?php
	$prod = false;

	if($prod){
		$host = "";
		$bdd  = "";
		$user = "";
		$pass = "";
	}else{
		$host = "localhost";
		$bdd  = "sedios";
		$user = "root";
		$pass = "";
	}
	

	try{
		$pdo = new PDO('mysql:host='.$host.';dbname='.$bdd, $user, $pass); 
		$pdo->query("SET NAMES 'utf8'");
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo "<h1>Impossible de se connecter.</h1>";
		die();
	}
?>