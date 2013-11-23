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


if($_SERVER['REQUEST_METHOD'] == 'GET') {

	$search = $_GET['search'];

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		$query = searchPartialName($search, "patient", "pname");
		$s = oci_parse($c, $query);
		oci_execute($s);

		//Oracle Fetches
		$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}

function buildPatientList($num, $arr) {

	if($num > 0) {
		echo '<table class = "center">';
		echo '<tr>';
		echo '<th>Patient Name</th>';
		echo '<th>Address</th>';
		echo '<th>Phone Number</th>';
		echo '</tr>';
		for($i = 0; $i < $num; $i++) {
			echo '<tr>';
			echo '<td>'. $arr[$i]['PNAME'] .'</td>';
			echo '<td>'. $arr[$i]['ADDRESS'] .'</dh>';
			echo '<td>'. $arr[$i]['PHONE'] .'</td>';
			if(!(getUserType() == "doctor")) {
				echo '<td>';
				echo '<form style = "text-align: center; background-color: white;" method = "post" action = appPatientAppointments.php>';
				echo '<button type = "submit" name = "pAppointment" value ="'. $arr[$i]['PID'] .'">View Appointment</button>';
				echo '</form>';
				echo '</td>';
			} else {
				echo '<td>';
				echo '<form style = "text-align: center;" method = "post" action = appMedicalRecords.php>';
				echo '<button type = "submit" name = "addpatient" value ="'. $arr[$i]['PID'] .'">View Medical Record</button>';
				echo '</form>';
				echo '</td>';
	
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Patient Search</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"></div>

	<div id = "menu-nav">
		<?php buildMenuTab(); ?>	
	</div>

	<div id = "content">
		<form id = "search" method= "get">
			Search: <input type = text name = "search">
		</form>

		<?php buildPatientList($n_rows, $res); ?>
	</div>
	<div id = "footer"></div>
</body>
</html>
