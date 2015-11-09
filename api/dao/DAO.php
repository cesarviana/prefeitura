<?php 
/**
 * Definição abstrata de um objeto de acesso a dados.
 */
require '../../util/StrUtil.php';
abstract class DAO
{
	private $connFactory; // fábrica de conexão
	private $con; // conexão

	protected function __construct( ConnectionFactory $connFactory )
	{
		$this->connFactory = $connFactory;
	}

	// Return connection
	protected function getConn()
	{
		if(!$this->con) 
			$this->con = $this->connFactory->getConnection();
		return $this->con;
	}

	// Manage errors
	protected function error( $exception, $errorCode=500 )
	{
		http_response_code( $errorCode );
		$error = array('text' => $exception->getMessage() );
		echo json_encode($error);
	}

	// Return json result
	protected function result( $result )
	{
		echo json_encode($result);
	}

	// Close connection
	protected function finalize()
	{
		$this->con = null;
	}

	// Bind values from obj to statement
	protected function bind( &$stmt, $obj )
	{
		$words = StrUtil::multiexplode( array(' ','=',',','(',')'), $stmt->queryString );
		foreach ($obj as $key => &$value) {
			if( $this->paramExists( $words, $key ) ){
				$stmt->bindParam($key, $value);
			}
		}
	}

	private function paramExists( $words, $param )
	{
		foreach ( $words as $w ) {
			if( !empty($w) && ':'.$param == trim($w) ) return true;
		}
		return false;
	}

	public function getAll($order='')
	{
		$sqlOrder = '';
		
		if( $order || $this->getOrder() ){
			$sqlOrder = ' ORDER BY ';
			$sqlOrder .=  $order ? $order : $this->getOrder();
		}
		
		$sql = $this->getSelect() . ' ' . $sqlOrder;

		try {
			$con = $this->getConn();
			$stmt = $con->query( $sql );
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$this->finalize();
			$this->result($result);
		} catch (Exception $e) {
			$this->error( $e );
		}
	}

	public function getById( $id )
	{
		$sql = $this->getSelect() . ' WHERE id=:id ';

		try {
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$stmt->bindParam('id', $id);
			$stmt->execute();
			$result = $stmt->fetchObject();
			$this->finalize();
			$this->result($result);
		} catch (Exception $e) {
			$this->error( $e );	
		}
	}

	public function save( &$obj ) 
	{
		if(isset($obj->id) && $obj->id){
			$this->update( $obj );
		} else {
			$this->insert( $obj );
		}
	}

	private function update( &$obj )
	{
		$sql = $this->getUpdate() . ' WHERE id=:id';
		try {	
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$this->bind( $stmt, $obj );
			$stmt->bindParam(':id',$obj->id);
			$stmt->execute();
			$this->finalize();
		} catch (Exception $e) {
			$this->error( $e );
		}	
	}

	private function insert( &$obj )
	{
		$sql = $this->getInsert();
		try {
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$this->bind( $stmt, $obj );
			$stmt->execute();
			$obj->id = $con->lastInsertId( $this->getSequenceName() );
			$this->finalize();
		} catch (Exception $e) {
			$this->error( $e );
		}	
	}

	public function delete( $obj )
	{
		$sql = ' DELETE FROM ' . $this->getTable() . ' WHERE id=:id ';
		try {
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$stmt->bindParam(':id',$obj->id);
			$stmt->execute();
			$this->finalize();
		} catch (Exception $e) {
			$this->error( $e );
		}
	}

	// Return the sequence name ( using postgres )
	protected abstract function getSequenceName();
	protected abstract function getInsert();
	protected abstract function getUpdate();
	protected function getSelect(){ return 'SELECT * FROM ' . $this->getTable(); }
	protected abstract function getTable();
	// Return the order
	protected function getOrder(){return ''; }
}

 ?>