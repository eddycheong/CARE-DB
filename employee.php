<?php
include "global.php";
include "globalhelper.php";
include "links.php";

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

	// Template search query, replace table and attribute
	$query = "select *
		 from employee";
	$s = oci_parse($c, $query);
	oci_execute($s);

	//Oracle Fetches
	$e_rows = oci_fetch_all($s, $employee, null, null, OCI_FETCHSTATEMENT_BY_ROW);

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

// Helper Functions
function buildEmployeeList($arr, $rows) {
	echo '<center>';
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>EID</th>';
	echo '<th>Employee Name</th>';
	echo '<th>Social Insurance Number</th>';
	echo '</tr>';

	for($i = 0; $i < $rows; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['EID'] .'</td>';
		echo '<td>'. $arr[$i]['ENAME'] .'</td>';
		echo '<td>'. $arr[$i]['SIN'] .'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '</center>';
}

function buildDoctorList($arr, $rows) {
	echo '<center>';
	echo '<table border="1">';
	echo '<tr>';
	echo '<th>EID</th>';
	echo '<th>Employee Name</th>';
	echo '<th>Social Insurance Number</th>';
	echo '<th>Qualifcation</th>';
	echo '<th>License</th>';
	echo '</tr>';
	for($i = 0; $i < $rows; $i++) {
		echo '<tr>';
		echo '<td>'. $arr[$i]['EID'] .'</td>';
		echo '<td>'. $arr[$i]['ENAME'] .'</td>';
		echo '<td>'. $arr[$i]['SIN'] .'</td>';
		echo '<td>'. $arr[$i]['QUALIFICATION'] .'</td>';
		echo '<td>'. $arr[$i]['LICENSE'] .'</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '</center>';
}

?>

<!--Design the page below-->
<html>
<head>
	<title>Employee</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<h1 style = "margin-bottom: 0;">Employee</h1>
	</div>
	
	<div id = "side-panel">
	<?php
		// assign arr based on user type
		$arr = $aArr;
		
		buildSideLink($arr);
	?>
	</div>


	<div id = "content">
		<!--Content appears here-->
		<br><br>
		<!--<form id = "search" name "" method= "post">
			<input type = text name = "search" value "">
		</form>-->

		<?php
		buildEmployeeList($employee, $e_rows);
		buildDoctorList($doctor, $d_rows);
		?>
	</div>
<!-- Need to learn divs, work on UI later-->
<!--	<div id = "leftMargin">
	</div>

	<div id = "footer">
	</div>
-->
</body>
</html>
