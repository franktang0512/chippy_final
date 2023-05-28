<?php
//使用postgress再使用
// $pg_host        = "host=ec2-34-233-214-228.compute-1.amazonaws.com";
// $pg_port        = "port=5432";
// $pg_dbname      = "dbname=d69u85fv8eq57s";
// // $credentials = "ssl=true&sslfactory=org.postgresql.ssl.NonValidatingFactory user=obnetcgcbbxpbl password=f9962202a36812df8f540b57baa67c68f820eb76d173235711bdf6362ccc4525";
// $credentials = "user=obnetcgcbbxpbl password=f9962202a36812df8f540b57baa67c68f820eb76d173235711bdf6362ccc4525";
// $pg_conn=pg_connect("$pg_host $pg_port $pg_dbname $credentials");
// if($pg_conn==false){
//     echo "Connect Profession Database Error!!";
// }
?>


<?php
$servername = "localhost";
$username = "viplab";
$password = "chippy2022";
//$db ="chippyfinal";
$db ="chippy_test";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>