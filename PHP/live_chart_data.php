<?php
/*
*to run the script where the fintion dosent exist
function query($query)
{
    $servername = "localhost";
    $username = "caicla";
    $password = "123456";
    $db = "bitnami_wordpress";
    $link = mysqli_connect($servername, $username, $password,$db);  
    if ($result = mysqli_query($link, $query)) {
        return $result;
    } else {
        echo 'mysql error for  ' . $query . mysqli_error($link);
        return FALSE;
    }
}
*/
//simple api for one thing
$sql_query="SELECT Procent, Material_Name, Weight FROM `station_container_material` AS SCM INNER JOIN smart_station AS SS ON SCM.Station_Id=SS.Station_Id WHERE SS.Customer_Id = 1 AND SS.Station_Name='Death Station' ORDER BY Material_Name DESC";
$result=query($sql_query);
$data;//array to be converted to json and printed
while($info=mysqli_fetch_assoc($result)){
	$data['labels'][]=$info['Material_Name'];
	$data['percent'][]=(int)$info['Procent'];
    $data['weight'][]=(float)$info['Weight'];
}
echo json_encode($data);