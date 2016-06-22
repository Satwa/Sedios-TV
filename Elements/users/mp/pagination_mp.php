<div id='pagination'>
	<a href='' class='button open'><i class='fa fa-arrow-right'></i> </a>
		<?php
			$f = UserController::listPagesForMsg($page);

			for($i=1;$i<=$f;$i++){
				if($page == $i){
					echo "<a href='' class='button current'>$i</a>";
				}else{
					echo "<a href='/mp/p/$i' class='button'>$i</a>";
				}
			}
		?>
	<a href='' class='button open'><i class='fa fa-arrow-left'></i> </a>
</div>