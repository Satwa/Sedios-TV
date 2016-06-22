<?php
	require $_SERVER['DOCUMENT_ROOT']."/Controller/Controller.php";
	require $_SERVER['DOCUMENT_ROOT']."/Controller/AdminController.php";

	if(!empty($_GET['msg']) && !empty($_GET['pseudo'])){AdminController::sendTweet($_GET['msg'], $_GET['pseudo']);}
?>