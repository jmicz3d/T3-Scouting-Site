<?php

// Create connection
$con = mysqli_connect ($hostname="localhost", $user="James", $pass="bootsector", $dbase="t3_prototype_2013_14_db");

// Check connection
if (mysqli_connect_errno ($con))  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Get query
$q = $_GET["q"];
$yr = $_GET["y"];


// Check query for team number. Using prepared statements for security.
if ($tmEvStmt = mysqli_prepare($con, "SELECT event_id FROM team_event_".$yr." WHERE team_number = ?"))
{
	mysqli_stmt_bind_param($tmEvStmt, "i", $q);
	
	mysqli_stmt_execute($tmEvStmt);
	
	mysqli_stmt_bind_result($tmEvStmt, $c1);
	
	while (mysqli_stmt_fetch($tmEvStmt))
	{
		$event_id[] = $c1;
	}
	mysqli_stmt_close($tmEvStmt);
}
// If there are no results (which if true does not add considerable time to the request) 
// then move to event page. But if it does, load team page.
if (isset($event_id))
{
	foreach ($event_id as $curr_event)
	{
		if ($rtStmt = mysqli_prepare($con, "SELECT * FROM ".$curr_event."_results_".$yr." WHERE team_number = ?"))
		{
			mysqli_stmt_bind_param($rtStmt, "i", $q);
			
			mysqli_stmt_execute($rtStmt);
			
			mysqli_stmt_bind_result($rtStmt, $col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $col11, $col12, $col13, $col14, $col15, $col16);
			
			while (mysqli_stmt_fetch($rtStmt))
			{
				$match_number[] = $col2;
				$auton_top[] = $col3;
				$auton_mid[] = $col4;
				$auton_bot[] = $col5;
				$auton_missed[] = $col6;
				$teleop_top[] = $col7;
				$teleop_mid[] = $col8;
				$teleop_bot[] = $col9;
				$teleop_missed[] = $col9;
				$attempt_10[] = $col10;
				$attempt_20[] = $col11;
				$attempt_30[] = $col12;
				$final_10[] = $col13;
				$final_20[] = $col14;
				$final_30[] = $col16;
			}
			mysqli_stmt_close($rtStmt);
		}
		if ($evIdStmt = mysqli_prepare($con, "SELECT event_name FROM event_list WHERE event_id = ?"))
		{
			mysqli_stmt_bind_param($evIdStmt, "s", $curr_event);
			
			mysqli_stmt_execute($evIdStmt);
			
			mysqli_stmt_bind_result($evIdStmt, $event_name);
			mysqli_stmt_fetch($evIdStmt);
			mysqli_stmt_close($evIdStmt);
		}
		
		if ($tmNaStmt = mysqli_prepare($con, "SELECT team_name FROM team_list WHERE team_number = ?"))
		{
			mysqli_stmt_bind_param($tmNaStmt, "s", $q);
			
			mysqli_stmt_execute($tmNaStmt);
			
			mysqli_stmt_bind_result($tmNaStmt, $team_name);
			mysqli_stmt_fetch($tmNaStmt);
			mysqli_stmt_close($tmNaStmt);
		}
	}

	// current vars from db: team number (q) and name, event id and name, match number, auton, teleop, and climbing 1, 2, 3, and missed
	
	echo "<h1 id='title'>".$team_name."&nbsp;&nbsp;|&nbsp;&nbsp;".$q."</h1>";
	
	echo "<table class='c-event-team-table'>
				<tbody>
					<tr>
						<td class='c-event-head'>".$event_name." - ".$curr_event."</td>
					</tr>
				</tbody>
			</table>";
	
	foreach ($match_number as $n => $cur_match_num)
	{
		$auton_total = $auton_top[$n] + $auton_mid[$n] + $auton_bot[$n];
		$teleop_total = $teleop_top[$n] + $teleop_mid[$n] + $teleop_bot[$n];
		$climb_total = $final_10[$n] + $final_20[$n] + $final_30[$n];
		$climb_missed = $attempt_10[$n] + $attempt_20[$n] + $attempt_30[$n];
		
		$auton_total_pts = ($auton_top[$n] * 6) + ($auton_mid[$n] * 4) + ($auton_bot[$n] * 2);
		$teleop_total_pts = ($teleop_top[$n] * 3) + ($teleop_mid[$n] * 2) + ($teleop_bot[$n]);
		$climb_total_pts = ($final_10[$n] * 10) + ($final_20[$n] * 20) + ($final_30[$n] * 30);
		
		echo "<table class='c-team-table'>
			<tbody>
				<tr class='c-team-even'>
					<td class='c-team-head'>Match ".$cur_match_num."</td>
					<td class='c-team-subhead'>Auton</td>
					<td class='c-team-subhead'>Teleop</td>
					<td class='c-team-subhead'>Climb</td>
				</tr>
				<tr class='c-team-odd'>
					<td class='c-team-subhead'>Total Points</td>
					<td class='c-team-data'>".$auton_total_pts."</td>
					<td class='c-team-data'>".$teleop_total_pts."</td>
					<td class='c-team-data'>".$climb_total_pts."</td>
				</tr>
				<tr class='c-team-even'>
					<td class='c-team-subhead'>Accuracy</td>
					<td class='c-team-data'>".$auton_total."/".($auton_total + $auton_missed[$n])."</td>
					<td class='c-team-data'>".$teleop_total."/".($teleop_total + $teleop_missed[$n])."</td>
					<td class='c-team-data'>".$climb_total."/".($climb_total + $climb_missed)."</td>
				</tr>
			</tbody>
		</table>";
	}
}
// Other type of result
else
{
	if ($eventStmt = mysqli_prepare($con, "SELECT team_number FROM team_event_".$yr." WHERE event_id = ? ORDER BY team_number ASC"))
	{
		mysqli_stmt_bind_param($eventStmt, "s", $q);
		
		mysqli_stmt_execute($eventStmt);
		
		mysqli_stmt_bind_result($eventStmt, $column1);
		
		while (mysqli_stmt_fetch($eventStmt))
		{
			$team_number[] = $column1;
		}
		mysqli_stmt_close($eventStmt);
	}
		echo"<h1 id='title'>".$q."</h1>
			<div id='c-container'>";
		
		if (isset($team_number))
		{
			foreach ($team_number as $num)
			{
				echo"<table class='c-event-table'>
					<tbody>
						<tr>
							<td class='c-team-head'>".$num."</td>
						</tr>
					</tbody>
				</table>";
			}
		}
		else
		{
			echo "<table class='c-team-table'><tr><td>This query has no data associated with it.</td></tr></table>";
		}
	echo "</div>";
}
mysqli_close($con);
?>
