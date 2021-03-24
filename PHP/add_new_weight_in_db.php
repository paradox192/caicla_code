<?php
/*
*The brain to update db and notify the collectior when new material weights gets add from the stations
*/
if ($_POST['post'] == 'Send' && isset($_POST['post'])){
	$customer = $_POST['customer'];
	$station = $_POST['station'];
	$material = $_POST['material'];
	$weight = $_POST['weight'];
	$fill = $_POST['fill'];
 	/*
 	*to send mail if fill is 85 and weight is 25
 	$sql_query = "SELECT Address FROM `customers` WHERE Customer_Name='".$customer."'";
	$result=query($sql_query);
  	$row = mysqli_fetch_assoc($result);
  	$address = $row['Address'];
  	if($fill == 85 && $weight >= 25){
		$to      = 'nobody@example.com';
		$subject = 'Staion har fullt kärl, hämta snarast';
		$message = 'Företag: '.$customer.'\n\r Station: '.$station.'\n\r Material: '.$material.'\n\r Vikt: '.$weight.'\n\r Adress: '.$address;
		$sent=mail($to, $subject, $message);
	  	if($sent){
			echo'Mail har skickats'; 
		}else{
			echo'Mail har inte skickats';
		}
	}
  */
	//get the row that corespond to customer material and station if not Create it;  
	$sql_query = "SELECT SCM.Weight, SCM.SCM_Id, SCM.Date, SCM.Station_Id, SS.Customer_Id FROM station_container_material AS SCM INNER JOIN smart_station AS SS ON SCM.Station_Id = SS.Station_Id INNER JOIN customers ON SS.Customer_Id = customers.Customer_Id WHERE Material_Name = '".$material."' AND customers.Customer_Name = '".$customer."' AND SS.Station_Name = '".$station."'";
	$result=query($sql_query);
	$nr_rows = mysqli_num_rows($result);
	if ($nr_rows == 0) {
		$date_ym = date('Y-m');
	  	$date_ymd = date('Y-m-d');
		//get the station_id
		$sql_query = "SELECT Station_Id FROM smart_station INNER JOIN customers ON smart_station.Customer_Id = customers.Customer_Id WHERE Station_Name = '".$station."' AND Customer_Name = '".$customer."'";
		$result = query($sql_query);
		$row = mysqli_fetch_assoc($result);
		$station_id = $row['Station_Id'];
		$sql_query = "INSERT INTO `Station_Container_Material`(`SCM_Id`, `Station_Id`, `Container_Id`, `Material_Name`, `Weight`, `Procent`, `Date`) VALUES (NULL,".$stationId.",1,'".$material."',".$weight.",".$fill.",'".$date_ymd."')";
		query($sql_query);

		$sql_query="SELECT Weight_History_Id, Weight FROM `weight_history` WHERE Customer_Id = ".$db_customer." AND Material_Name = '".$material."' AND Date LIKE '".$date_ym."%'";
		$result=query($sql_query);
		$nr_rows = mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);
		$db_history=$row['Weight_History_Id'];
		$db_weight_wh = $row['Weight'];
		$weight_add_db=$weight+$db_weight_wh;
	  	if ($nr_rows != 0) {//checks if there exist a row already if not create it
			//uppdate weight
			$sql_query ="UPDATE `weight_history` SET `Weight` = '".$weight_add_db."' WHERE `Weight_History_Id` = ".$db_history;
			query($sql_query);
		}else{
			$sql_query="INSERT INTO `weight_history` (`Weight_History_Id`,`Customer_Id`, `Material_Name`, `Weight`, `Date`) VALUES (NULL,'".$db_customer."', '".$material."', '".$weight_add_db."', '".$date_ym."-01')";
			query($sql_query);
		}
	}else{
	  	$date_ym = date('Y-m');
	  	$date_ymd = date('Y-m-d');
		$diff=0;
		$weight_add_db=0;
		//checkes if there is more than one row
		if($nr_rows<2){
			$row = mysqli_fetch_assoc($result);
			$db_customer = $row['Customer_Id'];
			$db_station = $row['Station_Id'];
			$db_weight = $row['Weight'];
			$db_scm = $row['SCM_Id'];
			//Update weight in SCM table
			$sql_query="UPDATE `station_container_material` SET `Weight`='".$weight."',`Procent`='".$fill."',`Date` = '".$date_ymd."' WHERE `SCM_Id` = ".$db_scm;
			query($sql_query);


			
			$diff = ($weight-$db_weight);
			if ($diff <= -1) {//to check if there has been a collection of the material
				$diff=$weight;
			  	$sql_query = "INSERT INTO `collect_material` (`Collect_Material_Id`, `SCM_Id`, `Date`) VALUES (NULL, '".$db_scm."', '".$date_ymd."')";
			  	query($sql_query);
			}
			//get the coresponding row from Weight_History by customer_id, material and date check if it exist
			$sql_query="SELECT Weight_History_Id, Weight FROM `weight_history` WHERE Customer_Id = ".$db_customer." AND Material_Name = '".$material."' AND Date LIKE '".$date_ym."%'";
			$result=query($sql_query);
		  	$nr_rows = mysqli_num_rows($result);
		  	$row = mysqli_fetch_assoc($result);
			$db_history=$row['Weight_History_Id'];
			$db_weight_wh = $row['Weight'];
			$weight_add_db=$db_weight_wh+$diff;
		  	if ($nr_rows != 0) {//checks if there exist a row already
				//uppdate weight
				$sql_query ="UPDATE `weight_history` SET `Weight` = '".$weight_add_db."' WHERE `Weight_History_Id` = ".$db_history;
				query($sql_query);
			}else{
				$sql_query="INSERT INTO `weight_history` (`Weight_History_Id`,`Customer_Id`, `Material_Name`, `Weight`, `Date`) VALUES (NULL,'".$db_customer."', '".$material."', '".$weight_add_db."', '".$date_ym."-01')";
				query($sql_query);
			}

		}else{//if there is more then 1 rows do 
			$tot_diff=0;
		  	while ($row = mysqli_fetch_assoc($result)) {
				$db_customer = $row['Customer_Id'];
				$db_station = $row['Station_Id'];
				$db_proc[] = $row['Procent'];
				$db_scm[] = $row['SCM_Id'];
				$db_date[] = $row['Date'];
				$db_weight[] = $row['Weight'];
			}

			$date = min($db_date);//get the oldest date
			for ($i=0; $i <$nr_rows ; $i++) { 
				if ($db_date[$i] == $date) {
					$diff = ($weight-$db_weight[$i]);
					if ($diff <= -1) {//to check if there has been a collection of the material
						$diff=$weight;
					  	$sql_query = "INSERT INTO `collect_material` (`Collect_Material_Id`, `SCM_Id`, `Date`) VALUES (NULL, '".$db_scm[$i]."', '".$date_ymd."')";
					  	query($sql_query);
					}
					$sql_query="UPDATE `station_container_material` SET `Weight` = '".$weight."' ,`Procent`='".$fill."',`Date`='".$date_ymd."' WHERE `SCM_Id` = ".$db_scm[$i];
					query($sql_query);		
				}
			}

			$sql_query="SELECT Weight_History_Id, Weight FROM `weight_history` WHERE Customer_Id = ".$db_customer." AND Material_Name = '".$material."' AND Date LIKE '".$date_ym."%'";
		  	$result=query($sql_query);	
		  	$nr_rows = mysqli_num_rows($result);
		  	$row = mysqli_fetch_assoc($result);
			$db_history=$row['Weight_History_Id'];
			$db_weight_wh = $row['Weight'];
			$weight_add_db=$db_weight_wh+$diff;
			if ($nr_rows != 0) {//checks if there exist a row already
				//uppdate weight
				$sql_query ="UPDATE `weight_history` SET `Weight` = '".$weight_add_db."' WHERE `Weight_History_Id` = ".$db_history;
				query($sql_query);
			}else{
				$sql_query="INSERT INTO `weight_history` (`Weight_History_Id`,`Customer_Id`, `Material_Name`, `Weight`, `Date`) VALUES (NULL,'".$db_customer."', '".$material."', '".$weight_add_db."', '".$date_ym."-01')";
				query($sql_query);
			}
		}
		
	}
}