<?php
/*
*make funtion global to work the smothest or create own db connect funtion.
*/
function query($query)
{
  /*
  *Move the vars to another file and make theme global
  $servername = "localhost";
	$username = "caicla";
	$password = "123456";
	$db = "bitnami_wordpress";
  */
	$link = mysqli_connect($servername, $username, $password,$db);	
  	if ($result = mysqli_query($link, $query)) {
        return $result;
    } else {
        echo 'mysql error for  ' . $query . mysqli_error($link);
        return FALSE;
    }
}
?>