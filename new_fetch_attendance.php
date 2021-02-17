<?php

//error_reporting(-1);
//ini_set('display_errors', 1);
$servername = "192.168.1.107";
$username = "root";
$password = "";
$dbname = "bigstone_hrms";
//echo "works\n";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
	echo "Connection success";
}
$odbc=odbc_connect('biomet','sa','admin@123');
if($odbc)
{
	echo "Connected";
}
else
{
	echo "Failed";
}
if($odbc)
{
	
	$sql="SELECT * from dbo.Attlogs WHERE LogDatatime>=DATEADD(day,-7, GETDATE());";
	//$sql="SHOW TABLES;"
	$result=odbc_exec($odbc,$sql);
	while($row=odbc_fetch_array($result))
	{
			//print_r($row);
			$employee_id=isset($row['Empcode'])?$row['Empcode']:"";
			$log_datetime=isset($row["LogDatatime"])?$row["LogDatatime"]:"";
			$log_date=isset($row["LogDatatime"])?$row["LogDatatime"]:"";
			$direction=isset($row['dir'])?$row['dir']:"";
			$bulk_data[]="('".$employee_id."','".$log_date."','".$log_datetime."','".$direction."')";
			
			
			
	}
	print_r($bulk_data);
	//die();
	if(isset($bulk_data) && count($bulk_data)>0)
	{
		          $quer=implode(',',$bulk_data);
		          $qi="INSERT IGNORE INTO `attendance_log` (employee_id,log_date,log_datetime,direction)VALUES". 
		          $quer;
		          if($conn->query($qi) === TRUE)
		          {
		          		echo "Successfully synced attendance data\n";
		          }
		          else
		          {
		          	 	echo "There was some issue in syning attendance data\n";
		          	 	echo "************************************************\n";
		          	 	echo $qi."\n";
		          	 	echo "************************************************\n";
		          }
		          unset($bulk_data);
		          unset($quer);
	}
}

?>