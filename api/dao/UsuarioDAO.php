<?php 
/**
* Objeto de acesso aos dados de usuário
*/
require_once 'DAO.php';
require_once 'Usuario.class.php';
require_once 'TipoUsuario.class.php';

class UsuarioDAO extends DAO
{
	private $_select  = 
	'SELECT
		u.id,
		u.nome ,
		u.login,
		u.senha, 
		tp.nome AS tipo,
		tp.id   AS id_tipo
	FROM usuario u
	LEFT JOIN tipo_usuario tp 
		   ON u.id_tipo_usuario = tp.id
		   ';

	private $_insert  = ' INSERT INTO usuario ( nome, login, senha, id_tipo_usuario) VALUES (:nome, :login, :senha, :id_tipo_usuario) '; 
	private $_update  = ' UPDATE usuario u SET nome = :nome, login = :login, senha = :senha, id_tipo_usuario = :id_tipo_usuario';
	private $_seqnome = 'usuario_id_seq';
	private $_order   = ' nome ';
	private $_table   = 'usuario';
	private $_alias   = 'u';

	private static $instance;

	protected function __construct( $connFactory )
	{
		parent::__construct( $connFactory );
	}

	public static function getInstance( $connFactory )
	{
		if(!isset(self::$instance)){
			self::$instance = new UsuarioDAO( $connFactory );
		}
		return self::$instance;
	}

	public function save( $usuario ){
		if(!$usuario)
			throw new Exception('Nenhuma informação para ser salva.', 422);
		$msgErro = "";
		if(!$usuario->nome)
			$msgErro .= 'O nome do usuário deve ser informado.' . PHP_EOL;
		if(!$usuario->senha)
			$msgErro .= 'A senha do usuário deve ser informada.' . PHP_EOL;
		if(!$usuario->login)
			$msgErro .= 'O login deve ser informado.' . PHP_EOL;
		if(!$usuario->tipo)
			$msgErro .= 'O tipo do usuário deve ser informado.' . PHP_EOL;

		if( $msgErro )
			throw new Exception( $msgErro , 422 );
			

		parent::save( $usuario );
	}

	/**
	 * Faz o bind das propriedades de segundo nível
	 */
	protected function bind( &$stmt, $usuario ){
		$stmt->bindParam('id_tipo_usuario', $usuario->tipo->id );
		parent::bind( $stmt, $usuario );
	}

	public function getAll($order=''){
		$list = array();
		$rows = parent::getAll( $order );
		foreach ($rows as $row) {
			array_push( $list, $this->createObj( $row ) );
		}
		return $list;
	}

	public function getById($id){
		$row = parent::getById($id);
		return $this->createObj( $row );
	}

	public function getByAccess( $usuario, $senha )
	{
		$senha = sha1( $usuario.$senha );
		$sql = $this->getSelect() . ' WHERE senha LIKE :senha';
		$con = $this->getConn();
		$stmt = $con->prepare( $sql );
		$stmt->bindParam('senha', $senha);
		$stmt->execute();
		$result = $stmt->fetchObject();
		
		if( !$result )
			throw new Exception('Usuário ou senha inválidos.', 401);

		return $this->createObj( $result );
	}

	public function createObj( $row ){
		$usuario = new Usuario();
		parent::rowToObj( $row, $usuario );
		$usuario->tipo = new TipoUsuario( $row->id_tipo, $row->tipo );
		return $usuario;
	}

	protected function insert($usuario){
		// Valida
		$con = $this->getConn();
		$stmt = $con->prepare( 'SELECT COUNT(1) FROM usuario WHERE usuario.login LIKE :login' );
		$stmt->bindParam('login', $usuario->login );
		$stmt->execute();
		$result = $stmt->fetchObject();
		if( $result->count > 0 )
			throw new Exception("O login informado já existe.", 422);
		parent::insert($usuario);
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