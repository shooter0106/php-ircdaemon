<?php
require_once __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function (string $class) {
	$classDirs = [
		__DIR__ . '/src/',
		__DIR__ . '/src/entities/',
	];
	foreach ($classDirs as $classDir) {
		if (strripos($class, '\\') == false) {
			$classFile = $classDir . $class . '.php';
		} else {
			$array = explode('\\', $class);
			$classFile = $classDir . array_pop($array) . '.php';
		};
		if (file_exists($classFile)) {
			include_once $classFile;
		}
	}
});

use IRCPHP\Server;
use Workerman\Worker;
use IRCPHP\Protocol;

$server = new Server();
$protocol = new Protocol();

$tcp_worker = new Worker("tcp://0.0.0.0:6667");

$tcp_worker->onConnect = function($connection) {};

$tcp_worker->onMessage = function($connection, $data) use (&$protocol)
{
	$protocol->readClientMessage($data, $connection);
	$protocol->execCommands();
};

$tcp_worker->onClose = function($connection) {};

Worker::runAll();