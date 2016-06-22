<?php 
	$f = ForumController::listCommentsByPage($page, $id);

	foreach($f as $f){
		$v = Controller::getDecoByLevel($f->author);
?>
	<div class="reply">
		<?php
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) >= 3 || $f->author == $pseudo){
					echo "<a href='/forum/delete/".$f->id."' id='delete'>Supprimer</a>";
				}
			}
		?>
		<span><i class='fa fa-<?= $v['icon'] ?>' title="<?= $v['title']  ?>"></i> <a href='/profile/<?= $f->author ?>' style="color:lightgray;"><?= $f->author ?></a></span>
		<hr>
		<div id="content">
			<?= nl2br(stripslashes(htmlentities($f->content))); ?>
		</div>
		<hr>
	
		<?php 
			if(Controller::isAuth()){
					$pseudo = $_SESSION['Auth']['user']['name'];
					if(UserController::getLevelOf($pseudo) >= 3 || $f->author == $pseudo){
						echo "<a href='/reply/edit/".$f->id."' id='edit'>Editer</a>";
					}
			} 
		?>
		<span id="right">
			<i class='fa fa-floppy-o'></i> <?=  date('d/m/y à H:i', $f->posted); ?>
		</span>
		<div style="clear:both;"></div>
	</div><br>
<?php
	} 
?>