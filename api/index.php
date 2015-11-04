<?php 
require '../vendor/autoload.php';

$app = new \Slim\Slim();

function autentica(){
	echo "oi";
};

$app->get('/medidas/:problema', 'autentica', function(){
	echo "oi";
});

$app->run();

 ?>