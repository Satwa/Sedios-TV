<?php 
	$f = BlogController::getComments($pid);
?>

<?php 
	$i = 0;
	while($i != count($f)){
		if($i % 2 == 0){$dir="left";}else{$dir="right";}
?>
	<div class="comment" id="<?= $f[$i]->id; ?>">
		<?php 
			$c = Controller::getDecoByLevel($f[$i]->author);
		?>
		<div class="<?= $dir ?>"><i class='fa fa-<?= $c['icon'] ?>' title="<?= $c['title'] ?>"></i> <a href='/profile/<?= $f[$i]->author ?>'><?= $f[$i]->author ?></a></div>
		<hr>
		<?= nl2br(htmlentities($f[$i]->content)); ?>
		<br>
		<?php
			if(Controller::isAuth()){
				$pseudo = $_SESSION['Auth']['user']['name'];
				if(UserController::getLevelOf($pseudo) >= 3 || $f[$i]->author == $pseudo){
					echo "<br><hr>";
					echo "<a href='/com/edit/".$f[$i]->id."' id='edit'>Editer</a>";
					echo "<a href='/com/delete/".$f[$i]->id."' id='delete'>Supprimer</a>";
					echo "<div style='clear:both'></div>";
				}
			}
		?>
	</div>
<?php 
		$i++;
	} 
?>