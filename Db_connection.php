<?php 
$servername = "rajat-VirtualBox"; 
$username = "rajat"; 
$password = "Soumya!07"; 
$db = "IT_490";

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors' , 1);

if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

//Create Connection 
$conn = new mysqli($servername, $username, $password, $db); 

//Check Connection 
if ($conn->connect_error){
    die("Connection failed:" . $conn->connect_error);
}
echo "Connected Successfully";

	
$s = "select * from Drug_Info";
($t = mysqli_query( $db, $s))  or die( mysqli_error($db));
while ( $r = mysqli_fetch_array($t,MYSQLI_ASSOC) ) {
    $drug_id = $r[ "drug_id" ];
    $drug_name = $r["drug_name" ];

    echo $drug_name;
    echo $drug_id;
}
?>