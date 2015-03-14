<?php
	require_once("dbcreds.php");
	// Create connection
	$con = mysqli_connect ($host, $user, $pass, $db);
	
	// Check connection
	if (mysqli_connect_errno ($con))  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$q = $_GET["q"];
	
	$result = mysqli_query ($con, "SELECT * FROM miket_team_list WHERE team_name = '".$q."'");		//This is to get the team number
																									//and name.

	while ($row = mysqli_fetch_array($result)) {
		$team_number = $row['team_number'];
		$team_name = $row["team_name"];
	}	
	
	$result = mysqli_query($con, "SELECT * FROM miket_results_2014 WHERE team_number = '".$team_number."'");
	
	$i = 0;

	while($row = mysqli_fetch_array($result))  {
		$match_num[$i] = $row['match_number'];
		$auton_top[$i] = $row['auton_top'];
		$auton_mid[$i] = $row['auton_mid'];
		$auton_bot[$i] = $row['auton_bot'];
		$auton_miss[$i] = $row['auton_missed'];
		$teleop_top[$i] = $row['teleop_top'];
		$teleop_mid[$i] = $row['teleop_mid'];
		$teleop_bot[$i] = $row['teleop_bot'];
		$teleop_miss[$i] = $row['teleop_missed'];
		$endgame_10[$i] = $row['final10'];
		$endgame_20[$i] = $row['final20'];
		$endgame_30[$i] = $row['final30'];
		$endgame_miss_10[$i] = $row['attempt_10'];
		$endgame_miss_20[$i] = $row['attempt_20'];
		$endgame_miss_30[$i] = $row['attempt_30'];
		$i++;
	}

 	echo"<table> 
		<tr> <th class='title' colspan='14'>Kettering University FiM</th></tr>;
		<tr>
			<th class='title'>".$team_number."</th>
			<th class='title' colspan='13'>".$team_name."</th>
		</tr>
		<tr>
			<th class='head'>Match Number:</th>";	

	$match_count = count($match_num);
	
	$auton_pts = 0;
	$auton_shot = 0;
	$auton_made = 0;
	
	$teleop_pts = 0;
	$teleop_shot = 0;
	$teleop_made = 0;
	
	$endgame_pts = 0;
	$endgame_shot = 0;
	$endgame_made = 0;
	
	for ($i = 0; $i <= ($match_count-1); $i++)  {
		echo "<th class='matchNum'>".$match_num[$i]."</th>";
		$auton[$i] = ($auton_top[$i] * 6) + ($auton_mid[$i] * 4) + ($auton_bot[$i] * 2);
			$auton_pts += $auton[$i];
			$auton_shot += $auton_top[$i] + $auton_mid[$i] + $auton_bot[$i] + $auton_miss[$i];
			$auton_made += $auton_top[$i] + $auton_mid[$i] + $auton_bot[$i];
			
		$teleop[$i] = ($teleop_top[$i] * 3) + ($teleop_mid[$i] * 2) + ($teleop_bot[$i] * 1);
			$teleop_pts += $teleop[$i];
			$teleop_shot += $teleop_top[$i] + $teleop_mid[$i] + $teleop_bot[$i] + $teleop_miss[$i];
			$teleop_made += $teleop_top[$i] + $teleop_mid[$i] + $teleop_bot[$i];
			
		$endgame[$i] = ($endgame_10[$i] * 10) + ($endgame_20[$i] * 20) + ($endgame_30[$i] * 30);
			$endgame_pts += $endgame[$i];
			$endgame_shot += $endgame_10[$i] + $endgame_20[$i] + $endgame_30[$i] + $endgame_miss_10[$i] + $endgame_miss_20[$i] + $endgame_miss_30[$i];
			$endgame_made += $endgame_10[$i] + $endgame_20[$i] + $endgame_30[$i];
		$total[$i] = $auton[$i] + $teleop[$i] + $endgame[$i];
	};
	
	echo "<th class='matchNum'>Total</th> </tr> ";
/////////////////////////////////////////////////////////////////////////////////////////////////
	echo "<tr> <th class='head'>Auton:</th>";

	for ($i = 0; $i <= ($match_count-1); $i++)  {
		echo "<td class='data'>".$auton[$i]."</td>";
	};
	echo "<td class='total'>".$auton_pts."</td></tr>";
/////////////////////////////////////////////////////////////////////////////////////////////////	
	echo "<tr>   <th class='head'>Teleop:</th>";
	
	for ($i = 0; $i <= ($match_count-1); $i++)  {
		echo "<td class='data'>".$teleop[$i]."</td>";
	};
	
	echo "<td class='total'>".$teleop_pts."</td></tr>";
/////////////////////////////////////////////////////////////////////////////////////////////////
	echo "<tr>  <th class='head'>Endgame:</th>";
	
	for ($i = 0; $i <= ($match_count-1); $i++)  {
		echo "<td class='data'>".$endgame[$i]."</td>";
	}
	
	echo "<td class='total'>".$endgame_pts."</td></tr>";
/////////////////////////////////////////////////////////////////////////////////////////////////
	echo "<tr>  <th class='head'>Total:</th>";
	
	for ($i = 0; $i <= ($match_count-1); $i++)  {
		echo "<td class='total'>".$total[$i]."</td>";
	}
	
	echo"<td class='total'>".($auton_pts + $teleop_pts + $endgame_pts)."</td></tr></table>";
	
	mysqli_close($con);
?> 