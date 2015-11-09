<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require 'DAO.php';
class UserDAO extends DAO
{
	// private $_select  = ' SELECT * FROM usuario '; Sempre igual
	private $_insert  = ' INSERT INTO usuario ( nome, login, senha, id_tipo_usuario) VALUES (:nome, :login, :senha, :id_tipo_usuario) '; 
	private $_update  = ' UPDATE usuario SET nome = :nome, login = :login, senha = :senha, id_tipo_usuario = :id_tipo_usuario';
	private $_seqName = 'usuario_id_seq';
	private $_order   = ' nome ';
	private $_table   = 'usuario';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new UserDAO( $connFactory );
		}
		return self::$instance;
	}

	public function getTable(){
		return $this->_table;
	}

	public function getSequenceName(){
		return $this->_seqName;
	}

	public function getInsert(){
		return $this->_insert;
	}

	public function getUpdate(){
		return $this->_update;
	}

	// public function getSelect(){
	// 	return $this->_select;
	// }

	public function getOrder(){
		return $this->_order;
	}

}
?>