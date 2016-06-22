	<title>Gérer la chaine: <?= $name; ?> | Administration | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/admin.css">
	<link rel="stylesheet" href="/assets/css/member.css">
	<link rel="stylesheet" href="/assets/css/jquery.datetimepicker.css">
	<script src="/assets/js/a_countup.js"></script>
</head>
<body>
	<?php include "Elements/header.php";
		  $c = Database::query("SELECT id,name,progid FROM sedios_live_channel WHERE name = '$name' AND onair = 1"); ?>
	<div class="container">
		<?php Controller::inMaintenance(); ?>
		<?php Controller::getAlert('live'); ?>
		<?php $q = Database::queryAll("SELECT * FROM sedios_live_show WHERE channel = '$name' AND passed = 0 ORDER BY hour ASC"); $pseudo = "%".$_SESSION['Auth']['user']['name']."%"; ?>
	<?php if(!$c){ ?>
		<fieldset>
				<legend>Lancer un live</legend>
				<?php
					if($q){
						foreach($q as $t){
							$live_min_5 = $t->hour - 300;
							$live_plu_5 = $t->hour + 300;
							$r = range($live_min_5, $live_plu_5, 1);
							$live_hour = $t->hour;
							if(date('d/m',$live_hour) == date('d/m', time())){
								if(in_array($live_hour, $r)){
									if(in_array(time(), $r)){
										$p = Database::query("SELECT id FROM sedios_live_show WHERE users LIKE '$pseudo'");
										if($p){
											echo '<a href="/admin/live/start/'.$t->id.'"><div id="channel" class="chan" style="color:lightgray;">'.$t->title.'<br><i>'.$t->users.'</i> <br>'.date('H:i', $live_hour).'</div></a>';
										}
									}
								}
							}
						}
						echo "<div style='clear:both;'></div>";
					}
				?>
			</fieldset>
			<br>
		?>
		<?php
			if($c){
				$p = Database::query("SELECT * FROM sedios_live_show WHERE passed = 0 AND id = {$c->progid}");
		?>
		<div id="form">
			<h2>Gérer le live</h2>
				<?php
					include "Elements/admin/dailypanel.php";
				?>
				<br><br>
				<hr><br>
				<div id="counter">{{countup:live}}</div><div style='clear:both;'></div><br><br>
				<center><a href="/admin/live/end/<?= $name ?>"><div id="button_menu">J'ai fini mon live !</div></a></center>
				<div style='clear:both;'></div><br>

				<?= "<script>new CountUp('".date('F',$p->hour)." ".date('d',$p->hour).", ".date('Y',$p->hour)." ".date('H',$p->hour).":".date('i',$p->hour).":00', 'counter');</script>"; ?>
		</div><br>
		<?php
			}
		?>
			<div id="form">
				<h2>Ajout/Suppression de programme</h2>
				
				<form method="post" action="/admin/addstream">
					<input type="text" name="channel" value="<?= $channel->name; ?>" hidden="hidden">
					<label for="titre">Nom du stream :</label><input type="text" id="titre" name="title"><br>
					<label for="hour">Date du stream :</label><input type="text" id="hour" name="hour"><br>
					<label for="users">Utilisateurs :</label><input type="text" id="users" name="users" value="<?= $channel->users; ?>"><br>
					<input type="submit" value="Créer le programme">
				</form>
			<table>
				<thead>
					<td id="head">Titre</td> <td id="head">Utilisateurs</td> <td id="head">Date</td> <td id="head">Supprimer</td>
				</thead>
				<?php
					foreach($q as $t){
						echo "<tr>";
							echo "<td>";
								echo $t->title;
							echo "</td>";
							echo "<td>";
								echo $t->users;
							echo "</td>";
							echo "<td>";
								echo date("\\l\\e d/m à H:i", $t->hour);
							echo "</td>";
							echo "<td>";
								$p = Database::query("SELECT id FROM sedios_live_show WHERE users LIKE '$pseudo'");
								if($p){
									echo "<center><a href='/admin/deletestream/".$t->id."'><i class='fa fa-trash'></i> </a></center>";//////////////////////////////
								}
							echo "</td>";
						echo "</tr>";
					}
				?>	
			</table>
			<hr>

			<h2>Clé de stream</h2>

			Serveur de stream : <a href="#">rtmp://publish.dailymotion.com/publish-dm</a><br><br>
			<a href="" id="key">Afficher la clé de stream</a><br><br>
			<div id="display" style="color:lightgray;"></div><br>
		</div>

		<?php
			if(UserController::getLevelOf($_SESSION['Auth']['user']['name'])){
				echo "<br><br>";
		?>
		<div id="form">
				<h2>Editer la chaine</h2>
				<form method="post" action="/admin/editchannel">
					<input type="text" name="channel" value="<?= $channel->name; ?>" hidden="hidden">
					<label for="users">Utilisateurs :</label><input type="text" id="users" name="users" value="<?= $channel->users; ?>"><br>
					<label for="streamkey">Clé de stream :</label><input type="text" id="streamkey" name="streamkey" value="<?= $channel->streamkey; ?>"><br>
					<input type="submit" value="Editer la chaine">
				</form>
		</div>
	<?php
		}
	?>
	<?= "<script>var streamkey = '".$channel->streamkey."';</script>" ?>
	<script src="/assets/js/a_channel.js"></script>
	<script src="/assets/js/jquery.datetimepicker.js"></script>
<?php
	echo "<script>";
		echo "$('#hour').datetimepicker({datepicker:true, startDate: '".date("Y",time())."/".date("m",time())."/".date("d",time())."',format:'d/m/Y H:i',minDate:0});";
	echo "</script>";
?>