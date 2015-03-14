<?php
	require_once("dbcreds.php");
	// Create connection
	$con = mysqli_connect ($host, $user, $pass, $db);

	// Check connection
	if (mysqli_connect_errno($con)){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$q=$_GET["q"];

	$result = mysqli_query($con,"SELECT * FROM kettering_teams WHERE team_number = '".$q."'");
	echo "<table border-collapse='collapse'>
	<tr>
	<th>team_number</th>
	<th>team_name</th>
	<th>team_location</th>
	</tr>";

	while($row = mysqli_fetch_array($result))
	  {
	  echo "<tr>";
	  echo "<td>" . $row['team_number'] . "</td>";
	  echo "<td>" . $row['team_name'] . "</td>";
	  echo "<td>" . $row['team_location'] . "</td>";
	  echo "</tr>";
	  }
	echo "</table>";

	mysqli_close($con);
?>