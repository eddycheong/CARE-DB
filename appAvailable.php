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

$tableAvailable = '<table width="700" id="availableTable">';
$tableAvailable .= '<tr align="center">';
$tableAvailable .= '<th width="25%" align="center">Time</th>';
$tableAvailable .= '<th width="75%" align="center">Select Available Time</th>';
$tableAvailable .= '</tr>';

for($i=0; $i<11;$i++){
	$t = DateTime::createFromFormat('G', 9+$i);
	$timeLabel = $t->format('g');
	$time = $t->format('h');
	$tableAvailable .= '<tr align="center">';
	$tableAvailable .= '<td align="center"><b>'. $timeLabel. ':00</b></td>';
	$tableAvailable .= '<td align="center">';
	$tableAvailable .= '<table border="0" width="100%">';
	$tableAvailable .= '<tr align="center">';
	for($j=0; $j<$d_rows;$j++){
		$tableCreated = false;
		$doctorName = $doctor[$j]['ENAME'];
		$doctorID = $doctor[$j]['EID'];
		for($k=0; $k<$a_rows;$k++){	
			$appDoctorID = $appointment[$k]['EID'];
			$appTime = $appointment[$k]['TIME'];
			$date = DateTime::createFromFormat('y-m-d H:i:s', $appTime);
			$y = $date->format('y');
			$m = $date->format('m');
			$d = $date->format('d');
			$hr = $date->format('h');
			$min = $date->format('i');
			echo $hr. ' '.$time;
			if(($doctorID==$appDoctorID) 
				&& ($hr==$time) 
				&& ($min=='00') 
				&& ($y == $new_viewingYear)
				&& ($m == $viewingMonth)
				&& ($d == $viewingDay)
				&& ($tableCreated == false)){
				$tableCreated = true;
				$tableAvailable .= '<td width="50%" class="doctorAvailable notAvailable"><b>Booked</b></td>';
			}
		}
		if($tableCreated == false){
			$tableAvailable .= '<td width="50%" class="doctorAvailable available"><a id="appSlot" class="doctorTable" href="appAddPatientSearch.php?i='.$doctorID.'&dn='.$doctorName.'&y='. $new_viewingYear . '&m=' . $viewingMonth . '&d=' . $viewingDay . '&h='.$time.'"><b>'. $doctorName. '</b></a></td>';
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
	<title>Available</title>
	<link rel = "stylesheet" type = "text/css" href= "./styles/styling.css">
</head>
<body style = "text-align: center;">
	<div id = "header">
		<div id="error_msg"></div>
		<?php attachHeader(); ?>
	</div>
	<div id = "menu-nav">
                <?php buildMenuTab(); ?>
	</div>
	<div id = "content">
		<h3 style = "margin-bottom: 0; padding-top: 40; color: #003366; font-weight: bold;"> 	
			<?php 
				echo '&#60'.$monthNames[$viewingMonth-1]. ' '. $viewingDay. ', '. $viewingYear.'&#62';
			?>
		</h3>
		<?php 
			echo $tableAvailable;
		?>
		<a></a>
	</div>
</body>
</html>
