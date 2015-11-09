<?php
require '../vendor/autoload.php';
require 'dao/DAOFactory.php';
require 'dao/ConnectionFactory.php';

$app = new \Slim\Slim();

function conecta(){
	echo "conecta";
}

function autentica(){
	echo "autentica";
}

// $app->get('/', function(){
// 	$dao = DAOFactory::getUserDAO( ConnectionFactory::getConnection() );
// 	$dao->getById();
// });

$app->get('/usuarios/:id', 'conecta','autentica', function($id){
	$dao = DAOFactory::getUserDAO( ConnectionFactory::getInstance() );
	$dao->getById( $id );
});

$app->run();

 ?>