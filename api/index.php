<?php

require_once '../vendor/autoload.php';
require_once 'dao/DAOFactory.php';
require_once 'dao/ConnectionFactory.php';
require_once 'dao/RelatorioProblemasMes.php';
require_once 'dao/RelatorioProblemasCategoria.php';
require_once 'dao/RelatorioCustoCategoria.php';

$app = new \Slim\Slim();

$app->get('/test', function(){
	require_once 'dao/DAOTest.php';
});

function auth(){
	session_start();
}

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
		case 'medidas':
			return DAOFactory::getMedidaDAO( ConnectionFactory::getInstance() );
		default:
			// tenta instanciar a classe pela string
			return new $daoString( ConnectionFactory::getInstance() );
	}
}


// get by id
$app->get('/:what/:id',function( $what, $id ){
	echo json_encode( createDAO($what)->getById($id) );
});

// get all
$app->get('/:what', function($what) {
	echo json_encode( createDAO( $what )->getAll() );
});

// get by fk
$app->get('/:what/:fkName/:fkValue', function($what, $fkName, $fkValue ) {
	echo json_encode( createDAO( $what )->getByFk($fkName, $fkValue) );
});



$app->post('/login', 'auth', function() use ($app) {
	
	$dao = createDAO( 'usuarios' );
	$body = json_decode( $app->request->getBody() );
	
	try {
		$data = array
		(
			'sessionId' => session_id(),
			'usuario' => $dao->getByAccess( $body->usuario, $body->senha ) 
		);

		echo json_encode( $data );

	} catch (Exception $e){
		$app->response->setStatus( $e->getCode() );
		echo json_encode( array('message'=> $e->getMessage() ));
	}

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