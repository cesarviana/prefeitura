<?php 
/**
* Fábrica de daos
*/
require_once 'UsuarioDAO.php';
require_once 'TipoUsuarioDAO.php';
require_once 'BairroDAO.php';
require_once 'CategoriaDAO.php';
require_once 'ProblemaDAO.php';

class DAOFactory
{
	public static function getUsuarioDAO( $connFactory ){
		return UsuarioDAO::getInstance( $connFactory );
	}
	public static function getTipoUsuarioDAO( $connFactory ){
		return TipoUsuarioDAO::getInstance( $connFactory );
	}
	public static function getBairroDAO( $connFactory ){
		return BairroDAO::getInstance( $connFactory );
	}
	public static function getCategoriaDAO( $connFactory ){
		return CategoriaDAO::getInstance( $connFactory );
	}
	public static function getProblemaDAO( $connFactory ){
		return ProblemaDAO::getInstance( $connFactory );
	}
}
 ?>