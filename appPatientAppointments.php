<?php
include "global.php";
include "globalhelper.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pid = $_POST['pAppointment'];

	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		$query = "select d.ename, p.pname, a.time, a.fee
			  from appointment a, patient p
			  inner join schedule s on p.pid = s.pid
			  inner join doctor d on d.eid = s.deid
			  where a.eid = s.deid and s.pid =".$pid;

	
		echo $pid, $query;
		/*
		$query = "select p.pname, s.time
			  from patient p, schedule s
			  where p.pid = s.pid and s.pid = ".$pid;
		*/
		$s = oci_parse($c, $query);
		oci_execute($s);

		//Oracle Fetches
		$n_rows = oci_fetch_all($s, $schedule, null, null, OCI_FETCHSTATEMENT_BY_ROW);

	oci_close($c);

} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

// Helper Functions
function buildSchedule($num, $arr, $pid) {
	echo '<table class = "center">';
	echo '<tr>';
	echo '<th>Doctor Name</th>';
	echo '<th>Schedule Date</th>';
	echo '<th>Scheduled Time</th>';
	echo '<th>Fee</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['ENAME'] .'</td>';
		$timestamp = strtotime($arr[$i]['TIME']);
		echo '<td>'. date("F j, Y", $timestamp);
		echo '<td>'. date("G:i a", $timestamp);

		$endtimestamp = mktime(date("G", $timestamp)+1, date("i", $timestamp), 0);
		
		echo ' - '. date("G:i a", $endtimestamp);
		echo '</td>';
		echo '<td>'. $arr[$i]['FEE'] .'</td>';
		echo '<td>';
		echo '<a href = "appScheduleRemove.php?PID='. $pid .'">Cancel</a>';
		echo '</td>';	
		echo '</tr>';
		}
	echo '</table>';
	}
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Patient Appointment</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"></div>

	<div id = "menu-nav">
		<div class = "menu-item">
			<p>Schedule</p>
			<a href = "appSchedule.php" class = "fill-link"></a>
		</div>
		<div class = "menu-item">
			<p>Patient</p>
			<a href = "appPatientSearch.php" class = "fill-link"></a>
		</div></div>
	<div id = "content">

		<?php buildSchedule($n_rows, $schedule, $pid); ?>

	</div>
<!-- Need to learn divs, work on UI later-->
<!--	<div id = "leftMargin">
	</div>
-->
	<div id = "footer"></div>
</body>
</html>
