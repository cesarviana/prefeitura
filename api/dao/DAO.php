<?php 
/**
 * Definição abstrata de um objeto de acesso a dados.
 */
require 'StrUtil.php';
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
			return $result;
		} catch (Exception $e) {
			$this->error( $e );
		}
	}

	public function getById( $id )
	{
		$sql = $this->getSelect() . $this->where();
		
		try {
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$stmt->bindParam('id', $id);
			$stmt->execute();
			$result = $stmt->fetchObject();
			$this->finalize();
			return $result;
		} catch (Exception $e) {
			$this->error( $e );	
		}
	}

	public function getByFk( $fkName, $fkValue )
	{
		$fkName = filter_var($fkName);

		$sql = $this->getSelect() . " WHERE $fkName=:fkValue";
		
		try {
			$con  = $this->getConn();
			$stmt = $con->prepare( $sql );
			$stmt->bindParam('fkValue', $fkValue);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$this->finalize();
			return $result;
		} catch (Exception $e) {
			$this->error( $e );	
		}
	}

	public function save( &$obj ) 
	{
		if( isset($obj) && isset($obj->id) && $obj->id ){
			$this->update( $obj );
		} else {
			$this->insert( $obj );
		}
	}

	private function update( &$obj )
	{
		$sql = $this->getUpdate() . $this->where();
			
		$con = $this->getConn();
		$stmt = $con->prepare( $sql );
		$this->bind( $stmt, $obj );
		$stmt->bindParam(':id',$obj->id);
		$stmt->execute();
		$this->finalize();
	
	}

	protected function insert( &$obj )
	{
		$sql = $this->getInsert();
		
		$con = $this->getConn();
		$stmt = $con->prepare( $sql );
		$this->bind( $stmt, $obj );
		$stmt->execute();
		$obj->id = $con->lastInsertId( $this->getSequenceName() );
		$this->finalize();
	
	}

	public function delete( $ids )
	{
		$sql = ' DELETE FROM ' . $this->getTable() . ' WHERE id IN('. implode(',', $ids ).')';
		try {
			$con = $this->getConn();
			$stmt = $con->prepare( $sql );
			$stmt->execute();
			$this->finalize();
		} catch (Exception $e) {
			$this->error( $e );
		}
	}

	// Manage errors
	protected function error( $exception )
	{
		http_response_code( 422 );
		$error = array('message' => $exception->getMessage() );
		return json_encode($error);
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
				$stmt->bindParam($key, $this->convert($value) );
			}
		}
	}

	// Conversão de valores
	private function convert( $value ){
		if(is_bool($value)){
			return $value ? 't' : 'f';
		}
		else return $value;
	}

	protected function rowToObj( $row, &$obj ){
		foreach($row as $rowProperty => $rowProperyValue ){
			foreach($obj as $objProperty => &$objProperyValue ){
				if( $rowProperty == strtolower($objProperty) ){
					$obj->$objProperty = $rowProperyValue;
					break;
				}
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

	private function where(){
		$alias = $this->getTableAlias();
		$alias = $alias ? $alias.'.' : $alias;
		return ' WHERE ' . $alias. 'id=:id ';
	}
	// Return the sequence nome ( using postgres )
	protected function getSequenceName(){}
	protected function getInsert(){}
	protected function getUpdate(){}
	protected function getSelect(){ return 'SELECT * FROM ' . $this->getTable(); }
	protected function getTable(){}
	// Return the order
	protected function getOrder(){}
	protected function getTableAlias(){}
}

 ?>