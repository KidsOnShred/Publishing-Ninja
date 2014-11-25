<?php
	
	  // Database credentials
	define('HOST', 'localhost');
    define('USER', 'angeltes_root');
    define('PASS', 'ingressum-n0n');
    define('NAME', 'angeltes_main');
	 
	
	class Core
	{
		public $mDb; // handle of the db connection
		private static $instance;
	
		private function __construct()
		{
			// Create a database object
			try {
				$dsn = "mysql:host=".HOST.";dbname=".NAME;
				$this->mDb = new PDO($dsn, USER, PASS);
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
				exit;
			}
			
		}
	
		public static function getInstance()
		{
			if (!isset(self::$instance))
			{
				$object = __CLASS__;
				self::$instance = new $object;
			}
			return self::$instance;
		}
	
		// others global functions
	}
?>