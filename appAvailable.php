<?php
include "global.php";
include "globalhelper.php";
//include "links.php";

// Do not remove these few lines of code unless for good reasons
// These sessions keep users remain logged in as themselves
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

// If no one is logged in, redirect them to the login page
if(!(isset($_SESSION['login']) || $_SESSION['login'] == '')) {
	header("Location: login.php");
}

// usertype test
$utype = getUserType();
echo $utype;
//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

if($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Obtain the search statement
	$search = $_POST['search'];
	//echo $search;

	// Break down the string into pieces
	$pieces = explode(" ", $search);
	$n_pieces = sizeof($pieces);

	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
		$query = "select *
			 from doctor";
		$s = oci_parse($c, $query);
		oci_execute($s);
		
		$d_rows = oci_fetch_all($s, $doctor, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_close($c);
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
}
echo $s;
echo $d_rows;
$viewingMonth = $_REQUEST['m'];
$viewingYear = $_REQUEST['y'];
$viewingDay = $_REQUEST['d'];

$tableAvailable = '<table width="800" style="text-align:center; padding:80px; border="0" cellspacing="0" cellpadding="0">';
$tableAvailable .= '<tr align="center">';
$tableAvailable .= '<th width="50%" align="left">Time</th>';
$tableAvailable .= '<th width="50%" align="right">Select Available Time</th>';
$tableAvailable .= '</tr>';
for($i=0; $i<11;$i++){
	$timeSlot = ($i<4)? 9+$i: $i-3;
	$tableAvailable .= '<tr align="center">';
	$tableAvailable .= '<td width="50%" align="left">'. $timeSlot. ':00</td>';
	$tableAvailable .= '<td width="50%" align="right">';
	$tableAvailable .= '<table border="1" width="100%">';
	$tableAvailable .= '<tr align="center">';
	$tableAvailable .= 'Hello World'. $d_rows;
	for($j=0; $j<$d_rows;$j++){
		
		//availability check (booked? not booked yet?)
		$tableAvailable .= '<td> Available'. $j. '</td>';
	}
	$tableAvailable .= '</tr>';	
	$tableAvailable .= '</table>';
	$tableAvailable .= '</td>';
	$tableAvailable .= '</tr>';	
}
$tableAvailable .= '</table>';

?>

<!--Design the page below-->
<html>
<head>
	<title>Template</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<h1 style = "margin-bottom: 0;"> 			
			Select Available Time
		</h1>
	</div>
	<div id = "content">
		<h3 style = "margin-bottom: 0; padding-top: 40;"> 	
			<?php 
				echo $monthNames[$viewingMonth-1]. ' '. $viewingDay. ' '. $viewingYear;
			?>
		</h3>
		<?php 
			echo $tableAvailable;
		?>
		<a></a>
	</div>
</body>
</html>
