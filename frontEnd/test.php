<?php

echo '
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
		<div>
			<h1>Heyo yall</h1>
		</div>
        <form class="form-horizontal" action="consumer_localhost.php" method="get">
       
                <input type=submit value="Get Data">
	</form>
	';
	
	echo '
        <div id="container" class="container">
	    <h1>Welcome to MedRX</h1>
                <div class="panel-group">
                        <div class="panel panel-default">
                                <div class="panel-body">
                                        <form class="form-horizontal" action="login.php" method="get">
                                                <fieldset id="noEnter">
                                                        <legend>Please Log In</legend>
                                                        <input type=text name="user" autocomplete="off" placeholder="Username"><br>
                                                        <input type=text name="pass" autocomplete="off" placeholder="Password"><br>
                                                        <input type=submit value="Log In">
                                                </fieldset>
            <br>
            <p>Not a registered user?</p>
                <a href="register.html">register here</a> <br>
          </form>
                                </div>
                        </div>
                </div>
        </div>

	</body>
</html>';

echo "Hello World";
include "rabbitFunctions.php";

?>
