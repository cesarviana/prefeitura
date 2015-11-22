<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
class TipoUsuarioDAO extends DAO
{
	private $_select  = 'SELECT tp.id, tp.nome FROM tipo_usuario tp';

	// private $_insert  = ' INSERT INTO usuario ( nome, login, senha, id_tipo_usuario) VALUES (:nome, :login, :senha, :id_tipo_usuario) '; 
	// private $_update  = ' UPDATE usuario SET nome = :nome, login = :login, senha = :senha, id_tipo_usuario = :id_tipo_usuario';
	// private $_seqnome = 'usuario_id_seq';
	private $_order   = ' nome ';
	// private $_table   = 'usuario';
	// private $_alias   = 'u';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new TipoUsuarioDAO( $connFactory );
		}
		return self::$instance;
	}

	public function getAll(){
		$list = array();
		$rows = parent::getAll( $order );
		foreach ($rows as $row) {
			$tipoUsuario = new TipoUsuario( $row->id, $row->nome );
			array_push( $list, $tipoUsuario );
		}
		return $list;
	}
	// public function getTable(){
	// 	return $this->_table;
	// }

	// public function getSequencenome(){
	// 	return $this->_seqnome;
	// }

	// public function getInsert(){
	// 	return $this->_insert;
	// }

	// public function getUpdate(){
	// 	return $this->_update;
	// }

	public function getSelect(){
	 	return $this->_select;
	}

	public function getOrder(){
		return $this->_order;
	}

	// public function getTableAlias(){
	// 	return $this->_alias;
	// }

}
?>