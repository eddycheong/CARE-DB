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

if(isset($_SESSION['doctor']))
	$eid = $_SESSION['doctor'];


if(($_SERVER['REQUEST_METHOD'] == 'POST') || isset($_SESSION['doctor'])) {
	if(isset($_POST['eid']))
		$eid = $_POST['eid'];

	if(isset($_POST['CANCEL'])) {
		$eid = $_POST['EID'];
		$time = $_POST['TIME'];
	}

	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Execute if cancel is triggered
		if(isset($_POST['CANCEL'])) {
			$query = "delete from appointment
				  where eid = ".$eid."
				  and time = '".$time."'";

			$s = oci_parse($c, $query);
			oci_execute($s);
			oci_fetch($s);
		}

		$query = "select distinct p.pname, a.time
			  from appointment a, patient p
			  where a.pid = p.pid and a.eid =".$eid ;
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
function buildSchedule($num, $arr, $eid) {
	if($num > 0) {
	echo '<table class = "pSearch">';
	echo '<tr>';
	echo '<th>Schedule Appointment</th>';
	echo '<th>Schedule Date</th>';
	echo '<th>Scheduled Time</th>';
	if(!isset($_SESSION['doctor'])) 
		echo '<th></th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>';
		if($arr[$i]['PID'] == 0)
			echo 'Board Meeting';
		else 
			echo $arr[$i]['PNAME'];
		echo '</td>';		
		$timestamp = strtotime($arr[$i]['TIME']);
		echo '<td>'. date("F j, Y", $timestamp);
		echo '<td>'. date("G:i a", $timestamp);

		$endtimestamp = mktime(date("G", $timestamp)+1, date("i", $timestamp), 0);
		
		echo ' - '. date("G:i a", $endtimestamp);
		echo '</td>';
		
		if(!isset($_SESSION['doctor'])) {
			echo '<td style = "width: 1%;">';
			echo '<form method = "post">';
			echo '<input type = "hidden" name = "CANCEL" value = true>';
			echo '<input type = "hidden" name = "EID" value = "'.$eid.'">';
			echo '<input type = "hidden" name = "TIME" value = "'.$arr[$i]['TIME'].'">';
		
			echo '<button type = "submit">Cancel</button>';
			echo '</form>';
			echo '</td>';	
			echo '</tr>';
		}
		}
	echo '</table>';

	} else {
		echo 'Currently No Schedule';
	}
	}
}
?>
<!--Design the page below-->
<html>
<head>
	<title>Doctor Appointment</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<!-- <div id="error_msg"></div> -->
		<?php attachHeader(); ?>
	</div>

	<div id = "menu-nav">
		<?php buildMenuTab(); ?>	
	</div>
	<div id = "content">
		<h3 id = "pagetitle">Schedule</h3>
		<?php buildSchedule($n_rows, $schedule, $eid); ?>

	</div>
	<div id = "footer"></div>
</body>
</html>
