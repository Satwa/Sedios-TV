<?php
	class Router {
		//if {slug}||{user}||{token} [a-zA-Z0-9\-]+
		//if {id}||{pid}||{page}||{p}||dafault [0-9]+
		protected $routes = [];
		protected $path = '';

		public function __construct($path = ''){
			$this->path = $path;
		}


		/**
		* Usage :
		* @param Method => GET or POST
		* @param Route  => New url (ex. /blog)
		* @param Call   => function(){require Controller(s), ViewFunction}
		**/

		public function addRoute($method, $route, $call){
			$this->routes[$route] = ["method"=>$method, "url"=>$route, "call"=>$call];
		}


		/**
		* Know if parsed route is actual
		* @return true if it's actual route
		**/

		public function isActualRoute($rewrite){//TODO: Add var possibility (preg_match?)
			$route = $this->routes[$rewrite];
			if($_SERVER['REQUEST_METHOD'] == $route["method"]){
				if(strcasecmp($_SERVER['REQUEST_URI'], $route["url"]) == 0 || strcasecmp($_SERVER['REQUEST_URI'], $route["url"]."/") == 0){
					return true;
				}elseif($this->compare($_SERVER['REQUEST_URI'], $rewrite, $this->parse($_SERVER['REQUEST_URI'], $rewrite))){
					return true;
				}
			}
			return false;
		}


		/**
		* Parse rewrite url
		* @param $actualURL   : SERVER REQUEST URI
		* @param $route 	  : name of rewrite
		* @return array of vars
		**/

		public function parse($actualURL, $route){
			$v = [];
			$route = $this->routes[$route];

			$url_slash = explode("/", $actualURL);
			$route_slash = explode("/", $route['url']);

			if(in_array("{slug}", $route_slash)){

				$k = array_keys($route_slash, "{slug}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash))
					$v["slug"] = $url_slash[$k[0]]; //Add to array

			}
			if(in_array("{user}", $route_slash)){

				$k = array_keys($route_slash, "{user}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash))
					$v["user"] = $url_slash[$k[0]]; //Add to array

			}
			if(in_array("{token}", $route_slash)){

				$k = array_keys($route_slash, "{token}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash))
					$v["token"] = $url_slash[$k[0]]; //Add to array

			}
			if(in_array("{id}", $route_slash)){

				$k = array_keys($route_slash, "{id}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					if(is_int($k[0])){
						$v["id"] = $url_slash[$k[0]]; //Add to array
					}
				}

			}
			if(in_array("{pid}", $route_slash)){

				$k = array_keys($route_slash, "{pid}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					if(is_int($k[0])){
						$v["pid"] = $url_slash[$k[0]]; //Add to array
					}
				}

			}
			if(in_array("{page}", $route_slash)){
				$k = array_keys($route_slash, "{page}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					if(is_int($k[0])){
						$v["page"] = $url_slash[$k[0]]; //Add to array
					}
				}

			}

			return $v;
		}


		/**
		* Compare current url with normal route by replacing args
		* @param $currentURL  : SERVER REQUEST URI
		* @param $route 	  : name of rewrite
		* @param $args   	  : array of args from parse()
		* @return true/false
		**/

		public function compare($currentURL, $route, $args){
			$route = $this->routes[$route];

			$url_slash = explode("/", $currentURL);
			$route_slash = explode("/", $route['url']);

			$basicURL = $route['url'];

			$finalURL = "";

			if(strpos($basicURL, "{slug}") !== false){

				$k = array_keys($route_slash, "{slug}"); //Search key in explode_array
				
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{slug}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{slug}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			if(strpos($basicURL, "{user}") !== false){

				$k = array_keys($route_slash, "{user}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{user}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{user}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			if(strpos($basicURL, "{token}") !== false){

				$k = array_keys($route_slash, "{token}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{token}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{token}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			if(strpos($basicURL, "{id}") !== false){

				$k = array_keys($route_slash, "{id}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{id}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{id}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			if(strpos($basicURL, "{pid}") !== false){

				$k = array_keys($route_slash, "{pid}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{pid}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{pid}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			if(strpos($basicURL, "{page}") !== false){

				$k = array_keys($route_slash, "{page}"); //Search key in explode_array
				if(array_key_exists($k[0], $url_slash)){
					$basicURL = str_replace("{page}", $url_slash[$k[0]], $basicURL);
					$finalURL = str_replace("{page}", $url_slash[$k[0]], $basicURL); //Add to URL
				}

			}
			
			if(strcasecmp($currentURL, $finalURL) == 0 || strcasecmp($currentURL, $finalURL."/") == 0){
				return true;
			}
			return false;
		}


		/**
		* Launch the router
		**/

		public function listen(){
			foreach($this->routes as $route => $key){
				if(self::isActualRoute($route)){
					$v = $this->parse($_SERVER['REQUEST_URI'],$route);
					//var_dump($v);
					call_user_func_array($key['call'], $v);

					return true;
				}else{
					continue;
				}
			}
		}
	}

	/*$app->setName("titre");
	$app->addStyle("home.css");*/
?>