<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Problema.class.php';
require_once 'DAOFactory.php';

class ProblemaDAO extends DAO
{
	
	private $_select  = 'SELECT p.id, p.descricao, p.data_registro, p.id_usuario, p.id_categoria, p.id_bairro as dataRegistro FROM problema p';
	private $_insert  = 'INSERT INTO problema ( descricao, data_registro ) 
	 					 VALUES (:descricao, :data_registro)'; 
	
	private $_update  = ' UPDATE problema b SET descricao = :descricao';
	private $_seqnome = 'problema_id_seq';
	private $_order   = ' data_registro ';
	private $_table   = 'problema';
	private $_alias   = 'b';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new ProblemaDAO( $connFactory );
		}
		return self::$instance;
	}

	
	public function getAll($order='')
	{
		
		$list = array();
		

		$rows = parent::getAll($order);
		
		
		$conn = ConnectionFactory::getInstance();
		$usuarioDAO   = DAOFactory::getUsuarioDAO( $conn );
		$categoriaDAO = DAOFactory::getCategoriaDAO( $conn );
		$bairroDAO 	  = DAOFactory::getBairroDAO( $conn );
		
		foreach ($rows as $row) 
		{
			$obj = new Problema();
			parent::rowToObj( $row, $obj );
			
			$obj->usuario 	= $usuarioDAO->getById( $row->id_usuario );
			$obj->categoria = $categoriaDAO->getById( $row->id_categoria );
			$obj->bairro 	= $bairroDAO->getById( $row->id_bairro );
			
			array_push($list, $obj);
		}
		
		return $list;
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