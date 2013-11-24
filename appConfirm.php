<?php
include "global.php";
include "globalhelper.php";
include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) || $_SESSION['login'] == '')) {
	header("Location: login.php");
}

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

	//===================
	// CONNECT TO ORACLE
	//===================

$eid = $_SESSION['AppDoctorID'];
$time = $_SESSION['AppTime'];
$pid =$_REQUEST['pid'];
$pname = $_REQUEST['pname'];
$doctor = $_SESSION['dname'];
$fee = rand(50,150);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
		//echo $pid;
		$query = "insert into appointment values (".$eid.", '".$time."', ".$fee.",".$pid.")";
		$s = oci_parse($c, $query);
		oci_execute($s);
		//if($s) echo "appointment<br>".$query."<br>";
		
		$query2 ="insert into schedule values (2, ".$eid.", ".$pid.", '".$time."')"; 		
		$s2 = oci_parse($c, $query2);
		oci_execute($s2);
		//if($s2) echo "schedule<br>".$query2."<br>";
		
		oci_close($c);
		unset($_SESSION['AppDoctorID']);
		unset($_SESSION['AppTime']);
		unset($_SESSION['dname']);
		header("Location: appSchedule.php");
			
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}

?>

<!--Design the page below-->
<html>
<head>
	<title>AppConfirm</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"></div>

	<div id = "menu-nav">
                <?php buildMenuTab(); ?>
	</div>

	<!--Make this a header of the file Cindy-->
	Review The Appointment
	<div id = "content">
		<?php
		// sample code to use result from search
			echo '<center>';
			echo '<table border="1">';
			echo '<tr>';
			echo '<td>ID</th>';
			echo '<td>'.$pid.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Name</th>';
			echo '<td>'.$pname.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Date&Time</th>';
			echo '<td>'.$time.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Doctor</th>';
			echo '<td>'.$doctor.'</td>';
			echo '</tr>';
			echo '</table>';
			echo '</center>';
		?>
		<form id = "confirm" method= "post">
			<input type = "submit" name = "confirm" value = "Confirm">
		</form>
	</div>
	<div id = "footer"></div>
</body>
</html>
