<?php


$servername = "127.0.0.1";
$username = "vibhor";
$password = "vibhor";
$dbname = "smartmeter";
$cur_power = 0;
$statusArray = array();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT status FROM components order by priority asc";

$statusArray="";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $i = 0;
    while($row = $result->fetch_assoc()) {
        $statusArray = $statusArray.$row["status"].",";
        $i = $i + 1;
    }
    echo $statusArray;
}
else {
     echo "0 results";
}

$conn->close();
?>