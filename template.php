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

	// Obtain the search statement
	$search = $_GET['search'];

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		$query = searchPartialName($search, "employee", "ename");
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
function buildList($num, $arr) {
	echo '<table class = "center">';
	echo '<tr>';
	echo '<th>EID</th>';
	echo '<th>Employee Name</th>';
	echo '</tr>';
	for($i = 0; $i < $num; $i++) {
		echo '<tr>';
		echo '<th>'. $arr[$i]['EID'] .'</th>';
		echo '<th>'. $arr[$i]['ENAME'] .'</th>';
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

	<div id = "content">
		<form id = "search" method= "get">
			<input type = text name = "search">
		</form>

		<?php buildList($n_rows, $res); ?>

	</div>

	<div id = "footer"></div>
</body>
</html>
