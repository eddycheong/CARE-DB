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

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Obtain the patient name
	$patient = trim($_POST['addpatient']);

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		//$query = searchPartialName($search, "employee", "ename");
		$query = "select *
				from has_medicalrecords
				where pname = '". $patient ."'";
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

// Helper Functions
function buildList($num, $arr, $patient) {
if($num == 0)
echo "Medical Record for " . $patient . " is not Available";
else{
echo "Medical Record for " . $patient . "";
	echo '<table class = "center">';
	echo '<tr>';
	echo '<th>Allergies</th>';
	echo '<th>Emergency Contacts</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['ALLERGIES'] .'</td>';
		echo '<td>'. $arr[$i]['EMERCONTACTS'] .'</td>';
		echo '</tr>';
	}
	echo '</table>';
	}
}
?>

<!--Design the page below-->
<html>
<head>
	<title>Template</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"></div>
	<div id = "content">

		<?php 
		buildList($n_rows, $res, $patient); 
		?>

	</div>

	<div id = "footer"></div>
</body>
</html>
