<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Bairro.class.php';

class BairroDAO extends DAO
{
	
	private $_select  = 'SELECT b.id, b.nome FROM bairro b';
	private $_insert  = ' INSERT INTO bairro ( nome ) VALUES (:nome) '; 
	private $_update  = ' UPDATE bairro b SET nome = :nome';
	private $_seqnome = 'bairro_id_seq';
	private $_order   = ' nome ';
	private $_table   = 'bairro';
	private $_alias   = 'b';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new BairroDAO( $connFactory );
		}
		return self::$instance;
	}

	public function save( $bairro ){
		if(!$bairro)
			throw new Exception('Nenhuma informação para ser salva.', 422);
		if(!$bairro->nome)
			throw new Exception('O nome do bairro deve ser informado.', 422);
		parent::save( $bairro );
	}

	public function getTable(){
		return $this->_table;
	}

	public function getSequenceName(){
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