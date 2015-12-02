<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Medida.class.php';

class MedidaDAO extends DAO
{
	
	private $_select  = 
	'SELECT 
		m.id, 
		m.descricao, 
		m.custo, 
		m.solucao, 
		m.id_usuario 
	 FROM 
	  	medida m';
	
	private $_insert  = 'INSERT INTO medida ( descricao, custo, solucao, id_usuario, id_problema ) 					
	 					 VALUES (:descricao, :custo, :solucao, :id_usuario, :id_problema ) ';
	
	private $_update  = 
	'UPDATE medida m SET 
		descricao = :descricao, 
		custo=:custo, 
		solucao=:solucao, 
		id_usuario=:id_usuario, 
		id_problema=:id_problema
		';

	private $_seqnome = 'medida_id_seq';
	private $_order   = ' nome ';
	private $_table   = 'medida';
	private $_alias   = 'm';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new MedidaDAO( $connFactory );
		}
		return self::$instance;
	}

	public function save( $medida ){

		if(!$medida->custo) $medida->custo = 0;
		
		$msgErro = "";

		if( $medida->custo < 0 )
			$msgErro .= "Valor não pode ser negativo.";

		if(!$medida->descricao)
			$msgErro .= "A descrição deve ser informada.";

		if(!$medida->problema)
			$msgErro .= "A medida precisa estar associada a um problema.";

		if($msgErro) throw new Exception($msgErro, 422);

		parent::save( $medida );
			
	}

	public function bind( &$stmt, $medida ){
		$stmt->bindParam('id_usuario', $medida->usuario->id );
		$stmt->bindParam('id_problema', $medida->problema->id );
		parent::bind($stmt, $medida);
	}

	public function getByFk( $fkName, $fkValue ){
		$list = array();
		$rows = parent::getByFk( $fkName, $fkValue );
		foreach ($rows as $row) {
			array_push($list, $this->toObj($row));
		}
		return $list;
	}

	public function countByProblemaId( $ids )
	{
		$sql = 'SELECT COUNT(1) AS count FROM medida WHERE id_problema IN('. implode(',', $ids).')';

		$con = $this->getConn();
		$stmt = $con->prepare( $sql );
		$stmt->execute();
		return $stmt->fetchObject()->count;
	}

	private function toObj($row){
		$usuarioDAO = DAOFactory::getUsuarioDAO(ConnectionFactory::getInstance());
		$medida = new Medida();
		parent::rowToObj($row, $medida);
		$medida->usuario = $usuarioDAO->getById( $row->id_usuario );
		return $medida;
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