<?php
	// Create connection
	$con = mysqli_connect("localhost", "James", "bootsector", "t3_prototype_2013_14_db");

	// Check connection
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

//get the q parameter from URL
$q = $_GET["q"];
$ql = strtolower($q);
$return[] = "";

	if ($teamStmt = mysqli_prepare($con, "SELECT team_number, team_name FROM team_list WHERE team_number LIKE CONCAT(?, '%') OR team_name LIKE CONCAT(?, '%')"))
	{
		mysqli_stmt_bind_param($teamStmt, "is", $q, $q);
		
		mysqli_stmt_execute($teamStmt);
		
		mysqli_stmt_bind_result($teamStmt, $col1, $col2);
		
		while (mysqli_stmt_fetch($teamStmt))
		{
			$return[] = $col1." - ".$col2;
		}
		mysqli_stmt_close($teamStmt);
	}
	
	if ($eventStmt = mysqli_prepare($con, "SELECT event_id, event_name FROM event_list WHERE event_id like CONCAT(?, '%') OR event_name like CONCAT(?, '%')"))
	{
		mysqli_stmt_bind_param($eventStmt, "ss", $q, $q);
		
		mysqli_stmt_execute($eventStmt);
		
		mysqli_stmt_bind_result($eventStmt, $col1, $col2);
		
		while (mysqli_stmt_fetch($eventStmt))
		{
			$return[] = $col1." - ".$col2;
		}
		mysqli_stmt_close($eventStmt);
	}
	
	if (count($return) > 12) {
		$max = 12;
	} else {
		$max = count($return);
	}
	for ($s = 1; $s < $max; $s++) {
		echo "<div class='c-list-container'>
			<table class='c-list-table'>
				<tr>
					<td class='c-list-data'>".$return[$s]."</td>
				</tr>
			</table>
		</div>";
	}
mysqli_close($con);
?>
