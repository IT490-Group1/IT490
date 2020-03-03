<?php

function auth( $user, $pass, &$t){

	global $db;

	$hashed = sha1($pass);
	
	$s = "select * from A where user = '$user' and pass = '$hashed' ";
	echo "auth SQL is: $s<br><br>";
	$t = mysqli_query( $db, $s ) or die( mysqli_error());
	
	$num = mysqli_num_rows($t);
	
	if ( $num > 0) {
		echo "<p class='valid'>Good credentials</p><br>";
		return true;
	} else {
		return false;
	}
}

function getdata($input, &$result){
	
	global $bad;
	global $db;
	
	if (!isset ($_GET [$input])){
		$bad = true;
		echo "<p id='invalid'>Bad Credentials</p>";
		return ;
	}
	
	$result = $_GET [$input];
	$result = mysqli_real_escape_string ($db, $result);
}

function redirect($message, $targetFile, $delay){
		echo "$message";
		header ("refresh:$delay , url=$targetFile");
		exit;
}

function gatekeeper() {
	if(!isset($_SESSION["logged"])){
		redirect("<p id='invalid'>log in please</p>", "login.html", 3);
	}
	
}

function show ( $user , &$out) {
	global $db;
	
	$s = "select * from A where user = '$user'";
	$out .= "<br> SQL statement is: $s <br>";
	($t = mysqli_query( $db, $s))  or die( mysqli_error($db));
	$out .= "<table class='displayTable'>
				<caption>Table A</caption>
				<tr class='displayTable'>
					<th class='displayTable'>user</th>
					<th class='displayTable'>plain_pass</th>
					<th class='displayTable'>pass</th>
					<th class='displayTable'>fullname</th>
					<th class='displayTable'>email</th>
					<th class='displayTable'>address</th>
					<th class='displayTable'>cell</th>
					<th class='displayTable'>init_balance</th>
					<th class='displayTable'>cur_balance</th>
				</tr>
				<tr class='displayTable'>";
	while ( $r = mysqli_fetch_array($t,MYSQLI_ASSOC) ) {
					$user = $r[ "user" ];
					$plain_pass = $r[ "plain_pass" ];
					$pass = $r[ "pass" ];
					$fullname = $r[ "fullname" ];
					$email = $r[ "email"];
					$address = $r[ "address" ];
					$cell = $r[ "cell" ];
					$init_balance = $r[ "init_balance" ];
					$cur_balance = $r[ "cur_balance" ];
					
					$out .= "<td class='displayTable'>$user</td>
					<td class='displayTable'>$plain_pass</td>
					<td class='displayTable'>$pass</td>
					<td class='displayTable'>$fullname</td>
					<td class='displayTable'>$email</td>
					<td class='displayTable'>$address</td>
					<td class='displayTable'>$cell</td>
					<td class='displayTable'>$init_balance</td>
					<td class='displayTable'>$$cur_balance</td>";
	};
	$out .= "	</tr>
			</table>";
	
	$s = "select * from T where user = '$user' order by `T`.`date` desc";
	$out .= "<br> SQL statement is: $s <br>";
	($t = mysqli_query( $db, $s) )  or die( mysqli_error($db) );
		
	$num = mysqli_num_rows($t);
	
	$out .= "<table class='displayTable'>
				<caption>Table T</caption>
				<tr class='displayTable'>
					<th class='displayTable'>user</th>
					<th class='displayTable'>type</th>
					<th class='displayTable'>amount</th>
					<th class='displayTable'>date</th>
				</tr>";
	while ( $r = mysqli_fetch_array($t,MYSQLI_ASSOC) ) {
				$type = $r[ "type" ];
				$amount = $r[ "amount" ];
				$date = $r[ "date" ];
				
				if($type=="D"){
					$color="deposit";
				} else{
					$color="withdraw";
				}
				
				$out .= "<tr class='displayTable'>
					<td class='displayTable' id='$color'>$user</td>
					<td class='displayTable' id='$color'>$type</td>
					<td class='displayTable' id='$color'>$amount</td>
					<td class='displayTable' id='$color'>$date</td>
				</tr>";
	};
	$out .= "</table>";
	
	$out .= "<br>";
	
	echo $out;
}

function deposit ( $user , $amount ) {
	global $db;
	
	$update = "update A set cur_balance = cur_balance + '$amount' where user = '$user'";
	$insert = "insert into T value ('$user', 'D', '$amount', NOW() )";
	echo "$update <br />";
	echo "$insert <br />";
	mysqli_query($db, $update);
	mysqli_query($db, $insert);
	
	$_SESSION["cur_balance"]=$_SESSION["cur_balance"]+$amount;
}

function withdraw ( $user , $amount ) {
	global $db;
	
	if ($amount > $_SESSION["cur_balance"]) {
		redirect("<br>You don't have $$amount in your account. Cannot withdraw.", "formpage.php", 3);
		exit();
	}
	
	$update = "update A set cur_balance = cur_balance - '$amount' where user = '$user'";
	$insert = "insert into T value ('$user', 'W', '$amount', NOW() )";
	echo "$update <br />";
	echo "$insert <br />";
	mysqli_query($db, $update);
	mysqli_query($db, $insert);
	
	$_SESSION["cur_balance"]=$_SESSION["cur_balance"]-$amount;
}

function mailer ( $user , $out ) {
	if(isset($_GET["emailresults"])) { 
		echo "Mail copy sent!<br>";
		
		global $db;
		$email;
		$s = "select email from A where user = '$user'";
		($t = mysqli_query( $db, $s))  or die( mysqli_error($db));
		while ( $r = mysqli_fetch_array($t,MYSQLI_ASSOC) ) {
			$email = $r[ "email"];
		};
		
		date_default_timezone_set('America/New_York');
		
		$to = $email;
		$subject = date("l m/d/y h:i:s A");
		$message = $out;
		
		mail ($to, $subject, $message);
	}  
	else {
		echo "Mail copy was not requested.<br>";
	} 
}
?>
