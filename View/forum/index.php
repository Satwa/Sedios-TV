	<title>Forum | Sedios</title>

	<link rel="stylesheet" href="/assets/css/forum.css">
</head>
<body>
	<?php include "Elements/header.php"; ?>
	<div class="container"><?php Controller::inMaintenance(); ?>
		<div id="title"><i class="fa fa-tag"></i> Forum</div>
		<?php Controller::getAlert('forum'); ?>

		<div id="cats">
			<?php 
				foreach($r as $cat){
					echo "<a href='/forum/cat/".$cat->id."-".$cat->slug."'>";
					echo "<div id='cat'>";
					echo "<div id='title'>".$cat->title."</div>";
					echo "<div id='desc'>".$cat->description."</div>";
					echo "</div>";
					echo "</a>";
				}
			?>
		</div>
		<div style="clear:both;"></div>
	 
