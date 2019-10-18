<?php
	session_start();

	require 'vendor/autoload.php';

	\Slim\Slim::registerAutoloader();
	\Moment\Moment::setLocale('es_ES');

	$app = new \Slim\Slim();

	$corsOptions = array(
    	"origin" => "*",
    	"exposeHeaders" => array(
			"X-API-KEY", "Origin", "X-Requested-With" , "Authorization" ,"Content-Type", "Accept", "Access-Control-Request-Method", "x-xsrf-token"
		),
		"maxAge" => 1728000,
    	"allowCredentials" => True,
		"allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
	);
	$app->add(new \CorsSlim\CorsSlim($corsOptions));

	/* enviroment */
	require "env/env.dev.php";
	/* Autoload */
	$folders = [
		'common',
		'models',
		'routes'
	];
	foreach ($folders as $f) {
		foreach (glob("$f/*.php") as $k => $filename) {
			require $filename;
		}
	} 

	
	/* Hello World */
	$app->get('/', function(){
		echo 'Funciona Correctamente';
	});

	$app->get('/peticion/:id', function($id){
		echo 'Funciona Correctamente '.$id;
	});

	$app->get('/atendido/:idCama/:rfid', function($idCama, $rfid){
		echo 'Funcion Cumplida '.$idCama." ".$rfid;
	});
	$app->get('/registro/:rfidRegistro', function($rfidRegistro){
		echo 'Funcion Cumplida '.$rfidRegistro;
	});

	$app->run();
