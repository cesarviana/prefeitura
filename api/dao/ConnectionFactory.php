<?php 
/**
* Fábrica de conexões
*/
class ConnectionFactory
{
	private static $con;
	private static $instance;
	private function __construct(){}

	public function getConnection(){
		if(!isset(self::$con)){
			$host = 'localhost';
			$user = 'us_prefeitura';
			$pass = '123456';
			$db   = 'db_prefeitura';
			$port = 5432;
			$con  = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$db.';user='.$user.';password='.$pass.'');
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $con;
	}

	public static function getInstance(){
		if( !self::$instance )
			self::$instance = new ConnectionFactory();
		return self::$instance;
	}

}
 ?>