<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Categoria.class.php';

class CategoriaDAO extends DAO
{
	
	private $_select  = 'SELECT c.id, c.nome FROM categoria c';
	private $_insert  = ' INSERT INTO categoria ( nome ) VALUES (:nome) '; 
	private $_update  = ' UPDATE categoria b SET nome = :nome';
	private $_seqnome = 'categoria_id_seq';
	private $_order   = ' nome ';
	private $_table   = 'categoria';
	private $_alias   = 'c';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new CategoriaDAO( $connFactory );
		}
		return self::$instance;
	}

	public function getTable(){
		return $this->_table;
	}

	public function getSequencenome(){
		return $this->_seqnome;
	}

	public function getInsert(){
		return $this->_insert;
	}

	public function getUpdate(){
		return $this->_update;
	}

	public function getSelect(){
	 	return $this->_select;
	}

	public function getOrder(){
		return $this->_order;
	}

	public function getTableAlias(){
		return $this->_alias;
	}


}


 ?>