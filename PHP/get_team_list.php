<?php
	// Create connection
	$con=mysqli_connect("localhost","James","bootsector","t3_prototype_2013_14_db");

	// Check connection
	if (mysqli_connect_errno($con)){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$result = mysqli_query($con,"SELECT * FROM kettering_teams");

	while($row = mysqli_fetch_array($result)){
	  $a[] = $row['team_name'];
    }

//get the q parameter from URL
$q=$_GET["q"];

//lookup all hints from array if length of q>0
if (strlen($q) > 0)
  {
  $hint="";
  for($i=0; $i<count($a); $i++)
    {
    if (strtolower($q)==strtolower(substr($a[$i],0,strlen($q))))
      {
      if ($hint=="")
        {
        $hint="<option value=\"".$a[$i]."\" >";
        }
      else
        {
        $hint=$hint." <option value=\"".$a[$i]."\">";
        }
      }
    }
  }

// Set output to "no team by that name" if no hints were found
// or to the correct values
if ($hint == "")
  {
  $response ="No team by that name";
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;

mysqli_close($con);

?>
