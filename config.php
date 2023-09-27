<?php
	class prenotazione_campioni_myvariable{
		public $hostnames = "";
		public $passwords = "";
		public $username  = "";
		public $database  = "prenotazione_campioni";
		public $epcode		= "";
		public function __construct(){
			$this->hostnames 	= getenv("MYSQL_HOST");
			$this->passwords 	= getenv("MYSQL_PASSWORD");
			$this->username  	= getenv("MYSQL_USER");
			$this->database  	= getenv("MYSQL_DATABASE");
		}
	} 
?>
