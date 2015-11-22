<?php 
// Testa o dao de usuário

require_once 'ConnectionFactory.php';
require_once 'DAOFactory.php';

function createUsuarioDAO(){
	$connFactory = ConnectionFactory::getInstance();
	return DAOFactory::getUsuarioDAO( $connFactory );
}

function test_getAll(){
	$dao = createUsuarioDAO();
	print_r( $dao->getAll() );
}
function test_getById(){
	print_r( createUsuarioDAO()->getById(1) );
}
function test_save_delete(){
	$usuario = (object) array
	(
		'id'   => null,
		'nome' => 'Cristiane',
		'login'=> 'cris',
		'id_tipo_usuario' => 1,
		'senha'=> 'jfjfjf'
	);
	$dao = createUsuarioDAO();
	$dao->save( $usuario );
	// update
	$usuario->nome = 'Cristiane Viana';
	$dao->save( $usuario );
	// delete
	$dao->delete( $usuario );
}
//phpinfo();
test_getAll();
test_getById();
test_save_delete();

 ?>