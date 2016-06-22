<div id="replyform">
	<?php
		if($a == 1){
			if(Controller::isAuth()){
	?>
		<?php Controller::getAlert('reply'); ?>
		<form method="post" action="/post/reply">
			<label for="content">Votre message</label><br>
			<textarea id="content" name="content" required><?php if(!empty($_SESSION['reply'])){echo $_SESSION['reply']["content"];} ?></textarea>
			<br>
			<input type="hidden" value="<?= $id ?>" name="t">	
			<br>
			<center><input type="submit" value="Poster !"></center>
		</form>
	<?php }else{ ?>
		<div id="title"><i class="fa fa-users"></i> Vous devez être connecté pour pouvoir répondre</div><br>
	<?php
	  }
	}else{echo '<div id="title"><i class="fa fa-users"></i> Le sujet est fermé</div><br>';}
	?>
</div>