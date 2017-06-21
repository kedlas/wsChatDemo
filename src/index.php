<?php
use App\Chat\Chat;
use App\Router;
use App\Server;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../vendor/autoload.php';

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
