<?php
$servername = "127.0.0.1";
$username = "vibhor";
$password = "vibhor";
$dbname = "smartmeter";
$priorityArray=array();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cur_threshold  = 0;
if (isset($_POST['new_threshold'])) {
	$cur_threshold = $_POST['new_threshold'];
}

if (isset($_POST['priorityArray'])) {
    $prstring = $_POST['priorityArray'];
    $priorityArray = explode(',',$prstring);
}

// $prstring = "bulb2,bulb1,bulb3";
// $priorityArray = explode(',',$prstring);

$sql = "UPDATE power_log SET threshold=".$cur_threshold;
if ($conn->query($sql) == TRUE){ 

}
$i = 1;
$q = $i -1;
$sql = "UPDATE components SET priority=".$i." WHERE ID='".$priorityArray[$q]."'";
while($i <= count($priorityArray)){
    $i = $i;
    $q = $i -1;
    $sql = "UPDATE components SET priority=".$i." WHERE ID='".$priorityArray[$q]."'";
    echo $sql;
    if ($conn->query($sql) == TRUE){ 
    }
    $i = $i+1;  
}


$conn->close();
?>
