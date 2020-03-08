<!DOCTYPE HTML>
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
			<?php
			include("rabbitFunctions.php");
			
			get_from_backend($out);


			?>
			<br>
			<a href="index.php">Back to index.php</a>
		</div>
	</body>
</html>

