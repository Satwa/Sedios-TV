<?php
	$user = $_SESSION['Auth']['user']['name'];
	if(UserController::getLevelOf($user) > 0 && UserController::getLevelOf($user) < 5){
?>
<hr><br>
	<div id="form">
		<h2>Publier un tweet</h2>
		<form id="tweet" method="get">
			<input type="text" id="pseudo" value="<?= $user; ?>" disabled="disabled" hidden="hidden">
			<input type="text" id="msg" placeholder="Votre tweet"><br>
			<input type="submit" id="button" value="Publier">
		</form>
	</div>

	<script src="/assets/js/a_tweet.js"></script>
	<link rel="stylesheet" href="/assets/css/admin.css">
	<br>
	<div id="form">
		<?php 
			Controller::resetSession('ban');
			Controller::getAlert('ban');
		?>
		<h2>Bannir un membre</h2>
		<form method="post" action="/admin/ban">
			<label for="pseudo">Pseudo :</label><input type="text" id="pseudo" name="pseudo"><br>
			<label for="datepicker">Date de fin :</label><input type="text" id="datepicker" name="date"><br><br>
			<input type="submit" value="Bannir">
		</form>
		<br><hr><br>
		<h2>Pardonner un membre</h2>
		<form method="post" action="/admin/unban">
			<label for="pseudo">Pseudo :</label>
			<select name="pseudo" id="pseudo">
				<?php
					$q = Database::queryAll("SELECT name FROM sedios_users WHERE banned = 1");
					foreach($q as $f){
						echo "<option value='{$f->name}'>{$f->name}</option>";
					}
				?>
				
			</select><br>
			<input type="submit" value="Pardonner">
		</form>
	</div>


	<script src="/assets/js/jquery.datetimepicker.js"></script>
	<link rel="stylesheet" href="/assets/css/jquery.datetimepicker.css">
	
	<?php
		echo "<script>";
			echo "$('#datepicker').datetimepicker({timepicker:false, startDate: '".date("Y",time())."/".date("m",time())."/".date("d",time())."',format:'d-m-Y',minDate:0});";
		echo "</script>";
	?>

<?php
	}
	if(UserController::getLevelOf($user) > 0){
?>
	<br>
	<fieldset>	
		<legend>Vos chaines</legend>
		<?php
			$pseudo = "%".$_SESSION['Auth']['user']['name']."%";
			$chan = Database::queryAll("SELECT id,name,users FROM sedios_live_channel WHERE users LIKE '$pseudo'");
			foreach($chan as $c){
				echo "<a href='/admin/channel/{$c->name}'><div id='channel'>";
					echo $c->name;
					echo "<div id='go'><i class='fa fa-arrow-right'></i> </div>";
				echo "</div></a>";
			}
		?>
	</fieldset>
	<br><br>
<?php } ?>
