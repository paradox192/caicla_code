<?php
$sql_query="SELECT DISTINCT SCM.Material_Name, CM.Date FROM `collect_material` AS CM INNER JOIN station_container_material AS SCM ON CM.SCM_Id=SCM.SCM_Id INNER JOIN smart_station AS SS ON SCM.Station_Id=SS.Station_Id WHERE SS.Station_Name='Death Station' ORDER BY Date DESC,SCM.Material_Name DESC";
$result=query($sql_query);
$material=[];
//to get the latest Collections of the materials
while($row = mysqli_fetch_assoc($result)){
  if(!array_key_exists($row['Material_Name'],$material)){
	$material[$row['Material_Name']]=$row['Date'];
  }
	
}
//print the array
echo'<ul>';
foreach ($material as $key => $value){
	 echo'<li>'.$key.':	'.$value .'</li>';
}
echo'</ul>';