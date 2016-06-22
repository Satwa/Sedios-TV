<div id="comments">

	<?php 
		if(BlogController::authorizeComments($pid)->comments != 1){
	?>
		<div id="title"><i class="fa fa-users"></i>  Les commentaires sont désactivés</div><br>
	<?php
		}else{
			if(Controller::isAuth()){
	?>
		<div id="title"><i class="fa fa-users"></i>  Poster un commentaire</div><br>
		<?php Controller::getAlert('comment'); ?>
		<form method="post" action="/post/comment/<?= $pid; ?>">
			<label for="content">Votre message :</label> 	 <textarea id="content" name="content" required><?php if(!empty($_SESSION['comment'])){echo $_SESSION['comment']["content"];} ?></textarea><br>
			<br>
			<center><input type="submit" value="Envoyer mon commentaire">
		</form>
	<?php }else{
		?><div id="title"><i class="fa fa-users"></i>  Vous devez être connecté pour pouvoir poster un commentaire</div><br><?php
		  }
		} ?>
</div>