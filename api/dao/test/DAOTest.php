<?php 
// Testa o dao de usuário

require '../ConnectionFactory.php';
require '../DAOFactory.php';

function createUserDAO(){
	$connFactory = ConnectionFactory::getInstance();
	return DAOFactory::getUserDAO( $connFactory );
}

function test_getAll(){
	$dao = createUserDAO();
	print_r( $dao->getAll() );
}
function test_getById(){
	print_r( createUserDAO()->getById(1) );
}
function test_save_delete(){
	$user = (object) array
	(
		'id'   => null,
		'nome' => 'Cristiane',
		'login'=> 'cris',
		'id_tipo_usuario' => 1,
		'senha'=> 'jfjfjf'
	);
	$dao = createUserDAO();
	$dao->save( $user );
	// update
	$user->nome = 'Cristiane Viana';
	$dao->save( $user );
	// delete
	$dao->delete( $user );
}
//phpinfo();
test_getAll();
test_getById();
test_save_delete();

 ?>