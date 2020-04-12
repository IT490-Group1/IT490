
<?php

require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('25.132.137.114', 5672, 'test', 'testHost');
$channel = $connection->channel();

$channel->queue_declare('rmqQueue', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'rmqQueue');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
?>
