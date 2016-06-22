<div id="header">
	 	<a href="/"><div id="logo"></div><div class="hr">&nbsp;</div></a>
		<a href="#" class="header__icon" id="header__icon"></a>
	<nav id="menu">
		<a href="/">       <i class="fa fa-home"style="font-size:26px;"></i> </a>
		<a href="/blog">   Blog	  </a>
		<a href="/forum">  Forum  </a>
		<a href="/live">   Live   </a>
		<a href="/contact">Contact</a>
		<div id="login">
			<a href="/search"><i class="fa fa-search"></i> </a>
			<a href="/member"><i class="fa fa-users"></i> </a>
			<?php if(Controller::isAuth()){ ?><a href="/logout"><i class="fa fa-sign-out"></i> </a><?php } ?>
		</div>
	</nav>
</div>