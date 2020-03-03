<?php
session_start();
session_set_cookie_params(0, "/~rc425/public_html/it202a2/", "web.njit.edu");
?>
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
<?php
echo "Session ID is: ".session_id()."<br>";

$bad = false;

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors' , 1);

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

include("myFunctions.php");
include ("account.php") ;

$db = mysqli_connect($hostname, $username, $password, $project);
mysqli_select_db( $db, $project ); 

getdata("user", $user);
getdata("pass", $pass);
getdata("delay", $delay);
	
if($bad){
	exit("Error");
}

if( !auth($user, $pass, $t) ) {
	redirect("<p class='invalid'>Invalid Credentials. Try again.</p><br>", "login.html", $delay);
	exit();
}

print "user is $user<br>";
print "pass is $pass<br>";
print "delay is $delay<br>";

$_SESSION["logged"] = true;
$_SESSION["user"] = $user;
$s = "select cur_balance from A where user = '$user'";
$t = mysqli_query( $db, $s );
$r = mysqli_fetch_array($t, MYSQLI_ASSOC);
$cur_balance=$r[ "cur_balance"];
$_SESSION["cur_balance"]=$cur_balance;
if(isset($_SESSION["logged"])){
	echo $_SESSION["cur_balance"];
}

redirect("<br>Collecting data 😈...", "formpage.php", $delay);
?>
</html>