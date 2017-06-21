<?php
use App\Chat\Chat;
use App\Router;
use App\Server;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$loader = require dirname(__DIR__) . '/vendor/autoload.php';

sleep(100000);

$router = new Router(new Chat());

$server = IoServer::factory(
	new HttpServer(
		new WsServer(
			new Server($router)
		)
	),
	8080
);

$server->run();
