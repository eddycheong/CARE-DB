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

$pid = $_REQUEST['pid'];
//$pid = 5793;
	//===================
	// CONNECT TO ORACLE
	//===================
	if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {

		// Template search query, replace table and attribute
		$query = "select * from patient where pid = ".$pid." ";
		$s = oci_parse($c, $query);
		oci_execute($s);
		$n_rows = oci_fetch_all($s, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		$query2 = "select count(distinct time) as num from appointment a, patient p where a.pid=p.pid AND p.pid=".$pid;
		$s2 = oci_parse($c, $query2);
		oci_execute($s2);
		$n_rows2 = oci_fetch_all($s2, $res2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		$query3 = "select avg(fee) as avgfee from appointment a, patient p where a.pid=p.pid AND p.pid=".$pid;
		$s3 = oci_parse($c, $query3);
		oci_execute($s3);
		$n_rows3 = oci_fetch_all($s3, $res3, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		
		oci_close($c);
		
	} else {
		$err = oci_error();
		echo "Oracle Connect Error " . $err['message'];
	}
	$pname = $res[0]['PNAME'];
		$address = $res[0]['ADDRESS'];
		$phone = $res[0]['PHONE'];
		$email = $res[0]['EMAIL'];
		$carecard = $res[0]['CARECARD'];
		$appNum = $res2[0]['NUM'];
		$fee = $res3[0]['AVGFEE'];

?>

<!--Design the page below-->
<html>
<head>
	<title>View Patient Profile</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header"><br></div>

	<div id = "content">
		<?php
		// sample code to use result from search
			echo '<center>';
			echo '<table border="1">';
			echo '<tr>';
			echo '<td>ID</th>';
			echo '<td>'.$pid.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Name</th>';
			echo '<td>'.$pname.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Address</th>';
			echo '<td>'.$address.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Phone</th>';
			echo '<td>'.$phone.'</td>';
			echo '</tr>';			
			echo '<tr>';
			echo '<td>Email</th>';
			echo '<td>'.$email.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Carecard</th>';
			echo '<td>'.$carecard.'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td># of Appointments</th>';
			echo '<td>'.$appNum.'</td>';
			echo '</tr>';
			
			echo '<tr>';
			echo '<td>Average Payment</th>';
			echo '<td>$'.$fee.'.00</td>';
			echo '</tr>';
			echo '</table>';
			echo '</center>';
		
		echo '<form  method = "post" action = "editPatient.php?pid='.$pid.'">';
		echo	'<button type = "submit">Add Patient</button>';
		echo	'</form>';
		?>
	</div>

	<div id = "footer"></div>
</body>
</html>
