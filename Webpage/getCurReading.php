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

$sql = "SELECT * FROM power_log";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $cur_power = $row["power"];
        $cur_threshold = $row["threshold"];
    }
} else {
    echo "ts";
}
echo $cur_power;
