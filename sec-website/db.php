<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "user123";

$conn = mysqli_connect($server, $username, $password, $database);

if($conn){
    echo "success";
}
else{
    die("Error". mysqli_connect_error());
}
?>
