<?php

require_once '../vendor/autoload.php';
require_once 'dao/DAOFactory.php';
require_once 'dao/ConnectionFactory.php';

$app = new \Slim\Slim();

$app->get('/test', function(){
	require_once 'dao/DAOTest.php';
});


function createDAO( $daoString ){
	switch ($daoString) {
		case 'usuarios':
			return DAOFactory::getUsuarioDAO( ConnectionFactory::getInstance() );
		case 'tiposUsuario':
			return DAOFactory::getTipoUsuarioDAO( ConnectionFactory::getInstance() );
		case 'bairros':
			return DAOFactory::getBairroDAO( ConnectionFactory::getInstance() );
		case 'categorias':
			return DAOFactory::getCategoriaDAO( ConnectionFactory::getInstance() );
		case 'problemas':
			return DAOFactory::getProblemaDAO( ConnectionFactory::getInstance() );
		default:
			# code...
			break;
	}
}



$app->get('/usuarios/:id',function( $id ){
	$dao = DAOFactory::getUsuarioDAO( ConnectionFactory::getInstance() );
	$dao->getById( $id );
});



$app->get('/:what', function($what) {
	echo json_encode( createDAO( $what )->getAll() );
});



$app->post('/:what', function($what) use ($app) {
	
	$dao = createDAO( $what );
	try {
		echo $dao->save( json_decode( $app->request->getBody() ) );
	} catch (Exception $e){
		$app->response->setStatus( $e->getCode() );
		echo json_encode( array('message'=> $e->getMessage() ));
	}

});



$app->delete('/:what/:ids', function($what, $ids) use($app) {

	$dao = createDAO( $what );
	try {
		echo $dao->delete( explode(',', $ids) );
	} catch (Exception $e){
		$app->response->setStatus( $e->getCode() );
		echo json_encode( array('message'=> $e->getMessage() ));
	}

});


$app->run();

?>