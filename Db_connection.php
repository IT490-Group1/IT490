<?php 
$servername = "rajat-VirtualBox"; 
$username = "rajat"; 
$password = "Soumya!07"; 

//Create Connection 
$conn = new mysqli($servername, $username, $password); 

//Check Connection 
if ($conn->connect_error){
    die("Connection failed:" . $conn->connect_error);
}
echo "Connected Successfully";
?>