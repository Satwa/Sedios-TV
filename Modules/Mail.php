<?php
	class Mail{
		private $from = null;
		private $to = null;

		private $subject = null;
		private $content = null;

		private $headers;

		public function __construct(){

		}

		/*
		 *	With this function we will convert array into \r\n for all entries
		*/
		function convertHeaders(){
			$this->headers .= "MIME-Version: 1.0" . PHP_EOL;
			$this->headers .= "To: " . $this->to . PHP_EOL;
			$this->headers .= "From: <Sedios>" . $this->from . PHP_EOL;
			$this->headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		}

		function setSubject($sub){
			$this->subject = $sub;
		}

		function setFrom($from){
			$this->from = $from;
		}

		function setTo($to){
			$this->to = $to;
		}

		function setContent($content = null){
			$this->content = "<!DOCTYPE html><html><head><style>body{background:gray;margin:0}#content{width:256px;padding:20px;margin:auto;background:lightgray;}</style></head><body><div id='content'>$content</div></body></html>";
			
		}

		function send(){
			if(!empty($this->from) && !empty($this->subject) && !empty($this->content)){
				self::convertHeaders();
				$s = mail($this->to, $this->subject, $this->content, $this->headers);
				if(!$s){
					echo "Error while sending mail!";
					return false;
				}else{return true;}
			}else{
				echo "You need to set all vars different to empty!";
				return false;
			}
		}
	}
?>