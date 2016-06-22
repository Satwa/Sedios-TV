<?php
	$host = "";
	$bdd  = "";
	$user = "";
	$pass = "";

	try{
		$pdo = new PDO('mysql:host='.$host.';dbname='.$bdd, $user, $pass); 
		$pdo->query("SET NAMES 'utf8'");
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo "<div id='title'>Impossible de se connecter.</div><br/>";
		die();
	}

	//ban
	$time = time();
	$sql = $pdo->prepare("SELECT name FROM sedios_users WHERE banned = 1 and expire > $time");
	$sql->execute();
	$f = $sql->fetchAll();

	foreach($f as $u){
		$n = $u->name;
		$sql = $pdo->prepare("UPDATE sedios_users SET banned = 0, expire = 0 WHERE name = '$u'");
		$sql->execute();
	}

	//live
	$sql = $pdo->prepare("UPDATE sedios_live_show SET passed = 1 WHERE banned = 1 and hour < $time");
	$sql->execute();

	$sql = $pdo->prepare("UPDATE sedios_live_channel SET onair = 0,progid = 0");
	$sql->execute();

?>