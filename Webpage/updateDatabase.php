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

$cur_power = 0;
$cur_threshold  = 0;
if (isset($_POST['new_power'])) {
	$cur_power = $_POST['new_power'];
}
if (isset($_POST['new_threshold'])) {
	$cur_threshold = $_POST['new_threshold'];
}

if (isset($_POST['new_statusArray'])) {
	$statusArray = $_POST['new_statusArray'];
	$statusArray = explode(',', $statusArray);
}

$sql = "UPDATE power_log SET power=".$cur_power.",threshold=".$cur_threshold;
if ($conn->query($sql) == TRUE){ 

}

$i = 0;
$c = 0;




$sql = "SELECT count(*) as count from components where status='X'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $c = $row["count"];
    }
}


$c = (int)$c;
$i = (int)$i; 
while (($i+$c) < count($statusArray)){
	$p = (int)($i+1);
	$q = $i + $c;
	$sql = "UPDATE components SET status='".$statusArray[$q]."' WHERE priority=".$p;
	$i=(int)$i + 1;
	if ($conn->query($sql) == TRUE){ 
	}
}

$conn->close();
?>
