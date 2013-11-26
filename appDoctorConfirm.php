<?php
include "global.php";
include "globalhelper.php";

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

$eid = $_GET['i'];
$time = $_GET['y'] . '-' . $_GET['m'] . '-'. $_GET['d'] . ' ' . $_GET['h'] .':00:00';
$doctor = $_GET['dn'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
		//echo $pid;
		$query = "insert into appointment values (".$eid.", '".$time."', 0, 0)";
		$s = oci_parse($c, $query);
		oci_execute($s);
		if($s) echo "appointment<br>".$query."<br>";
		
		oci_close($c);
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
	<div id = "header">
		<!-- <div id="error_msg"></div> -->
		<?php attachHeader(); ?>
	</div>

	<div id = "menu-nav">
                <?php buildMenuTab(); ?>
	</div>

	<!--Make this a header of the file Cindy-->
	
	<h3 id = "pagetitle">Review the Appointment</h3>
	<div id = "content">
		<?php
		// sample code to use result from search
			echo '<table class = "pSearch">';
			echo '<tr>';
			echo '<th>Doctor ID</th>';
			echo '<td>'.$eid.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Doctor Name</th>';
			echo '<td>'.$doctor.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<th>Date & Time</th>';
			echo '<td>'.$time.'</td>';
			echo '</tr>';
			echo '</table>';
		?>
		<form id = "confirm" method= "post">
			<input id = "search" type = "submit" name = "confirm" value = "Confirm">
		</form>
	</div>
	<div id = "footer"></div>
</body>
</html>
