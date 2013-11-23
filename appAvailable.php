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

//=======================
//       READ ME
//=======================

// For new files, (eg. newpage.php) run this command in console:
// chmod 755 newpage.php

//===================
// CONNECT TO ORACLE
//===================
if ($c = oci_connect ($ora_usr, $ora_pwd, "ug")) {
	$query = "select *
		 from doctor";
	$s = oci_parse($c, $query);
	oci_execute($s);
	
	$d_rows = oci_fetch_all($s, $doctor, null, null, OCI_FETCHSTATEMENT_BY_ROW);

	$query = "select *
		 from appointment";
	$s = oci_parse($c, $query);
	oci_execute($s);
	
	$a_rows = oci_fetch_all($s, $appointment, null, null, OCI_FETCHSTATEMENT_BY_ROW);

	oci_close($c);
} else {
	$err = oci_error();
	echo "Oracle Connect Error " . $err['message'];
}

//get previously clicked y,m,d 
$viewingYear = $_REQUEST['y'];
//change format from yyyy to yy
$old_year_timestamp = strtotime($viewingYear);
$new_viewingYear = date('y', $old_year_timestamp);

$viewingMonth = $_REQUEST['m'];
$viewingDay = $_REQUEST['d'];

$tableAvailable = '<table width="800" style="text-align:center; padding:80px; margin-left:185px; border="0" cellspacing="0" cellpadding="0">';
$tableAvailable .= '<tr align="center">';
$tableAvailable .= '<th width="50%" align="center">Time</th>';
$tableAvailable .= '<th width="50%" align="center">Select Available Time</th>';
$tableAvailable .= '</tr>';

for($i=0; $i<11;$i++){
	$time = ($i<4)? 9+$i: $i-3;
	$tableAvailable .= '<tr align="center">';
	$tableAvailable .= '<td width="20%" align="center">'. $time. ':00</td>';
	$tableAvailable .= '<td align="center">';
	$tableAvailable .= '<table border="1" width="100%">';
	$tableAvailable .= '<tr align="center">';
	for($j=0; $j<$d_rows;$j++){
		$tableCreated = false;
		$doctorName = $doctor[$j]['ENAME'];
		for($k=0; $k<$a_rows;$k++){	
			$doctorID = $doctor[$j]['EID'];
			$appDoctorID = $appointment[$k]['EID'];
			$appTime = $appointment[$k]['TIME'];
			$date = DateTime::createFromFormat('y-m-d H:i:s', $appTime);
			$y = $date->format('y');
			$m = $date->format('m');
			$d = $date->format('d');
			$hr = $date->format('H');
			$min = $date->format('i');
			// $y = 13;
			// $m = 11;
			// $d = 02;
			// $hr = 09;
			// $min = 00;
			if(($doctorID==$appDoctorID) 
				&& ($hr==$time) 
				&& ($min=='00') 
				&& ($y == $new_viewingYear)
				&& ($m == $viewingMonth)
				&& ($d == $viewingDay)
				&& ($tableCreated == false)){
				$tableCreated = true;
				$tableAvailable .= '<td width="50" bgcolor="#7DC3E3">'. $doctorName. '</td>';
			}
		}
		if($tableCreated == false){
			$_SESSION['AppDoctorID']=$doctorID;
			$_SESSION['AppTime']= $new_viewingYear. '-'. $viewingMonth. '-'. $viewingDay. ' '.$hr. ':00:00';

			$tableAvailable .= '<td width="50" bgcolor="#7DC3E3"><a href="appPatientSearch.php">'. $doctorName. '</a></td>';
		}
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
		<?php attachHeader(); ?>
	</div>
	<div id = "menu-nav">
                <?php buildMenuTab(); ?>
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
