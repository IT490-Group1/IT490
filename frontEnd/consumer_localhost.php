<?php


require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('from_backend', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
	echo ' [x] Received ', $msg->body, "\n";
	$out = '<div>';
	
	$out .= '<p>'.$msg.'</p>';
	
	$out .= '</div>';
	echo $out;
	
};

$channel->basic_consume('from_backend', '', false, true, false, false, $callback);

while ($channel->callbacks) {
	$channel->wait();
}

$channel->close();
$connection->close();



?>
