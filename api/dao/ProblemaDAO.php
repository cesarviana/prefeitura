<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Problema.class.php';
require_once 'DAOFactory.php';

class ProblemaDAO extends DAO
{
	
	private $_select  = 
	'SELECT 
		p.id, 
		p.descricao, 
		p.data_registro as dataRegistro, 
		p.id_usuario, 
		p.id_categoria, 
		p.id_bairro,
		(SELECT COUNT(m.id) FROM medida m WHERE m.id_problema = p.id AND m.solucao = \'t\') AS solucionado
	 FROM problema p
	 LEFT JOIN medida m ON m.id_problema = p.id';

	private $_insert  = 'INSERT INTO problema ( descricao, data_registro, id_categoria, id_bairro, id_usuario ) 
	 					 VALUES (:descricao, :data_registro, :id_categoria, :id_bairro, :id_usuario)'; 
	
	private $_update  = 'UPDATE problema p SET 
						 descricao 	   = :descricao,
						 data_registro = :data_registro,
						 id_categoria  = :id_categoria,
						 id_bairro     = :id_bairro,
						 id_usuario    = :id_usuario';

	private $_seqnome = 'problema_id_seq';
	private $_order   = ' data_registro ';
	private $_table   = 'problema';
	private $_alias   = 'p';

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

	public function save( $problema ){
		if(!$problema)
			throw new Exception('Nenhuma informação para ser salva.', 422);
		$msgErro = "";
		if(!$problema->descricao)
			$msgErro .= 'A descrição do problema deve ser informada.' . PHP_EOL;
		if(!$problema->dataRegistro)
			$msgErro .= 'A data do problema deve ser informada.' . PHP_EOL;
		if(!$problema->usuario)
			$msgErro .= 'O problema deve estar associado a um usuário.' . PHP_EOL;
		if(!$problema->categoria)
			$msgErro .= 'O problema deve estar associado a uma categoria.' . PHP_EOL;
		if(!$problema->bairro)
			$msgErro .= 'O bairro deve ser informado.' . PHP_EOL;

		if( $msgErro )
			throw new Exception( $msgErro , 422 );
			

		parent::save( $problema );
	}
	
	public function getAll($order='')
	{
		
		$list = array();
		
		$rows = parent::getAll($order);
		
		foreach ($rows as $row) 
		{
			array_push($list, $this->toObj( $row ) );
		}
		
		return $list;
	}

	public function getById( $id )
	{
		$row = parent::getById( $id );
		
		if($row)
		{
			return $this->toObj( $row );
		}
	}

	// Converte a linha do result set para um objeto
	private function toObj( $row ){

		$conn 		  = ConnectionFactory::getInstance();
		$usuarioDAO   = DAOFactory::getUsuarioDAO( $conn );
		$categoriaDAO = DAOFactory::getCategoriaDAO( $conn );
		$bairroDAO 	  = DAOFactory::getBairroDAO( $conn );

		$obj = new Problema();

		parent::rowToObj( $row, $obj );

		$obj->usuario 	= $usuarioDAO->getById( $row->id_usuario );
		$obj->categoria = $categoriaDAO->getById( $row->id_categoria );
		$obj->bairro 	= $bairroDAO->getById( $row->id_bairro );

		return $obj;
	}

	protected function bind( &$stmt, $problema ){
		
		$stmt->bindParam('id_categoria', $problema->categoria->id );
		$stmt->bindParam('id_bairro',  $problema->bairro->id );
		$stmt->bindParam('id_usuario', $problema->usuario->id );
		$stmt->bindParam('data_registro', $problema->dataRegistro );

		parent::bind( $stmt, $problema );
	}
	
	public function delete( $ids ){

		$conn 		  = ConnectionFactory::getInstance();
		$medidaDAO 	  = DAOFactory::getMedidaDAO( $conn );

		$numeroMedidas = $medidaDAO->countByProblemaId( $ids ); 

		if( $numeroMedidas > 0 )
			throw new Exception("Não é possível excluir o problema, pois existem medidas ligadas à ele.", 500);
		
		parent::delete( $ids );
	
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