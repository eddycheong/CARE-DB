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
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
	if(isset($_REQUEST['mm'])){
		// Template search query, replace table and attribute
		$query = "Select Temp.id, P.pname, Temp.fee
				From Temp, patient P
				WHERE Temp.fee in ( SELECT ". $_REQUEST['mm']. "(Temp.fee) FROM Temp) and Temp.id =P.pid";

		$s = oci_parse($c, $query);
		oci_execute($s);
		//Oracle Fetches
		$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	}
	else{
		$query = "Select Temp.id, P.pname, Temp.fee From Temp, patient P where Temp.id =P.pid";
		$d = oci_parse($c, $query);
		oci_execute($d);
		//Oracle Fetches
		$n_rows = oci_fetch_all($d, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	}
	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

// Helper Functions
function buildList($num, $arr) {
	echo '<a href="minMaxFee.php?mm=min">MIN</a>';
	echo '<a href="minMaxFee.php?mm=max">MAX</a>';
	echo '<a href="minMaxFee.php">ALL</a>';
	echo '<table class = "center">';
	echo '<tr>';
	echo '<th>PId</th>';
	echo '<th>Patient Name</th>';
	echo '<th>Avarage Paid Fee</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['ID'] .'</td>';
		echo '<td>'. $arr[$i]['PNAME'] .'</td>';
		echo '<td>'. $arr[$i]['FEE'] .'</td>';
		echo '</tr>';
	}
	echo '</table>';
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
	<div id = "menu-nav"></div>
	<div id = "content">

		<?php buildList($n_rows, $res); ?>

	</div>

	<div id = "footer"></div>
</body>
</html>
