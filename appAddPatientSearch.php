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
		$query = searchPartialName($search, "patient", "pname");
	else {
		$query = "select *
			  from patient";	
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

function buildPatientList($num, $arr) {

	if($num > 0) {
		echo '<table class = "pSearch">';
		echo '<tr>';
		echo '<th>Patient Name</th>';
		echo '<th>Address</th>';
		echo '<th>Phone Number</th>';
		echo '</tr>';
		for($i = 0; $i < $num; $i++) {
			if($arr[$i]['PID'] != 0) {
				echo '<tr>';
				echo '<td>'; 
				echo '<a style = "text-decoration: none; color: #003366; font-weight: bold;" href = "appConfirm.php?i='.$_GET['i'].'&dn='.$_GET['dn'].'&y='.$_GET['y'].'&m='.$_GET['m'].'&d='.$_GET['d'].'&h='.$_GET['h'].'&pid='.$arr[$i]['PID'].'&pname='.$arr[$i]['PNAME'].'">';
				echo $arr[$i]['PNAME'];
				
				echo '</a>';
				echo '</td>';
				echo '<td>'. $arr[$i]['ADDRESS'] .'</dh>';
				echo '<td>'. $arr[$i]['PHONE'] .'</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	} else {
		echo 'Search Resulted in No Matches';
	}
}
//Hacky way for passing values to appAddPatient.php
$_SESSION['i'] = $_GET['i'];
$_SESSION['dn'] = $_GET['dn'];
$_SESSION['y'] = $_GET['y'];
$_SESSION['m'] = $_GET['m'];
$_SESSION['d'] = $_GET['d'];
$_SESSION['h'] = $_GET['h'];

?>

<!--Design the page below-->
<html>
<head>
	<title>Patient Search</title>
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
			<form  method = "post" action = "appAddPatient.php">
				<button type = "submit">Add Patient</button>
			</form>
		</div>
		<?php buildPatientList($n_rows, $res); ?>
	</div>
	<div id = "footer"></div>
</body>
</html>
