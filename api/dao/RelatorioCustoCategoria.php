<?php 

require_once 'ConnectionFactory.php';

class RelatorioCustoCategoria
{
	private $_select  = 
	"SELECT
		c.nome AS categoria,
		SUM(m.custo) AS custo_total
	FROM
		categoria c
	RIGHT JOIN problema p
		ON p.id_categoria = c.id
	RIGHT JOIN medida m
		ON m.id_problema = p.id
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