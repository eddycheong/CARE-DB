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

$search = $_GET['search'];

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

	// Template search query, replace table and attribute
	if(isset($_GET['search']))
		$query = searchPartialName($search, "doctor", "ename");
	else {
		$query = "select *
			  from doctor";	
	}

	$s = oci_parse($c, $query);
	oci_execute($s);

	//Oracle Fetches
	$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

function buildDoctorList($num, $arr) {

	if($num > 0) {
		echo '<table class = "pSearch">';
		echo '<tr>';
		echo '<th>Employee ID</th>';
		echo '<th>Doctor Name</th>';
		echo '<th></th>';
		echo '</tr>';
		for($i = 0; $i < $num; $i++) {
			echo '<tr>';
			echo '<td style = "width:15%;">'. $arr[$i]['EID'] .'</td>';
			echo '<td>'. $arr[$i]['ENAME'] . '</td>';
			echo '<td style = "width:1%;">';
			echo '<form method = "post" action = "appDoctorAppointments.php">';
			echo '<input type = "hidden" name = "eid" value = "'.$arr[$i]['EID'].'">';
			echo '<button type = "submit">View Appointments</button>';
			echo '</form>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo 'Search Resulted in No Matches';
	}
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Doctor Search</title>
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

		<div class = "form-container"> 	
			<form id = "search" method= "get">
				Search: <input type = text name = "search">
			</form>
		</div>
		<?php buildDoctorList($n_rows, $res); ?>
	</div>
	<div id = "footer"></div>
</body>
</html>
