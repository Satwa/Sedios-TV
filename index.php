<?php
	session_start();
	require "Modules/autoloader.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="google" value="notranslate">

	<meta http-equiv="content-language" content="fr">
	<meta name="copyright" content="http://www.sedios.fr/">
	<meta name="author" content="Sedios.fr">
	<meta name="description" content="Créée par des jeunes aux idées novatrices, Sedios est une WebTV mais aussi un blog et un forum où chacun peut partager sa passion. Rejoignez-nous :)">
	<meta name="keywords" content="sedios, tv, jeune, gaming, gameplay, jeu, amateur, jeux, video, stream, live, blog, passion, developpement, programmation, web, webtv, forum, communaute, partage">
	<meta name="language" content="fr">

	<meta property="og:title" content="Sedios">
	<meta property="og:site_name" content="Sedios.fr">
	<meta property="og:url" content="http://www.sedios.fr/">
	<meta property="og:language" content="fr">

	<meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@SediosTV">
    <meta name="twitter:description" content="Créée par des jeunes aux idées novatrices, Sedios est une WebTV mais aussi un blog et un forum où chacun peut partager sa passion. Rejoignez-nous :)">
    <meta name="twitter:image" content="/assets/img/thumb.png">
    <meta property="twitter:url" content="http://www.sedios.fr/">

	<link rel="icon" type="image/png" href="/assets/img/logo.png">
	<link rel="stylesheet" href="/assets/css/app.css">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="/assets/js/jquery.moment.js"></script>

	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

