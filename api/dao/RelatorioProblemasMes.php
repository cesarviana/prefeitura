<?php 

require_once 'ConnectionFactory.php';

class RelatorioProblemasMes
{
	private $_select  = 
	"SELECT
		COUNT(id) as quantidade,
		to_char( data_registro, 'MM/YYYY' ) as mes 
	 FROM
		problema p
	 GROUP BY mes 
     ORDER BY mes;";

    private $connFactory;

	public function __construct( $connFactory )
	{
		$this->connFactory = $connFactory;
	}

	public function getAll(){
		$con = $this->connFactory->getConnection();
		$stmt = $con->query( $this->_select );
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

}

?>