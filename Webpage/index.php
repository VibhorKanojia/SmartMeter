<?php
$servername = "127.0.0.1";
$username = "vibhor";
$password = "vibhor";
$dbname = "smartmeter";
$cur_power = 0;
$cur_threshold = 0;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
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
    echo "0 results";
}




$sql = "SELECT status FROM components order by priority asc";

$statusArray = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $i = 0;
    while($row = $result->fetch_assoc()) {
        $statusArray[$i] = $row["status"];
        $i = $i + 1;
    }
}
else {
     echo "0 results";
}
  
?> 


<html>
<head>
	<meta charset="utf-8">
	<title>HTML5 Sortable jQuery Plugin</title>
	<link href="css/style.css" rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="js/jquery.sortable.js"></script>
	<script>

	$(document).ready(function(){
		var intervalID = setInterval(optimize, 1000);
	});

	function getPriority(){
		var priorityArray = [];
		$('ul li').each(function(i)
		{
		    // This is your rel value
		    priorityArray.push($(this).attr('id'));

		});
		return priorityArray;
		console.log(priorityArray);
	}	

	
	
	function optimize(){
		var statusArray = <?php echo '["' . implode('", "', $statusArray) . '"]' ?>;
		console.log(statusArray);
		var count = 0;
		/*
		$('ul li').each(function(i)
		{
			if (statusArray[count] == 'A'){
		    	$(this).css("background","#000");
		    }
		    else{
		    	$(this).css("background","#555");	
		    }
		
		    count++;
		});
		*/
		priorityArray = getPriority();
		console.log("start");
		var cur_power = document.getElementById("cur_power").innerHTML;
		var threshold_power = document.getElementById("voltage").value;
		if (Number(threshold_power) == 0){
			threshold_power = Number(<?php echo $cur_threshold; ?>);
		}

		$.get("getCurReading.php", function(data, status){
			cur_power = data;
			document.getElementById("cur_power").innerHTML = cur_power;
			console.log("Fetching current data");
			console.log(cur_power);
		});

		var stringed = priorityArray.join(',');
		console.log(stringed);
		$.ajax({
		    url: 'updateDatabase.php', // url is empty because I'm working in the same file
		    data: {
		    	'new_threshold':threshold_power,
		    	'priorityArray':stringed
			},
		    type: 'post',
		});
	};


		
	</script>


</head>

<body>

	<header>
		<h1>Embedded Systems Lab</h1>
	</header>
	<section>
		<h2>ALL COMPONENTS</h2>
		<ul class="exclude list" id="componentList">
			<li id="bulb1" class="enabled">Bulb 1</li>
			<li id="bulb2" class="enabled">Bulb 2</li>
			<li id="bulb3" class="enabled">Bulb 3</li>
		</ul>
	</section>
	<br>
	<br>
	<section>
  			<label for="current">Current Power Reading : </label>
  			<label id = "cur_power" for="reading"><?php echo $cur_power ?></label>
  			<br><br>
  			<label for="threshold">Enter Threshold Value</label>
  			<br><br>
  			<input type="text" name="voltage" value="" id="voltage"><br>
  			<br>
  			<input type="submit" value="Submit" onclick="optimize()"> 
	</section>

	
	<script>
		$(function() {
			$('.sortable').sortable();
			$('.handles').sortable({
				handle: 'span'
			});
			$('.connected').sortable({
				connectWith: '.connected'
			});
			$('.exclude').sortable({
				items: ':not(.disabled)'
			});
		});

		document.addEventListener('dblclick', function(e) {
    		e = e || window.event;
    		var target = e.target || e.srcElement,
        	text = target.textContent || text.innerText;   
			
			var curClass = target.getAttribute("class");
			if (curClass == "disabled"){
				target.setAttribute("class", "enabled");
				target.setAttribute("draggable","true");

			}
			else if (curClass == "enabled"){
				target.setAttribute("class","disabled");
				target.setAttribute("draggable","false");
			}

			$('.exclude').sortable({
				items: '.enabled'
			});
			}, false);
		
	</script>
</body>
</html>