<?php
	require "Modules/Router.php";

	$router = new Router();

	/******************************************\
				   MAIN GET METHOD
	\******************************************/

	$router->addRoute("GET", "/", function(){
		Controller::loadController(["HomeController", "BlogController", "LiveController"]);
		HomeController::index();
	});

	/*
	 *
	 * Blog
	 *
	 */
	$router->addRoute("GET", "/blog", function(){
		Controller::loadController("BlogController");
		BlogController::index();
	});
	$router->addRoute('GET', '/blog/p/{page}', function($page){
		
		Controller::loadController("BlogController");
		BlogController::index($page);
	});
	$router->addRoute("GET", "/blog/{slug}", function($slug){
		Controller::loadController("BlogController");
		BlogController::view($slug);
	});

	/*
	 *
	 * Forum
	 *
	 */
	$router->addRoute("GET", "/forum", function(){
		Controller::loadController("ForumController");
		ForumController::index();
	});
	$router->addRoute("GET", "/forum/cat/{slug}", function($slug){
		Controller::loadController("ForumController");
		ForumController::viewcat($slug);
	});
	$router->addRoute("GET", "/forum/cat/{slug}/p/{page}", function($slug, $page){
		Controller::loadController("ForumController");
		ForumController::viewcat($slug, $page);
	});
	$router->addRoute("GET", "/forum/thread/{id}", function($id){
		Controller::loadController("ForumController");
		ForumController::showthread($id);
	});
	$router->addRoute("GET", "/forum/thread/{id}/p/{page}", function($id, $page){
		Controller::loadController("ForumController");
		ForumController::showthread($id, $page);
	});
	$router->addRoute("GET", "/forum/new/{id}", function($id){
		Controller::loadController("ForumController");
		ForumController::newThread($id);
	});
	$router->addRoute("GET", "/forum/close/{id}", function($id){
		Controller::loadController("ForumController");
		ForumController::closeThread($id);
	});

	/*
	 *
	 * Live
	 *
	 */
	$router->addRoute("GET", "/live", function(){
		Controller::loadController("LiveController");
		LiveController::index();
	});
	$router->addRoute("GET", "/games", function(){
		Controller::loadController("LiveController");
		LiveController::games();
	});
	$router->addRoute("GET", "/dev", function(){
		Controller::loadController("LiveController");
		LiveController::dev();
	});
	$router->addRoute("GET", "/live/{user}", function($user){
		Controller::loadController("LiveController");
		LiveController::channel($user);
	});

	$router->addRoute("GET", "/vod/{user}", function($user){
		Controller::loadController("LiveController");
		LiveController::loadVOD($user);
	});

	/*
	 *
	 * About
	 *
	 */
	$router->addRoute('GET', '/contact', function(){
		Controller::loadController("ContactController");
		ContactController::index();
	});
	$router->addRoute('GET', '/search', function(){
		Controller::search();
	});
	$router->addRoute('GET', '/about', function(){
		Controller::loadController("AboutController");
		AboutController::about();
	});
	$router->addRoute('GET', '/cgu', function(){
		Controller::loadController("AboutController");
		AboutController::tos();
	});
	$router->addRoute('GET', '/legacy', function(){
		Controller::loadController("AboutController");
		AboutController::legacy();
	});
	//-------------------------------------------------
	$router->addRoute('GET', '/404', function(){
		Controller::loadController("AboutController");
		AboutController::noresult();
	});
	$router->addRoute('GET', '/maintenance', function(){
		Controller::maintenance_view();
	});

	/*
	 *
	 * User
	 *
	 */
	$router->addRoute('GET', '/profile/{user}', function($user){
		Controller::loadController("UserController");
		UserController::profile($user);
	});
	$router->addRoute('GET', '/member', function(){
		Controller::loadController(["UserController", "AdminController"]);
		UserController::panel();
	});
	$router->addRoute('GET', '/login', function(){
		Controller::loadController("UserController");
		UserController::login();
	});
	$router->addRoute('GET', '/register', function(){
		Controller::loadController("UserController");
		UserController::register();
	});
	$router->addRoute('GET', '/logout', function(){
		Controller::loadController("UserController");
		UserController::logout();
	});
	$router->addRoute('GET', '/valid/{token}', function($token){
		Controller::loadController("UserController");
		UserController::validaccount($token);
	});
	$router->addRoute('GET', '/lostpass', function(){
		Controller::loadController("UserController");
		UserController::lostpass();
	});
	$router->addRoute('GET', '/mp', function(){
		Controller::loadController("UserController");
		UserController::inbox_view();
	});
	$router->addRoute('GET', '/mp/p/{page}', function($page){
		Controller::loadController("UserController");
		UserController::inbox_view($page);
	});
	$router->addRoute('GET', '/mps', function(){
		Controller::loadController("UserController");
		UserController::sent_view();
	});
	$router->addRoute('GET', '/mps/p/{page}', function($page){
		Controller::loadController("UserController");
		UserController::sent_view($page);
	});
	$router->addRoute('GET', '/mp/show/{id}', function($id){
		Controller::loadController("UserController");
		UserController::displaymp_view($id);
	});

	/******************************************\
				   MAIN GET METHOD
	\******************************************/

	$router->addRoute('POST', '/update', function(){
		Controller::loadController("UserController");
		UserController::updateInfo();
	});
	//-------------------------------------------------
	$router->addRoute('POST', '/post/comment/{pid}', function($pid){
		Controller::loadController("BlogController");
		BlogController::sendComment($pid);
	});
	//-------------------------------------------------
	$router->addRoute('POST', '/post/mail', function(){
		Controller::loadController("ContactController");
		ContactController::sendMail();
	});
	//-------------------------------------------------
	$router->addRoute('POST', '/post/login', function(){
		Controller::loadController("UserController");
		UserController::sendLogin();
	});
	$router->addRoute('POST', '/post/register', function(){
		Controller::loadController("UserController");
		UserController::sendRegister();
	});
	$router->addRoute('POST', '/post/lostpass', function(){
		Controller::loadController("UserController");
		UserController::sendNewPass();
	});
	$router->addRoute('POST', '/post/mp', function(){
		Controller::loadController("UserController");
		UserController::sendMsg();
	});
	//-------------------------------------------------
	$router->addRoute('POST', '/post/reply', function(){
		Controller::loadController("ForumController");
		ForumController::sendReply();
	});
	$router->addRoute('POST', '/post/thread', function(){
		Controller::loadController("ForumController");
		ForumController::sendThread();
	});

	/******************************************\
				   EDIT -- GET/POST
	\******************************************/

	$router->addRoute('GET', '/com/edit/{id}', function($id){
		Controller::loadController("BlogController");
		BlogController::editcom_view($id);
	});
	$router->addRoute('POST', '/post/editcom/{id}', function($id){
		Controller::loadController("BlogController");
		BlogController::editComment($id);
	});

	$router->addRoute('GET', '/reply/edit/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::editreply_view($id);
	});
	$router->addRoute('POST', '/post/editreply/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::editReply($id);
	});
	$router->addRoute('GET', '/thread/edit/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::editthread_view($id);
	});
	$router->addRoute('POST', '/post/editthread/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::editThread($id);
	});

	/******************************************\
				   DELETE -- GET
	\******************************************/

	$router->addRoute('GET', '/admin/deletepost/{id}', function($id){
		Controller::loadController("BlogController");
		BlogController::delete_post($id);
	});
	$router->addRoute('GET', '/com/delete/{id}', function($id){
		Controller::loadController("BlogController");
		BlogController::delete_com($id);
	});

	$router->addRoute('GET', '/forum/deletethread/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::deleteThread($id);
	});
	$router->addRoute('GET', '/forum/delete/{id}', function($id){
		Controller::loadController("ForumController");
		ForumController::delete_com($id);
	});


	$router->addRoute('GET', '/post/delete', function(){
		Controller::loadController("UserController");
		UserController::delete();
	});
	$router->addRoute('GET', '/delete', function(){
		Controller::loadController("UserController");
		UserController::delete_validation();
	});

	/******************************************\
				   ADMIN -- GET/POST
	\******************************************/


	$router->addRoute('GET', '/admin/edit/{id}', function($id){
		Controller::loadController("AdminController");
		AdminController::editPost($id);
	});
	$router->addRoute('GET', '/admin/listpost', function(){
		Controller::loadController("AdminController");
		AdminController::displayPost();
	});
	$router->addRoute('GET', '/admin/listpost/{page}', function($page){
		Controller::loadController("AdminController");
		AdminController::displayPost($page);
	});
	//-------------------------------------------------
	$router->addRoute('GET', '/admin/channel/{user}', function($user){
		Controller::loadController("AdminController");
		AdminController::accessChannel($user);
	});
	$router->addRoute('GET', '/admin/deletestream/{id}', function($id){
		Controller::loadController("AdminController");
		AdminController::deleteStream($id);
	});
	$router->addRoute('GET', '/admin/live/start/{id}', function($id){
		Controller::loadController("AdminController");
		AdminController::startStream($id);
	});
	$router->addRoute('GET', '/admin/live/end/{user}', function($user){
		Controller::loadController("AdminController");
		AdminController::finishStream($user);
	});
	//-------------------------------------------------
	//-------------------------------------------------
	//-------------------------------------------------
	$router->addRoute('POST', '/admin/newpost', function(){
		Controller::loadController("AdminController");
		AdminController::publishPost();
	});
	$router->addRoute('POST', '/admin/editpost', function(){
		Controller::loadController("AdminController");
		AdminController::publishEditPost();
	});
	$router->addRoute('POST', '/admin/access', function(){
		Controller::loadController("AdminController");
		AdminController::changeAccess();
	});
	$router->addRoute('POST', '/admin/create/channel', function(){
		Controller::loadController("AdminController");
		AdminController::createChannel();
	});
	$router->addRoute('POST', '/admin/editchannel', function(){
		Controller::loadController("AdminController");
		AdminController::editChannel();
	});
	$router->addRoute('POST', '/admin/set/maintenance', function(){
		Controller::loadController("AdminController");
		AdminController::setMaintenance();
	});
	$router->addRoute('POST', '/admin/ban', function(){
		Controller::loadController("AdminController");
		AdminController::banUser();
	});
	$router->addRoute('POST', '/admin/unban', function(){
		Controller::loadController("AdminController");
		AdminController::unbanUser();
	});
	$router->addRoute('POST', '/admin/addstream', function(){
		Controller::loadController("AdminController");
		AdminController::addStream();
	});


	$match = $router->listen();
	
	if(!$match){
		Controller::redirect("/404");
	}
?>

<div style="clear:both"></div><div id="c"></div>
<br><br>
<hr id="hrf">
</div>
	<?php include "./Elements/footer.php"; ?>

	<script src="/assets/js/app.js"></script>
	<!-- HEY TOI! MAUVAIS PADAWAN! TU ES DU COTE OBSCUR DE LA FORCE -->
	<!-- FERME CA DE SUITE ET VA VOIR MAITRE SHIFU (Oh wait...) -->
</body>
</html>