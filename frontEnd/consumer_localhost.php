<!DOCTYPE HTML>

<?php
<html>
	<head>
		<title>Richard Ching</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
		<link rel="stylesheet" href="styles.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">

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
			<br>
			<a href="formpage.php">Back to formpage.php</a>
		</div>
	</body>
</html>