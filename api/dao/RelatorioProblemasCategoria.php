<?php 

require_once 'ConnectionFactory.php';

class RelatorioProblemasCategoria
{
	private $_select  = 
	"SELECT c.nome AS categoria,
       COUNT(p.id) AS quantidade
	 FROM problema p
	 RIGHT JOIN categoria c ON p.id_categoria = c.id
	 GROUP BY categoria
	 ORDER BY categoria";

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