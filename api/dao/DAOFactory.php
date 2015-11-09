<?php 
/**
* Fábrica de daos
*/
require 'UserDAO.php';

class DAOFactory
{
	public static function getUserDAO( $connFactory ){
		return UserDAO::getInstance( $connFactory );
	}
}
 ?>