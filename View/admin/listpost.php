	<title>Editer des articles | Sedios</title>
	
	<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?><br>
	<div id="form">
		<table>
			<thead>
				<td id="head">Titre</td> <td id="head">Auteur</td> <td id="head">Publication</td> <td id="head">Publié</td> <td id="head">Supprimer</td>
			</thead>
			<?php
				foreach($f as $t){
					echo "<tr>";
						echo "<td>";
							echo "<a href='/admin/edit/".$t->id."'>".$t->title."</a>";
						echo "</td>";
						echo "<td>";
							echo $t->author;
						echo "</td>";
						echo "<td>";
							echo date("d/m/Y H:i", $t->time);
						echo "</td>";
						echo "<td>";
							if($t->visibility == 1){echo "<center>Oui</center>";}else{echo "<center>Non</center>";}
						echo "</td>";
						echo "<td>";
							echo "<center><a href='/admin/deletepost/".$t->id."'><i class='fa fa-trash'></i> </a></center>";
						echo "</td>";
					echo "</tr>";
				}
			?>	
		</table>
	</div>
	<br><br>
	<div id='pagination'>
		<a href='' class='button open'><i class='fa fa-arrow-right'></i> </a>
			<?php
				$f = AdminController::listPagesForPosts($page);

				for($i=1;$i<=$f;$i++){
					if($page == $i){
						echo "<a href='#' class='button current'>$i</a>";
					}else{
						echo "<a href='/admin/listpost/$i' class='button'>$i</a>";
					}
				}
			?>
		<a href='' class='button open'><i class='fa fa-arrow-left'></i> </a>
	</div>