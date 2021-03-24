<?php
/*
*what you se after login
*/
$customers=$_SESSION['C_Name'];
//get the customers stations othe related data
$sql_query="SELECT Station_Name, Position_Description, Station_Id FROM `smart_station` WHERE Customer_Name = '".$customers."'";
$stations =query($sql_query);
echo'<section>';
echo'<h2> Välkommen '.$customers.'</h2>';
while($s_row = mysqli_fetch_assoc($stations)){
  	echo '<div>';
  	$station=$s_row['Station_Id'];
    //get the latest picka up date for the station
  	$sql_query="SELECT DISTINCT SCM.Material_Name, CM.Date FROM `collect_material` AS CM INNER JOIN station_container_material AS SCM ON CM.SCM_Id=SCM.SCM_Id INNER JOIN smart_station AS SS ON SCM.Station_Id=SS.Station_Id WHERE SS.Station_Id='".$station."' ORDER BY Date DESC,SCM.Material_Name DESC";
	$result=query($sql_query);
  	$collect=[];
  	while($row = mysqli_fetch_assoc($result)){
		if(!array_key_exists($row['Material_Name'],$collect)){
			$collect[$row['Material_Name']]=$row['Date'];
  		}
	}
  	echo '<h3>'.$s_row['Station_Name'].'</h3><span style="font-weight:bold">Lokal: '.$s_row['Position_Description'].'</span><br><br>';
    //get the content of the station
  	$sql_query="SELECT SCM.Material_Name, C.Size FROM `station_container_material` AS SCM INNER JOIN container AS C ON SCM.Container_Id=C.Container_Id WHERE SCM.Station_Id = ".$station." ORDER BY SCM.Material_Name DESC";
  	$station_content = query($sql_query);
  	echo'<table>';
	echo'<tr><th>Material</th><th>Kärl Storlek</th><th>Senaste Hämtning</th></tr>';
  	while($sc_row = mysqli_fetch_assoc($station_content)){
	  echo '<tr><td>'.$sc_row['Material_Name'].'</td><td>'.$sc_row['Size'].'</td><td>'.$collect[$sc_row['Material_Name']].'</td></tr>';
	}
  	
  	echo'</table></div>';
}
echo'</section>';